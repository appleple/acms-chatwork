# ChatWork for a-blog cms

a-blog cms の 拡張アプリ「ChatWork for a-blog cms」を使うとフォームからデータが送信された段階でそのフォームのデータを加工し、ChatWork に通知することができます。例えばフォームから「資料請求」などがあれば「資料請求がありました。」などとChatWorkの好きなチャンネルに通知できます。この拡張アプリはVer.2.8より利用可能です。

## ダウンロード
[ChatWork for a-blog cms](https://github.com/appleple/acms-chatwork/raw/master/build/chatwork.zip)

利用するためにはダウンロード後、/extension/pluginsに設置してください。

## 使い方
下の図のように、ChatWorkに通知したいFormIDを指定し、メッセージの送信先チャネルを「Channel」に送信元の名前を「From」に設定します。またメッセージにはFormモジュールの変数と、グローバル変数を使用することができます。
<img src="./images/screenshot.png" />

## カスタマイズ手順
以下の3つのステップで a-blog cms と ChatWork を連携します。

1. ChatWorkへの登録
2. Webhook URL の取得
3. a-blog cmsの拡張アプリ ChatWorkに Webhook URL を登録

### 1. ChatWorkへの登録
ChatWorkのアカウントをお持ちでない方は下記のURLにてアカウントを作成しましょう。ある程度の機能までは無料で使うことができます。 https://www.chatwork.com/

### 2. Webhook URL の取得
下記のURLにてチャネルを指定して Webhook URL を取得します。ここで登録したチャネル以外のチャネルにもメッセージを飛ばすことはできますので好きなチャネルを指定して作成しましょう。 <br/>
https://www.chatwork.com/services/new/incoming-webhook

### 3. a-blog cmsの拡張アプリ ChatWork に Webhook URL を登録

管理ページ > 拡張アプリより「拡張アプリ管理」のページに移動します。そのページより下の図のように ChatWork をインストールします。

<img src="./images/install.png" />

インストール完了後は、管理ページ > フォーム > 連携したいフォームID より ChatWork の管理ページに移動します。その後、「Webhook URL」という項目がありますので、その項目に先ほど覚えておいた Webhook URL を入力します。 これでa-blog cmsと ChatWork を連携させる準備は整いました。

<img src="./images/setting.png" />

## 注意
config.server.phpでHOOKを有効にしておく必要があります。

```php
define('HOOK_ENABLE', 1);
```

