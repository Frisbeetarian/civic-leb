<?php

namespace App\Enums;

enum EndReason: string
{
    case Term = 'term';
    case Resignation = 'resignation';
    case Dismissal = 'dismissal';
    case Death = 'death';
    case Retirement = 'retirement';
    case Extension = 'extension';
    case Annulment = 'annulment';
}
