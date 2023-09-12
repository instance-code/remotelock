<?php declare(strict_types=1);

namespace InstanceCode\Remotelock;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use InstanceCode\Remotelock\Enums\ErrorType;
use InstanceCode\Remotelock\Enums\AccessPersonType;

class Client
{

    public static $instance;

    protected string $endpoint = '';
    protected string $request_url = '';
    protected string $access_token = '';
    protected string $refresh_token = '';
    protected string $client_id = '';
    protected string $client_secret = '';
    protected string $redirect_uri = '';
    protected string $code = '';
    protected int $access_code_expired = 60; // second

    protected array $headers = [
        'Content-Type' => 'application/json; charset=utf-8',
    ];

    protected array $params = [];

    public int $status = 200;
    public mixed $response = null;

    public function __construct()
    {
        $this->endpoint = config('remotelock.endpoint');
        $this->client_id = config('remotelock.client_id');
        $this->client_secret = config('remotelock.client_secret');
        $this->redirect_uri = config('remotelock.redirect_uri');
        $this->access_code_expired = config('remotelock.access_code_expired');
    }

    public function __call(string $method, array $arguments)
    {
        if(in_array($method, ['get', 'post', 'put', 'patch', 'option']) && !empty($arguments)) {
            $url = $this->endpoint . $arguments[0];
            if(!empty($arguments[1])) {
                $params = is_array($arguments[1]) ? $arguments[1] : [$arguments[1]];
                $this->setParams($params);
            }
            $this->response = $this->http()->{$method}($url);
            return $this->response;
        }
    }

    public static function getInstance()
    {
        $http = self::$instance;
        if (!$http) {
            $http = new self();
        }

        return $http;
    }

    public function http(): PendingRequest
    {
        $http = Http::withHeaders($this->headers);
        if(!empty($this->params)) {
            $http = $http->withBody(json_encode($this->params));
        }
        return $http;
    }

    public function setRequestUrl(string $uri = '')
    {
        $this->request_url = $this->endpoint . $uri;
        return $this;
    }

    public function setCode(string $code = '')
    {
        $this->code = $code;
        return $this;
    }
    public function setRefreshToken(string $refresh_token = '')
    {
        $this->refresh_token = $refresh_token;
        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function setAuthorization(string $access_token)
    {
        $this->setHeaders([
            'Authorization' => "Bearer {$access_token}"
        ]);
        return $this;
    }

    public function setParams(array $params = [])
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    public function createLink()
    {
        $url = $this->endpoint . RemotelockUri::AUTHORIZE_URI;
        $queryParams = http_build_query([
            'client_id' => $this->client_id,
            'response_type' => 'code',
            'redirect_uri' => $this->redirect_uri,
        ]);
        return "{$url}?{$queryParams}";
    }

    public function getToken()
    {
        $this->setRequestUrl(RemotelockUri::TOKEN_URI)
        ->setParams([
            "code" => $this->code,
            "client_id" => $this->client_id,
            "client_secret" => $this->client_secret,
            "redirect_uri" => $this->redirect_uri,
            "grant_type" => "authorization_code"
        ]);
        $this->response = $this->http()
            ->post($this->request_url);
        return $this->response;
    }

    public function refreshToken()
    {
        $this->setRequestUrl(RemotelockUri::TOKEN_URI)
        ->setParams([
            "client_id" => $this->client_id,
            "client_secret" => $this->client_secret,
            "refresh_token" => $this->refresh_token,
            "grant_type" => "refresh_token"
        ]);
        $this->response = $this->http()
            ->post($this->request_url);
        return $this->response;
    }

    public function createAccessUser(AccessPersonType $accessType = AccessPersonType::GUEST, array $attribute = [])
    {
        $currentAttribute = array_merge([
            "starts_at" => \Carbon\Carbon::now()->toISOString(),
            "ends_at" => \Carbon\Carbon::now()->addSeconds($this->access_code_expired)->toISOString(),
            "name" => "Test User",
            "pin" => mt_rand(1000,9999),
        ], $attribute);

        $this->setRequestUrl(RemotelockUri::ACCESS_PERSON_URI)
        ->setParams([
            "type" => $accessType->value,
            "attributes" => $currentAttribute,
        ]);
        $this->response = $this->http()
            ->post($this->request_url); //

        return $this->response;
    }

    public function getQrCode(string|int $pin, array $attribute = [])
    {
        $currentAttribute = array_merge([
            "width" => 250,
            "height" => 250,
            "contents" => $pin,
        ], $attribute);

        $this->setRequestUrl(RemotelockUri::QR_URI)
        ->setParams([
            "type" => "qrcode",
            "attributes" => $currentAttribute,
        ]);
        $this->response = $this->http()
            ->post($this->request_url);
        return $this->response;
    }
}
