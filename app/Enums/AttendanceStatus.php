<?php

namespace App\Enums;

final class AttendanceStatus
{
    const ON_TIME           = 'OT';
    const LATE              = 'L';
    const ABSENT            = 'A';
    const LEFT_TIMELY       = 'LT';
    const LEFT_EARLY        = 'LE';
    const LEFT_LATER        = 'LL';
    const REMOTE_HOME       = '0';
    const REMOTE_OFFICE     = '1';
}
