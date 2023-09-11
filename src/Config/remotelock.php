<?php

return [
    'endpoint' => env('REMOTE_LOCK_ENDPOIND', 'https://api.remotelock-pf.jp'),
    'client_id' => env('REMOTE_LOCK_CLIENT_ID', ''),
    'client_secret' => env('REMOTE_LOCK_CLIENT_SECRET', ''),
    'redirect_uri' => env('REMOTE_LOCK_CALLBACK_URL', 'urn:ietf:wg:oauth:2.0:oob'),
    'access_code_expired' => env('REMOTE_LOCK_ACCESS_CODE_EXPIRED', 60),
];
