# slack-notificator-cli
CLIとしてSlackに投稿するスクリプト

## 引数
| 引数 | 意味 | 必須/任意 |
| :--- | :--- | :--- |
| message | 投稿文 | messagepath といずれか必須 |
| messagepath | 投稿文を記したテキストファイルのパス | message といずれか必須 |
| username | 投稿者 | 任意 |
| channel | 投稿先チャンネル名 | 任意 |

## 設定
`config/.env.sample` をコピーして `config/.env` を作成してください。

| Key | Value |
| :--- | :--- |
| DEFAULT_WEBHOOK_URL | 着信Webhook URL |
| DEFAULT_USERNAME | デフォルト投稿者 |
| DEFAULT_CHANNEL | デフォルト投稿先チャンネル名 |

## 実行例
`bin/slack-notificator-cli --message='ほげほげ'`

`bin/slack-notificator-cli --messagepath='/tmp/message.txt' --username='山田太郎'`
