<?php

namespace App\Enums;

enum BodyStatus: string
{
    case Active = 'active';
    case NeverConstituted = 'never_constituted';
    case Dormant = 'dormant';
    case ExpiredContinuing = 'expired_continuing';
    case Transitioning = 'transitioning';
    case AdHoc = 'ad_hoc';
    case Dissolved = 'dissolved';
}
