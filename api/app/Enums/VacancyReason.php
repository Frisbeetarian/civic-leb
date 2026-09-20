<?php

namespace App\Enums;

enum VacancyReason: string
{
    case Expired = 'expired';
    case Death = 'death';
    case Resignation = 'resignation';
    case Removal = 'removal';
    case NeverConstituted = 'never_constituted';
}
