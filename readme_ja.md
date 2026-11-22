> 🌐 **言語:** [🇻🇳 Tiếng Việt](./readme_vi.md) · [🇬🇧 English](./readme.md) · 🇯🇵 日本語（現在）

# S-Cart 用 LoginSocial プラグイン

## 概要

LoginSocial プラグインを使うと、ユーザーは Facebook、Google、GitHub などのソーシャルメディアアカウントや、その他多くのプロバイダーで GP247 システムにログインできます。プラグインは複数の guard（admin、customer、vendor、pmo…）に対応しており、ユーザー認証を柔軟に管理できます。

## 機能

- ✅ Facebook、Google、GitHub によるログインに対応
- ✅ 複数の guard に対応：admin、customer、vendor、pmo
- ✅ 初回ログイン時にアカウントを自動作成
- ✅ ソーシャルアカウントを既存のアカウントに連携
- ✅ 管理パネルで OAuth 設定を管理
- ✅ 分かりやすく使いやすい管理画面
- ✅ 多言語対応（ベトナム語、英語）

## システム要件

- GP247 Core >= 2.0
- Laravel 12.x
- PHP >= 8.2
- Laravel Socialite ^5.0

## インストール

### ステップ 1：Laravel Socialite パッケージをインストールする

```bash
composer require laravel/socialite
```

### ステップ 2：プラグインをインストールする

1. プラグインのフォルダーを `app/GP247/Plugins/LoginSocial` にコピーします
2. Admin Panel > Extensions > Plugins を開きます
3. 「LoginSocial」を探して「Install」をクリックします
4. インストールが成功したら、「Enable」をクリックしてプラグインを有効化します

#### コマンドラインでインストールする（CLI、gp247 3.x）

これは上記 4 つの操作の代わりとなる方法です。gp247 3.x からは、管理画面を開かずに、GP247 ライブラリから **LoginSocial** をダウンロードしてコマンドラインですぐにインストールできます。**必ず先にステップ 1 を実施してください**（`composer require laravel/socialite`）。実施していない場合、パッケージ不足のためコマンドが停止します。ウェブサイトのルートフォルダーでターミナルを開き、次を実行します：

```bash
# 1) ウェブサイトごとに 1 回だけ：GP247 ライブラリに接続するための API License（無料）を登録
php artisan gp247:ext-register-license

# 2) ライブラリからプラグインをダウンロードしてインストール
php artisan gp247:ext-install --type=plugin --key=LoginSocial
```

