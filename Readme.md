コンテナを起動後、appコンテナ内で:
# main, dev-dockerブランチはコンテナの設定が違うので使えない。
featureで進める

# envのdb userはroot以外にする
予約ユーザーなのでよくない

mysql/mysql-server:8.0 が正解。
mysql:8.0 や mysql:8.4（Docker Hub公式）とは別のMySQL公式イメージで、entrypointが違いchownの問題が起きません。

合わせて my.cnf も必要な基本設定を追加し、chmod 644 も必要です。

# Laravelインストール
composer create-project laravel/laravel . "^12.0"

もしくは
## 1. Laravel インストーラーをグローバルインストール
composer global require laravel/installer

## 2. プロジェクト作成
laravel new .
laravel new はLaravel 11以降対話形式になっていて、インストール時にスターターキットを選べます：


 ┌ Would you like to install a starter kit? ──────────────────┐
 │ Laravel Breeze                                              │
 │ Laravel Jetstream                                           │
 │ None                                                        │
 └────────────────────────────────────────────────────────────┘

 ┌ Which Breeze stack would you like to use? ─────────────────┐
 │ Vue with Inertia                                            │
 │ React with Inertia                                          │
 │ ...                                                         │
 └────────────────────────────────────────────────────────────┘
Breezeまで一気にセットアップできるので、こちらのほうが便利です。

ただし、src/ が空でないと同じく失敗します。

# Breeze + Vue (Inertia) インストール
composer require laravel/breeze --dev
php artisan breeze:install vue

# DBの設定
appコンテナの.envのDBを修正
php artisan migrate でデータが入るか確認
mysqlへの接続はdbコンテナからのみ。appにmysqlはない

個別に起動すると Docker のネットワーク設定がうまく機能しないことがあります。docker compose up -d でまとめて起動するのが確実です。
## 接続テスト
docker compose exec db mysql -u laravel -ppass order_system
## app->dbテスト
docker compose exec app php artisan migrate


# フロントエンドビルド
npm install (vite6の場合)
npm install @vitejs/plugin-vue@latest --save-dev (vite7を使う場合)
npm run build


# php artisan serve コマンド	用途	Docker環境では
php artisan serve	PHPの開発用ビルトインサーバー起動	app（PHP-FPM）+ server（nginx）が代わりに動いてる
npm run dev	Viteの開発サーバー起動	コンテナ内で別途対応が必要（後述）
ただし npm run dev は少し注意が必要です。

Viteの開発サーバー（HMR = ホットリロード）を使いたい場合はコンテナ内で起動する必要があります：


docker compose exec app npm run dev
本番ビルド（HMR不要）なら：


docker compose exec app npm run build
開発中にVueのホットリロードを使いたいか、ビルドだけでいいかによって変わります。最初は npm run build で十分です。






# laravel + breeze (vue)

Breeze以外でVueを使う方法
方法	説明	向いているケース
Laravel Breeze + Inertia + Vue	認証込みのスキャフォールド	手軽に始めたい
Laravel Jetstream + Inertia + Vue	チーム管理・2FA等の高機能版	本格的なSaaS
Laravel API + Vue SPA（分離）	LaravelはAPIのみ、Vueは別コンテナ	フロント/バック完全分離
Blade + Vite + Vue（手動）	Breezeなしで直接インストール	認証不要・小規模
Breezeは実際には「Inertia.js + Vue 3の認証スキャフォールド」なので、Inertia.jsを使いたくないか認証不要なら手動セットアップが選択肢になります。

# 自動で作られるもの
resources/js/Pages/Auth/Login.vue      ← ログイン画面
resources/js/Pages/Auth/Register.vue   ← 登録画面
resources/js/Pages/Auth/Dashboard.vue  ← ダッシュボード
routes/auth.php                        ← 認証ルート
app/Http/Controllers/Auth/...          ← 認証コントローラー
vite.config.js                         ← Vite設定
自分でゼロから書かなくていい、というのが「スキャフォールド」です。


Breeze (Inertia)	手動 (Blade + Vue)
認証画面	自動生成	自分で作る
ルーティング	Inertia経由	Vue Router or Blade
データ受け渡し	Inertia::render()	API (fetch/axios)
学習コスト	Inertia.jsを覚える必要あり	素直なVue
向いてる用途	認証が必要なアプリ	部分的にVueを使いたい
最初にVue + Laravelを学ぶなら Breeze のほうが構成の全体像が掴みやすいです。ただしInertia.jsというレイヤーが増える点は念頭に置いてください。