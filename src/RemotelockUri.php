<?php declare(strict_types=1);

namespace InstanceCode\Remotelock;

class RemotelockUri
{
    public const GUEST_ACCESS_URI = '/access_persons';
    public const QR_URI = '/util/qrcodes';
    public const AUTHORIZE_URI = '/oauth/authorize';
    public const TOKEN_URI = '/oauth/token';
    public const ACCESS_PERSON_URI = '/access_persons';
    public const UPDATE_ACCESS_PERSON_URI = '/access_persons/%d';
    public const DEACTIVATE_ACCESS_PERSON_URI = '/access_persons/%d/deactivate';
}
