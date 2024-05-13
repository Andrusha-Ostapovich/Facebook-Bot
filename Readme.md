## Facebook bot
Клас `Facebook` надає інтерфейс для взаємодії з Facebook API, включаючи роботу з вебхуками та надсилання повідомлень.

### Конструктор

Створення нового екземпляру класу `Facebook`.

#### Параметри

- `token` (string): Токен доступу до Facebook API.

```php
use Ostapovich\Facebook;

$facebook = new Facebook('YOUR_FACEBOOK_ACCESS_TOKEN');
```

### Методи

#### setWebhook

Встановлює вебхук з переданими даними та повертає код виклику (hub challenge).

```php
$data = ['hub_challenge' => 'CHALLENGE_STRING'];
$result = $facebook->setWebhook($data);
```

#### setSecurityWebhook

Встановлює захищений вебхук з переданими даними та перевірює токен на валідність. Повертає код виклику (hub challenge), якщо верифікація успішна.

```php
$data = [
    'hub_mode' => 'subscribe',
    'hub_verify_token' => 'VERIFY_TOKEN',
    'hub_challenge' => 'CHALLENGE_STRING'
];
$result = $facebook->setSecurityWebhook($data, 'VERIFY_TOKEN');
```

#### getMessage

Отримує текст повідомлення з переданого масиву даних.

```php
$data = [
    'entry' => [
        [
            'messaging' => [
                [
                    'message' => [
                        'text' => 'Hello, world!'
                    ]
                ]
            ]
        ]
    ]
];
$result = $facebook->getMessage($data);
```

#### getRefData

Отримує дані рефералу з переданого масиву даних.

```php
$data = [
    'entry' => [
        [
            'messaging' => [
                [
                    'postback' => [
                        'referral' => [
                            'ref' => 'REFERRAL_DATA'
                        ]
                    ]
                ]
            ]
        ]
    ]
];
$result = $facebook->getRefData($data);
```

#### setStartButton

Встановлює кнопку старту для сторінки Facebook Messenger.

```php
$facebook->setStartButton();
```

#### getFacebookUserId

Отримує ідентифікатор користувача Facebook з переданого масиву даних.

```php
$data = [
    'entry' => [
        [
            'messaging' => [
                [
                    'sender' => [
                        'id' => '123456789'
                    ]
                ]
            ]
        ]
    ]
];
$result = $facebook->getFacebookUserId($data);
```

#### sendFacebookMessage

Надсилає повідомлення Facebook вказаному користувачеві.

```php
$response = $facebook->sendFacebookMessage('USER_ID', 'Hello, Facebook user!');
```

#### getDeepLink

Створює глибоке посилання для бота з переданими даними рефералу.

```php
$bot_url = 'https://m.me/your_bot';
$ref_data = 'REF_DATA';
$deep_link = $facebook->getDeepLink($bot_url, $ref_data);
```

Цей код створить глибоке посилання з URL бота та реферальними даними, наприклад:

```
https://m.me/your_bot?ref=REF_DATA
```

Таким чином, клас `Facebook` надає зручний інтерфейс для взаємодії з Facebook API у вашому додатку.