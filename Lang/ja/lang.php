<?php
return [
    'title' => 'ソーシャルログイン',

    'admin' => [
        'title' => 'ソーシャルログイン',
        'description' => 'アプリケーションのソーシャルメディアログインプロバイダーを設定します。ユーザーはソーシャルメディアアカウントでログインできます。',
        'available_guards' => '利用可能なガード',
        'guards_help' => 'これらのガードをソーシャルログインに使用できます。ログインURLでガードを指定してください。緑のバッジは有効、灰色のバッジは無効です（config.phpで設定）。',
        'guard_enabled' => '有効',
        'guard_disabled' => '無効',
        'enable_provider' => ':providerログインを有効化',
        'client_id' => 'クライアントID / アプリID',
        'client_secret' => 'クライアントシークレット / アプリシークレット',
        'secret_hidden' => 'セキュリティのためシークレットキーは非表示です。更新するには新しい値を入力してください。',
        'secret_placeholder' => '********（更新するには新しい値を入力）',
        'secret_new_placeholder' => '新しいクライアントシークレットを入力',
        'redirect_url' => 'リダイレクトURL / コールバックURL',
        'redirect_help' => 'このURLをOAuthアプリの設定にコピーしてください',
        'usage_title' => '使用例',
        'usage_description' => 'ソーシャルログインを有効にするには、ログインページに次のコードを追加してください：',
        'save' => '設定を保存',
        'save_success' => '設定を保存しました。',
        'config' => [
            'facebook_enabled' => 'Facebookログインを有効化',
            'facebook_client_id' => 'FacebookアプリID',
            'facebook_client_secret' => 'Facebookアプリシークレット',
            'facebook_redirect' => 'FacebookリダイレクトURL',
            'google_enabled' => 'Googleログインを有効化',
            'google_client_id' => 'GoogleクライアントID',
            'google_client_secret' => 'Googleクライアントシークレット',
            'google_redirect' => 'GoogleリダイレクトURL',
            'github_enabled' => 'GitHubログインを有効化',
            'github_client_id' => 'GitHubクライアントID',
            'github_client_secret' => 'GitHubクライアントシークレット',
            'github_redirect' => 'GitHubリダイレクトURL',
        ],
    ],

    // Frontend messages
    'provider_not_enabled' => 'このログインプロバイダーは有効になっていないか、正しく設定されていません。',
    'guard_not_enabled' => 'この認証方法は利用できないか、正しく設定されていません。',
    'invalid_guard' => '指定された認証ガードが無効です。',
    'account_inactive' => 'アカウントが無効になっています。管理者にお問い合わせください。',
    'create_user_failed' => 'ユーザーアカウントの作成に失敗しました。もう一度お試しください。',
    'login_success' => 'ログインしました。',
    'login_failed' => 'ログインに失敗しました。もう一度お試しください。',

    // Social login buttons
    'login_with_facebook' => 'Facebookでログイン',
    'login_with_google' => 'Googleでログイン',
    'login_with_github' => 'GitHubでログイン',
    'login_with' => 'ログイン方法',
    'or_login_with' => 'または次でログイン',
    'continue_with' => '続行方法',

    // Additional messages
    'social_login_title' => 'ソーシャルログイン',
    'social_login_description' => 'ソーシャルメディアアカウントで簡単ログイン',
    'connecting_to' => '接続中',
    'please_wait' => 'お待ちください...',
    'redirecting' => 'リダイレクト中...',

    // Validation messages
    'client_id_required' => 'プロバイダーを有効にする場合はクライアントIDが必要です',
    'client_secret_required' => 'プロバイダーを有効にする場合はクライアントシークレットが必要です',
    'redirect_url_required' => 'プロバイダーを有効にする場合はリダイレクトURLが必要です',
    'invalid_client_id' => 'クライアントIDが無効です',
    'invalid_client_secret' => 'クライアントシークレットが無効です',
    'invalid_redirect_url' => 'リダイレクトURLが無効です',

    // Success messages
    'account_linked' => 'アカウントを連携しました',
    'account_created' => '新しいアカウントを作成しました',
    'welcome_message' => 'ようこそ！',
];
