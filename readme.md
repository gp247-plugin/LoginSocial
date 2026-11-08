> 🌐 **Language:** [🇻🇳 Tiếng Việt](./readme_vi.md) · 🇬🇧 English (current) · [🇯🇵 日本語](./readme_ja.md)

# LoginSocial Plugin for GP247

## Overview

The LoginSocial plugin allows users to log into the GP247 system using social media accounts such as Facebook, Google, GitHub, and other providers. The plugin supports multiple guards (admin, customer, vendor, pmo...) for flexible user authentication management.

## Features

- ✅ Login support via Facebook, Google, GitHub
- ✅ Multiple guards support: admin, customer, vendor, pmo
- ✅ Automatic account creation on first login
- ✅ Link social accounts with existing accounts
- ✅ OAuth configuration management in Admin panel
- ✅ User-friendly and easy-to-use admin interface
- ✅ Multilingual support (Vietnamese, English)

## System Requirements

- GP247 Core >= 2.0
- Laravel 12.x
- PHP >= 8.2
- Laravel Socialite ^5.0

## Installation

### Step 1: Install Laravel Socialite package

```bash
composer require laravel/socialite
```

### Step 2: Install the plugin

1. Copy the plugin folder to `app/GP247/Plugins/LoginSocial`
2. Access Admin Panel > Extensions > Plugins
3. Find "LoginSocial" and click "Install"
4. After successful installation, click "Enable" to activate the plugin

#### Install from the command line (CLI, gp247 3.x)

This is an alternative to the 4 actions above. Since gp247 3.x you can download **LoginSocial** from the GP247 library and install it straight from the command line, without opening the admin. **Step 1 must be done first** (`composer require laravel/socialite`), otherwise the command stops because the package is missing. Open a terminal in the website's root folder and run:

```bash
# 1) Once per website: register the (free) API License that connects the site to the GP247 library
php artisan gp247:ext-register-license

# 2) Download the plugin from the library and install it
php artisan gp247:ext-install --type=plugin --key=LoginSocial
```

