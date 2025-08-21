
## 開発環境
```
・php 7.4 ~ 8.0
・Laravel 8.x.x
・MySQl latest
```

## 環境構築
- コード準備
```
git clone ******(それぞれの環境に応じて)
```
  
- shell
```
$ cp .env.example .env
$ composer install
$ php artisan key:generate
$ composer dump-autoload
$ php artisan db:seed
```
※データベースがない場合
```
$ php artisan migrate
```

## 環境変数
以下が記載例です。
自身の環境に応じて、記載を変更してください。

```
・APP_URL=http://localhost/tengoku-sysbox/public
・DB_CONNECTION=mysql
・DB_HOST=127.0.0.1
・DB_PORT=3306
・DB_DATABASE=****(接続したいDB名)
・DB_USERNAME=****(接続したいユーザー名)
・DB_PASSWORD=****(接続したいユーザーのPS)
・MAIL_USERNAME=*****(送信メールアドレス)
・MAIL_PASSWORD=*****(送信メールアドレスPS)
```

## フォルダ構成

```
.
├── app/ アプリケーション処理
│   ├── Console/
│   │   └── Commands/ コマンド処理
│   ├── Exceptions
│   ├── Http/　アプリケーション中心処理
│   │   └── Controllers
│   ├── Mail　メール処理
│   ├── Models　アプリケーションとデータベースの接続処理
├── bootstrap/　初期起動時の処理
├── config / 各種設定及び共通関数処理
├── database/　データベース処理
│   ├── migrations　テーブル作成及び変更　
│   └── seeds　ダミーデータの作成及び変更
├── public　
├── resources/　フロント処理
│   └── views/
│       ├── admin　管理画面　
│       ├── auth　認証画面
│       ├── components/master　マスタ画面(コンポーネント)
│       ├── layouts　画面共通部品　
│       ├── mail　メール画面
│       ├── mypages　マイページ画面
│       ├── sitePages　サイトページ画面
├── routes/　ルート定義
│   ├── api.php　apiルート
│   └── web.php　URLルート
├── storage　画像等の保存場所
```
