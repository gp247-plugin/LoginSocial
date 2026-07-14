<?php
#App\GP247\Plugins\LoginSocial\Livewire\AdminLivewire.php

namespace App\GP247\Plugins\LoginSocial\Livewire;

use App\GP247\Plugins\LoginSocial\Helpers\ProviderConfig;
use GP247\Core\AdminShell\Infrastructure\GP247AdminComponent;
use GP247\Core\Models\AdminConfig;
use Illuminate\Contracts\View\View;

/**
 * Admin settings screen for the LoginSocial plugin: per-provider (facebook,
 * google, github) enabled flag, client id/secret and redirect URL, backed by
 * the admin_config key/value table under the same group/key/code scheme the
 * legacy AdminController used (LoginSocial_config code, "{provider}_{field}"
 * keys), plus a read-only view of the configured auth guards.
 *
 * The client secret is never hydrated into a public Livewire property (those
 * are serialized into the page for client-side hydration) — only a boolean
 * hasSecret flag is exposed, so the real value never reaches the page source.
 */
class AdminLivewire extends GP247AdminComponent
{
    protected ?string $permission = null;

    /** @var array<int, string> */
    public array $providers = ['facebook', 'google', 'github'];

    /** @var array<string, array<string, mixed>> provider => [enabled, client_id, client_secret, redirect] */
    public array $values = [];

    /** @var array<string, bool> provider => whether a client secret is already configured */
    public array $hasSecret = [];

    public function mount(): void
    {
        parent::mount();

        foreach ($this->providers as $provider) {
            $config = ProviderConfig::getProviderConfig($provider);

            $this->values[$provider] = [
                'enabled' => (bool) $config['enabled'],
                'client_id' => (string) $config['client_id'],
                'client_secret' => '',
                'redirect' => (string) $config['redirect'],
            ];
            $this->hasSecret[$provider] = ! empty($config['client_secret']);
        }
    }

    /**
     * Persist all provider settings (Layer-2 gated). The client secret is only
     * written when a new value was submitted, so leaving it blank keeps the
     * previously configured secret untouched.
     *
     * @return void
     * @throws \GP247\Core\AdminShell\Domain\AuthorizationException When denied.
     */
    public function save(): void
    {
        $this->authorizeAction('save');

        $globalStoreId = defined('GP247_STORE_ID_GLOBAL') ? GP247_STORE_ID_GLOBAL : 0;

        foreach ($this->providers as $provider) {
            $fields = [
                'enabled' => $this->values[$provider]['enabled'] ? '1' : '0',
                'client_id' => gp247_clean((string) $this->values[$provider]['client_id']),
                'redirect' => gp247_clean((string) $this->values[$provider]['redirect']),
            ];

            $clientSecret = trim((string) $this->values[$provider]['client_secret']);
            if ($clientSecret !== '') {
                $fields['client_secret'] = gp247_clean($clientSecret);
            }

            foreach ($fields as $field => $value) {
                AdminConfig::updateOrCreate(
                    [
                        'code' => 'LoginSocial_config',
                        'key' => $provider.'_'.$field,
                        'store_id' => $globalStoreId,
                    ],
                    [
                        'group' => 'Plugins',
                        'value' => $value,
                        'detail' => 'Plugins/LoginSocial::lang.admin.config.'.$provider.'_'.$field,
                    ]
                );
            }

            $this->values[$provider]['client_secret'] = '';
            $this->hasSecret[$provider] = $clientSecret !== '' || $this->hasSecret[$provider];
        }

        $this->notify('success', gp247_language_render('Plugins/LoginSocial::lang.admin.save_success'));
    }

    /**
     * Configured auth guards (config('Plugins/LoginSocial.guards')) for the
     * read-only status badges — same data the legacy AdminController exposed.
     *
     * @return array<int, array{name: string, enabled: bool}>
     */
    private function guardsStatus(): array
    {
        $guards = config('Plugins/LoginSocial.guards', []);

        return collect($guards)
            ->map(fn ($config, $name) => ['name' => $name, 'enabled' => (bool) ($config['enabled'] ?? false)])
            ->values()
            ->all();
    }

    public function render(): View
    {
        return view('Plugins/LoginSocial::livewire', [
            'guards' => $this->guardsStatus(),
        ])->layout('gp247-admin::layouts.admin', [
            'title' => gp247_language_render('Plugins/LoginSocial::lang.admin.title'),
        ]);
    }
}
