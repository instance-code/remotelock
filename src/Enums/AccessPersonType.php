<?php

namespace InstanceCode\Remotelock\Enums;

enum AccessPersonType: string
{
    case GUEST = 'access_guest';
    case USER = 'access_user';
}
