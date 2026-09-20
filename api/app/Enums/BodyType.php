<?php

namespace App\Enums;

enum BodyType: string
{
    case Constituency = 'constituency';
    case Elected = 'elected';
    case Department = 'department';
    case Commission = 'commission';
    case Advisory = 'advisory';
}
