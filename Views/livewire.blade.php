{{--
    TailAdmin port of the legacy AdminLTE screen: available-guards status
    block, then one card per provider (enable toggle, client id, masked
    client secret, redirect URL), saved via a single submit button.

    Variables: $guards (array<int, array{name:string, enabled:bool}>).
    Component state: $providers, $values (provider => fields), $hasSecret.
--}}
<div class="space-y-5">
    <x-gp247::card :title="gp247_language_render('Plugins/LoginSocial::lang.admin.available_guards')">
        <div class="flex flex-wrap gap-2">
            @foreach ($guards as $guard)
                <x-gp247::badge :color="$guard['enabled'] ? 'green' : 'gray'">
                    {{ $guard['name'] }}
                    <i class="fas fa-{{ $guard['enabled'] ? 'check-circle' : 'times-circle' }} ml-1"></i>
                </x-gp247::badge>
            @endforeach
        </div>
        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
            {{ gp247_language_render('Plugins/LoginSocial::lang.admin.guards_help') }}
        </p>
    </x-gp247::card>

    <form wire:submit="save" class="space-y-5">
        @foreach ($providers as $provider)
            <x-gp247::card :title="ucfirst($provider).' Configuration'">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex h-6 w-11 cursor-pointer items-center">
                            <input type="checkbox" wire:model="values.{{ $provider }}.enabled" class="peer sr-only">
                            <span class="absolute inset-0 rounded-full bg-gray-200 transition-colors peer-checked:bg-blue-600 peer-focus:ring-2 peer-focus:ring-blue-500 dark:bg-gray-600"></span>
                            <span class="absolute left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                        </label>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ gp247_language_render('Plugins/LoginSocial::lang.admin.enable_provider', ['provider' => ucfirst($provider)]) }}
                        </span>
                    </div>

                    <x-gp247::input wire:model="values.{{ $provider }}.client_id"
                        :label="gp247_language_render('Plugins/LoginSocial::lang.admin.client_id')"
                        placeholder="Enter {{ ucfirst($provider) }} Client ID" />

                    <x-gp247::input type="password" wire:model="values.{{ $provider }}.client_secret"
                        :label="gp247_language_render('Plugins/LoginSocial::lang.admin.client_secret')"
                        :placeholder="$hasSecret[$provider] ? gp247_language_render('Plugins/LoginSocial::lang.admin.secret_placeholder') : gp247_language_render('Plugins/LoginSocial::lang.admin.secret_new_placeholder')"
                        :help="$hasSecret[$provider] ? gp247_language_render('Plugins/LoginSocial::lang.admin.secret_hidden') : null" />

                    <x-gp247::input wire:model="values.{{ $provider }}.redirect"
                        :label="gp247_language_render('Plugins/LoginSocial::lang.admin.redirect_url')"
                        :placeholder="url('auth/social/'.$provider.'/callback')"
                        :help="gp247_language_render('Plugins/LoginSocial::lang.admin.redirect_help')" />
                </div>
            </x-gp247::card>
        @endforeach

        <x-gp247::card>
            <h5 class="mb-2 text-sm font-semibold text-gray-800 dark:text-gray-100">
                <i class="fas fa-code"></i> {{ gp247_language_render('Plugins/LoginSocial::lang.admin.usage_title') }}
            </h5>
            <p class="mb-3 text-sm text-gray-600 dark:text-gray-300">
                {{ gp247_language_render('Plugins/LoginSocial::lang.admin.usage_description') }}
            </p>
            <pre class="overflow-x-auto rounded-lg bg-gray-900 p-3 text-xs text-white"><code>&lt;a href="@{{ route('social.redirect', ['provider' => 'facebook', 'guard' => 'customer']) }}"&gt;
    &lt;i class="fab fa-facebook"&gt;&lt;/i&gt; Login with Facebook
&lt;/a&gt;</code></pre>
        </x-gp247::card>

        <div class="flex justify-end">
            <x-gp247::button type="submit">
                <i class="fas fa-save"></i> {{ gp247_language_render('Plugins/LoginSocial::lang.admin.save') }}
            </x-gp247::button>
        </div>
    </form>
</div>