- ステップ 1 の前に、`.env` の `APP_URL` がウェブサイトの**実際のドメイン**になっていること（`http://localhost` のままではないこと）を確認してください。ライセンスはこのドメインに紐付けられます。
- インストールが完了すると、プラグインは**有効化済み**の状態になり、キャッシュも自動で更新されます。管理画面で Install/Enable を押す必要はありません。
- コマンドは `gp247.json` に宣言された条件（core のバージョン、composer パッケージ、依存プラグイン）を自動で確認します。不足がある場合は停止し、何が足りないかを明示します（例：`laravel/socialite` が未インストール）。
- `app/GP247/Plugins/LoginSocial` フォルダーがすでにサーバー上にある場合（手動でコピーした、またはインストーラーに同梱されていた場合）、コマンドは再ダウンロードせず**その場でインストール**します。
- プラグインがすでにインストール済みの場合、コマンドは実行を拒否します。新しいバージョンにするには、`php artisan gp247:ext-update --type=plugin --key=LoginSocial` を実行してください。
- コマンドの末尾に `--json` を付けると、機械可読な形式で結果を受け取れます（スクリプト/CI 向け）。
- 以下のインストール後の設定手順はそのまま必要です。**ステップ 3**（各プロバイダーで OAuth アプリを作成）と**ステップ 4**（Admin Panel でクライアントID/シークレットを入力）は引き続き実施してください。
- 詳細：[プラグイン＆テンプレートのインストールガイド](https://github.com/gp247net/gp247-docs/blob/main/extension/install-extension.md) · [コマンドリファレンス](https://github.com/gp247net/gp247-docs/blob/main/cli/cli-extensions_ja.md)。

### ステップ 3：OAuth プロバイダーを設定する

> **注意**：LoginSocial プラグインはデータベースの設定のみを使用し、ENV 変数は使用しません。

#### Facebook ログインの設定

1. [Facebook Developers](https://developers.facebook.com/) にアクセスします
2. 新しいアプリを作成するか、既存のアプリを使用します
3. Settings > Basic で：
   - **App ID**（クライアントID）を取得します
   - **App Secret**（クライアントシークレット）を取得します
4. Products > Facebook Login > Settings で：
   - **Redirect URL** を追加します：`https://your-domain.com/auth/social/facebook/callback`
5. **Admin Panel で設定します**（ENV 変数は不要）

#### Google ログインの設定

1. [Google Cloud Console](https://console.cloud.google.com/) にアクセスします
2. 新しいプロジェクトを作成するか、既存のプロジェクトを選択します
3. Google+ API を有効にします
4. OAuth 2.0 認証情報を作成します：
   - Application type: Web application
   - Authorized redirect URIs: `https://your-domain.com/auth/social/google/callback`
5. **クライアントID** と **クライアントシークレット** を取得します
6. **Admin Panel で設定します**（ENV 変数は不要）

#### GitHub ログインの設定

1. [GitHub Developer Settings](https://github.com/settings/developers) にアクセスします
2. 「New OAuth App」をクリックします
3. 情報を入力します：
   - Application name: アプリケーション名
   - Homepage URL: `https://your-domain.com`
   - Authorization callback URL: `https://your-domain.com/auth/social/github/callback`
4. **クライアントID** と **クライアントシークレット** を取得します
5. **Admin Panel で設定します**（ENV 変数は不要）

### ステップ 4：Admin Panel で設定する

1. **Admin Panel > Plugins > LoginSocial** を開きます
2. 使用したいプロバイダーを有効/無効にします
3. 各プロバイダーのクライアントID とクライアントシークレットを入力します
4. リダイレクトURI（コールバック URL）が正しいことを確認します
5. 「Save Configuration」をクリックします

> **重要**：すべての設定はデータベースに保存されるため、ENV 変数を設定する必要はありません。

## セキュリティ設定

### Guard の設定

セキュリティ上の理由から、**デフォルトでは `customer` guard のみが有効**になっています。これにより、ソーシャルログインを通じてシステムの管理領域や機密性の高い領域へ不正にアクセスされることを防ぎます。

#### Guard の状態

管理画面には各 guard の状態が表示されます：
- 🟢 **✓ 付きの緑色のバッジ** = guard は有効で、ソーシャルログインを使用できます
- ⚫ **✗ 付きの灰色のバッジ** = guard は無効です（ソーシャルログインは許可されません）

#### 他の guard を有効にする

他の guard（admin、vendor、pmo）でソーシャルログインを有効にする必要がある場合は、config ファイルで手動設定する必要があります：

**ファイル**：`app/GP247/Plugins/LoginSocial/config.php`

```php
'guards' => [
    'admin' => [
        'model' => 'GP247\Core\Models\AdminUser',
        'redirect_after_login' => 'admin.home',
        'enabled' => 1, // 有効にするには 0 から 1 に変更
        'status_default' => 0, // 新規ユーザーの既定ステータス（0=無効、1=有効）
    ],
    'customer' => [
        'model' => 'GP247\Shop\Models\ShopCustomer',
        'redirect_after_login' => 'front.home',
        'enabled' => 1, // 既定で有効
        'status_default' => 1, // 新規 Customer はすぐに有効化
    ],
    'vendor' => [
        'model' => 'App\GP247\Plugins\MultiVendorPro\Models\VendorUser',
        'redirect_after_login' => 'vendor_admin.home',
        'enabled' => 0, // 既定で無効
        'status_default' => 0, // 新規 Vendor は承認が必要
    ],
    // ... các guards khác
],
```

#### 設定パラメーター

- **`enabled`**：`1` = この guard でソーシャルログインを許可、`0` = ソーシャルログインを無効化
- **`status_default`**：新規作成ユーザーのデフォルト状態（0 = 未有効化/承認が必要、1 = 即時有効化）
- **`model`**：この guard のユーザーモデルクラス
- **`redirect_after_login`**：ログイン成功後にリダイレクトするルート名

> ⚠️ **セキュリティ警告**：admin、vendor、その他の権限の高い guard でソーシャルログインを有効にすると、セキュリティ上のリスクが生じる可能性があります。影響を十分に理解し、適切なセキュリティ対策を講じている場合にのみ有効にしてください。

### ベストプラクティス

1. 本当に必要な場合を除き、**admin guard は無効のままにしてください**
2. 権限の高い guard では **`status_default` を 0 に設定してください**（手動承認を必須にする）
3. ログで**ソーシャルログインのアクティビティを監視してください**
4. 機密性の高い guard には**追加の認証を導入してください**
5. **guard の設定を定期的に見直してください**

## 使い方

### テンプレートにソーシャルログインボタンを追加する

#### Customer 向けログイン（デフォルト）

```blade
<a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" class="btn btn-primary">
    <i class="fab fa-facebook"></i> Facebook でログイン
</a>

<a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="btn btn-danger">
    <i class="fab fa-google"></i> Google でログイン
</a>

<a href="{{ route('social.redirect', ['provider' => 'github']) }}" class="btn btn-dark">
    <i class="fab fa-github"></i> GitHub でログイン
</a>
```

#### Admin 向けログイン

```blade
<a href="{{ route('social.redirect', ['provider' => 'google', 'guard' => 'admin']) }}" class="btn btn-primary">
    <i class="fab fa-google"></i> Google で Admin ログイン
</a>
```

#### Vendor 向けログイン

```blade
<a href="{{ route('social.redirect', ['provider' => 'facebook', 'guard' => 'vendor']) }}" class="btn btn-primary">
    <i class="fab fa-facebook"></i> Facebook で Vendor ログイン
</a>
```

### Render Component を使う（推奨）

#### Render Component のパラメーター

| パラメーター | 型 | デフォルト | 説明 |
|---------|------|----------|-------|
| `guard` | string | `'customer'` | 認証 guard（admin、customer、vendor、pmo） |
| `providers` | array | `['facebook', 'google', 'github']` | 表示するプロバイダーの一覧 |
| `buttonClass` | string | `'btn btn-block mb-2'` | ボタンの CSS クラス |
| `title` | boolean | `false` | 「ソーシャルログイン」というタイトルを表示 |
| `description` | boolean | `false` | 「ソーシャルメディアアカウントでかんたんログイン」という説明を表示 |
| `forceShow` | boolean | `false` | ユーザーがログイン済みでも表示 |

**注意**：デフォルトでは、ソーシャルログインボタンは指定した guard でユーザーが**未ログイン**の場合にのみ表示されます。常に表示するには `forceShow=true` を使用してください。

#### 方法 1：render.blade.php を @include で使う

```blade
<!-- 最もシンプルな方法 -->
@include('Plugins/LoginSocial::render')

<!-- guard をカスタマイズ -->
@include('Plugins/LoginSocial::render', ['guard' => 'customer'])

<!-- providers をカスタマイズ -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'providers' => ['facebook', 'google', 'github']
])

<!-- ボタンの class をカスタマイズ -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'providers' => ['facebook', 'google'],
    'buttonClass' => 'btn btn-outline-primary btn-block'
])

<!-- タイトルと説明を表示 -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'title' => true,
    'description' => true
])

<!-- 常に表示（ログイン済みでも） -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'forceShow' => true
])
```

#### 方法 2：ログインフォーム内で使う

```blade
<!-- 通常のログインフォーム -->
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="パスワード" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">ログイン</button>
</form>

<!-- ソーシャルログイン部分 -->
@if(gp247_extension_check_active('Plugins', 'LoginSocial'))
    @include('Plugins/LoginSocial::render', [
        'guard' => 'customer',
        'title' => true,
        'description' => true
    ])
@endif
```

#### 方法 3：Admin ログインで使う

```blade
<!-- Admin login form -->
<form method="POST" action="{{ route('admin.login') }}">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Admin Email" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="パスワード" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Admin Login</button>
</form>

<!-- admin 用のソーシャルログイン -->
@if(gp247_extension_check_active('Plugins', 'LoginSocial'))
    @include('Plugins/LoginSocial::render', [
        'guard' => 'admin',
        'providers' => ['google', 'github'],
        'title' => true
    ])
@endif
```

#### 方法 4：Vendor ログインで使う

```blade
<!-- Vendor login form -->
<form method="POST" action="{{ route('vendor.login') }}">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Vendor Email" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="パスワード" required>
    </div>
    <button type="submit" class="btn btn-success btn-block">Vendor Login</button>
</form>

<!-- vendor 用のソーシャルログイン -->
@if(gp247_extension_check_active('Plugins', 'LoginSocial'))
    @include('Plugins/LoginSocial::render', [
        'guard' => 'vendor',
        'providers' => ['facebook', 'google'],
        'title' => true,
        'description' => true
    ])
@endif
```

### ログインフォームでの完全な例（手動）

```blade
<div class="social-login-buttons">
    <h4>または次の方法でログイン</h4>
    
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

## 利用可能な guard

プラグインは次の guard に対応しています：

- **admin**：管理者向けログイン
- **customer**：顧客向けログイン（デフォルト）
- **vendor**：ベンダー向けログイン（MultiVendorPro プラグインが必要）
- **pmo**：PMO ユーザー向けログイン

## 処理の流れ

1. ユーザーが「Facebook/Google/GitHub でログイン」ボタンをクリックします
2. システムが認証のためにプロバイダーへリダイレクトします
3. 認証に成功すると、プロバイダーがコールバック URL へリダイレクトします
4. プラグインは次を確認します：
   - ソーシャルアカウントがすでにある場合 → そのままログイン
   - メールアドレスがすでに存在する場合 → ソーシャルアカウントを既存のアカウントに連携
   - 新規ユーザーの場合 → 新しいアカウントを作成してログイン
5. guard に応じた適切なページへユーザーをリダイレクトします

## よくあるエラーの対処

### エラー："This login provider is not enabled"

**原因**：プロバイダーが有効になっていない、または設定されていません

**解決策**：
1. Admin Panel > Plugins > LoginSocial を開きます
2. プロバイダーが有効になっているか確認します
3. クライアントID とクライアントシークレットが正しく入力されているか確認します

### エラー："Invalid authentication guard"

**原因**：guard が設定に存在しません

**解決策**：
- プラグインの `config.php` ファイルで guard を確認します
- guard と対応するモデルが正しく設定されていることを確認します

### エラー："Client error: 400"

**原因**：OAuth の設定が正しくありません

**解決策**：
1. クライアントID とクライアントシークレットを確認します
2. リダイレクトURI が OAuth アプリの設定と一致しているか確認します
3. OAuth アプリが approved/published になっていることを確認します

## セキュリティ

- ✅ 標準の OAuth 2.0 プロトコルを使用
- ✅ クライアントシークレットをデータベースに安全に保存
- ✅ ログイン前にアカウントの状態を確認
- ✅ 新規アカウントにはランダムなパスワードを設定
- ✅ セッションベースの認証

## サポート

- Email: support@gp247.net
- Website: https://gp247.net

## License

MIT License

## Changelog

### Version 2.0
- 管理用の設定画面を TailAdmin/Livewire で再構築しました（GP247 Core 2.0 が必要）。プロバイダー/guard の設定方法や OAuth 設定の保存方法に変更はありません
- クライアントシークレットの項目は、これまでどおりマスク表示と「空欄のままにすると現在の値を維持」の動作を保持しています
- どのルートからも参照されていなかった不要なスキャフォールドファイル（`Controllers/FrontController.php`、`Views/Front.blade.php`、`Views/script.blade.php`）を削除しました

### Version 1.0
- 初回リリース
- Facebook、Google、GitHub に対応
- 複数の guard に対応
- 管理パネルでの設定
- 多言語対応