- Before step 1, make sure `APP_URL` in `.env` is the website's **real domain** (not `http://localhost`) — the license is bound to that domain.
- Once installed, the plugin is **enabled** and caches are refreshed automatically; you no longer need to click Install/Enable in the admin.
- The command checks the requirements declared in `gp247.json` (core version, composer packages, required plugins) and stops with a clear message if something is missing (e.g. `laravel/socialite` is not installed).
- If the folder `app/GP247/Plugins/LoginSocial` is already on the server (copied manually or shipped with the installer), the command **installs it in place** instead of downloading it again.
- The command refuses a plugin that is already installed. To move to a newer version, run `php artisan gp247:ext-update --type=plugin --key=LoginSocial`.
- Append `--json` to get machine-readable output (for scripts/CI).
- The post-install configuration steps below still apply: you still need **Step 3** (create the OAuth app at each provider) and **Step 4** (enter Client ID/Secret in the Admin Panel).
- More: [Installing Plugins & Templates](https://github.com/gp247net/gp247-docs/blob/main/extension/install-extension.md) · [Command reference](https://github.com/gp247net/gp247-docs/blob/main/cli/cli-extensions.md).

### Step 3: Configure OAuth Providers


#### Configure Facebook Login

1. Visit [Facebook Developers](https://developers.facebook.com/)
2. Create a new app or use an existing one
3. In Settings > Basic:
   - Get **App ID** (Client ID)
   - Get **App Secret** (Client Secret)
4. In Products > Facebook Login > Settings:
   - Add **Redirect URL**: `https://your-domain.com/auth/social/facebook/callback`
5. **Configure in Admin Panel** (no ENV variables needed)

#### Configure Google Login

1. Visit [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials:
   - Application type: Web application
   - Authorized redirect URIs: `https://your-domain.com/auth/social/google/callback`
5. Get **Client ID** and **Client Secret**
6. **Configure in Admin Panel** (no ENV variables needed)

#### Configure GitHub Login

1. Visit [GitHub Developer Settings](https://github.com/settings/developers)
2. Click "New OAuth App"
3. Fill in the information:
   - Application name: Your app name
   - Homepage URL: `https://your-domain.com`
   - Authorization callback URL: `https://your-domain.com/auth/social/github/callback`
4. Get **Client ID** and **Client Secret**
5. **Configure in Admin Panel** (no ENV variables needed)

### Step 4: Configure in Admin Panel

1. Access **Admin Panel > Plugins > LoginSocial**
2. Enable/disable desired providers
3. Enter Client ID, Client Secret for each provider
4. Verify the Redirect URL (callback URL) is correct
5. Click "Save Configuration"


## Security Configuration

### Guards Configuration

For security reasons, **only the `customer` guard is enabled by default**. This prevents unauthorized social login access to administrative or sensitive areas of your system.

#### Enabled Guards

The admin interface shows the status of each guard:
- 🟢 **Green badge with ✓** = Guard is enabled and available for social login
- ⚫ **Gray badge with ✗** = Guard is disabled (social login not allowed)

#### Enabling Other Guards

If you need to enable social login for other guards (admin, vendor, pmo), you must manually configure them in the config file:

**File**: `app/GP247/Plugins/LoginSocial/config.php`

```php
'guards' => [
    'admin' => [
        'model' => 'GP247\Core\Models\AdminUser',
        'redirect_after_login' => 'admin.home',
        'enabled' => 1, // Change from 0 to 1 to enable
        'status_default' => 0, // Default status for new users (0=inactive, 1=active)
    ],
    'customer' => [
        'model' => 'GP247\Shop\Models\ShopCustomer',
        'redirect_after_login' => 'front.home',
        'enabled' => 1, // Already enabled by default
        'status_default' => 1, // New customers are active by default
    ],
    'vendor' => [
        'model' => 'App\GP247\Plugins\MultiVendorPro\Models\VendorUser',
        'redirect_after_login' => 'vendor_admin.home',
        'enabled' => 0, // Disabled by default
        'status_default' => 0, // New vendors require approval
    ],
    // ... other guards
],
```

#### Configuration Parameters

- **`enabled`**: `1` = Allow social login for this guard, `0` = Disable social login
- **`status_default`**: Default status for newly created users (0 = inactive/requires approval, 1 = active immediately)
- **`model`**: User model class for this guard
- **`redirect_after_login`**: Route name to redirect after successful login

> ⚠️ **Security Warning**: Enabling social login for admin, vendor, or other privileged guards poses security risks. Only enable if you understand the implications and have proper security measures in place.

### Best Practices

1. **Keep admin guard disabled** unless absolutely necessary
2. **Set `status_default` to 0** for privileged guards (require manual approval)
3. **Monitor social login activity** in your logs
4. **Implement additional verification** for sensitive guards
5. **Review guard configurations** regularly

## Usage

### Add Social Login Buttons to Template

#### Login for Customer (default)

```blade
<a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" class="btn btn-primary">
    <i class="fab fa-facebook"></i> Login with Facebook
</a>

<a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="btn btn-danger">
    <i class="fab fa-google"></i> Login with Google
</a>

<a href="{{ route('social.redirect', ['provider' => 'github']) }}" class="btn btn-dark">
    <i class="fab fa-github"></i> Login with GitHub
</a>
```

#### Login for Admin

```blade
<a href="{{ route('social.redirect', ['provider' => 'google', 'guard' => 'admin']) }}" class="btn btn-primary">
    <i class="fab fa-google"></i> Admin Login with Google
</a>
```

#### Login for Vendor

```blade
<a href="{{ route('social.redirect', ['provider' => 'facebook', 'guard' => 'vendor']) }}" class="btn btn-primary">
    <i class="fab fa-facebook"></i> Vendor Login with Facebook
</a>
```

### Using Render Component (Recommended)

#### Render Component Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `guard` | string | `'customer'` | Authentication guard (admin, customer, vendor, pmo) |
| `providers` | array | `['facebook', 'google', 'github']` | List of providers to display |
| `buttonClass` | string | `'btn btn-block mb-2'` | CSS class for buttons |
| `title` | boolean | `false` | Show title "Social Login" |
| `description` | boolean | `false` | Show description "Quick login with your social media accounts" |
| `forceShow` | boolean | `false` | Show even if user is already logged in |

**Note**: By default, social login buttons only show when user is **NOT logged in** for the specified guard. Use `forceShow=true` to always show the buttons.

#### Method 1: Using @include with render.blade.php

```blade
<!-- Simplest way -->
@include('Plugins/LoginSocial::render')

<!-- With custom guard -->
@include('Plugins/LoginSocial::render', ['guard' => 'customer'])

<!-- With custom providers -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'providers' => ['facebook', 'google', 'github']
])

<!-- With custom button class -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'providers' => ['facebook', 'google'],
    'buttonClass' => 'btn btn-outline-primary btn-block'
])

<!-- With title and description -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'title' => true,
    'description' => true
])

<!-- Always show (even if logged in) -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'forceShow' => true
])
```

#### Method 2: Using in Login Form

```blade
<!-- Regular login form -->
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Login</button>
</form>

<!-- Social login section -->
@if(gp247_extension_check_active('Plugins', 'LoginSocial'))
    @include('Plugins/LoginSocial::render', [
        'guard' => 'customer',
        'title' => true,
        'description' => true
    ])
@endif
```

#### Method 3: Using for Admin Login

```blade
<!-- Admin login form -->
<form method="POST" action="{{ route('admin.login') }}">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Admin Email" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Admin Login</button>
</form>

<!-- Social login for admin -->
@if(gp247_extension_check_active('Plugins', 'LoginSocial'))
    @include('Plugins/LoginSocial::render', [
        'guard' => 'admin',
        'providers' => ['google', 'github'],
        'title' => true
    ])
@endif
```

#### Method 4: Using for Vendor Login

```blade
<!-- Vendor login form -->
<form method="POST" action="{{ route('vendor.login') }}">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Vendor Email" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
    </div>
    <button type="submit" class="btn btn-success btn-block">Vendor Login</button>
</form>

<!-- Social login for vendor -->
@if(gp247_extension_check_active('Plugins', 'LoginSocial'))
    @include('Plugins/LoginSocial::render', [
        'guard' => 'vendor',
        'providers' => ['facebook', 'google'],
        'title' => true,
        'description' => true
    ])
@endif
```

### Complete Example in Login Form (Manual)

```blade
<div class="social-login-buttons">
    <h4>Or login with</h4>
    
    <div class="btn-group">
        @if(gp247_config('facebook_enabled', GP247_STORE_ID_GLOBAL, '0'))
        <a href="{{ route('social.redirect', ['provider' => 'facebook', 'guard' => 'customer']) }}" 
           class="btn btn-facebook">
            <i class="fab fa-facebook-f"></i> Facebook
        </a>
        @endif
        
        @if(gp247_config('google_enabled', GP247_STORE_ID_GLOBAL, '0'))
        <a href="{{ route('social.redirect', ['provider' => 'google', 'guard' => 'customer']) }}" 
           class="btn btn-google">
            <i class="fab fa-google"></i> Google
        </a>
        @endif
        
        @if(gp247_config('github_enabled', GP247_STORE_ID_GLOBAL, '0'))
        <a href="{{ route('social.redirect', ['provider' => 'github', 'guard' => 'customer']) }}" 
           class="btn btn-github">
            <i class="fab fa-github"></i> GitHub
        </a>
        @endif
    </div>
</div>
```

## Available Guards

The plugin supports the following guards:

- **admin**: Login for administrators
- **customer**: Login for customers (default)
- **vendor**: Login for vendors (requires MultiVendorPro plugin)
- **pmo**: Login for PMO users

## Workflow

1. User clicks "Login with Facebook/Google/GitHub" button
2. System redirects to provider for authentication
3. After successful authentication, provider redirects to callback URL
4. Plugin checks:
   - If social account exists → Login immediately
   - If email exists → Link social account with existing account
   - If new user → Create new account and login
5. Redirect user to appropriate page based on guard

## Common Error Handling

### Error: "This login provider is not enabled"

**Cause**: Provider not enabled or not configured

**Solution**:
1. Go to Admin Panel > Plugins > LoginSocial
2. Check if provider is enabled
3. Check if Client ID and Client Secret are entered correctly

### Error: "Invalid authentication guard"

**Cause**: Guard does not exist in configuration

**Solution**:
- Check guard in plugin's `config.php` file
- Ensure guard and corresponding model are configured correctly

### Error: "Client error: 400"

**Cause**: OAuth configuration incorrect

**Solution**:
1. Check Client ID and Client Secret
2. Check Redirect URL matches OAuth app configuration
3. Ensure OAuth app is approved/published

## Security

- ✅ Uses standard OAuth 2.0 protocol
- ✅ Client Secret stored securely in database
- ✅ Account status checked before login
- ✅ Random password for new accounts
- ✅ Session-based authentication

## Support

- Email: support@gp247.net
- Website: https://gp247.net

## License

MIT License

## Changelog

### Version 2.0
- Admin configuration screen rebuilt on TailAdmin/Livewire (requires GP247 Core 2.0); no change to how providers/guards are configured or to the stored OAuth settings
- Client Secret fields keep the same masked, "leave blank to keep the current value" behavior
- Removed unused scaffold leftovers (`Controllers/FrontController.php`, `Views/Front.blade.php`, `Views/script.blade.php`) that were never wired to any route

### Version 1.0
- Initial release
- Support for Facebook, Google, GitHub
- Multiple guards support
- Admin panel configuration
- Multilingual support
