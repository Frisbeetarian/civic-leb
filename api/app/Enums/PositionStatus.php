<?php

namespace App\Enums;

enum PositionStatus: string
{
    case Active = 'active';
    case NeverConstituted = 'never_constituted';
    case Suspended = 'suspended';
    case Abolished = 'abolished';
}
