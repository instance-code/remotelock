## Config

```
composer require instance-code/remotelock
```

## Add to project

```
    'providers' => ServiceProvider::defaultProviders()->merge([
        InstanceCode\Remotelock\Providers\RemotelockServiceProvider::class,
    ])->toArray(),
```

## CREATE INSTANCE

```
$client = Remotelock::getInstance();
```

## CREATE LINK

``` get code
    $url = $client->createLink();

    <a href="{{ $url }}">GET CODE</a>
```

## GET TOKEN

```
$client->setCode('{code}')
        ->getToken();
```

## REFRESH TOKEN

```
$client->setRefreshToken('{refreshToken}')
        ->refreshToken()();
```

## CREARTE ACCESS USER

AccessPersonType::GUEST | AccessPersonType::USER

refer [Create an access guest](https://remotelock.kke.co.jp/confi/remotelock-apidoc/members-access#create-an-access-guest)
```
$client->createAccessUser(AccessPersonType::GUEST, $attribute);
```

## GET QR CODE

AccessPersonType::GUEST | AccessPersonType::USER

refer [CREATE QR](https://remotelock.kke.co.jp/confi/remotelock-apidoc/#qrcodes-create-a-qrcode)
```
$client->getQrCode(string|int $pin, array $attribute = '');
```

## Create any request

1. Support methods: get, post, put, patch, delete, option

refer [Create an access guest](https://remotelock.kke.co.jp/confi/remotelock-apidoc/)
```
$client->get($URI, $params);
$client->post($URI, $params);
$client->put($URI, $params);
$client->patch($URI, $params);
$client->delete($URI, $params);
```

2. Respon json

