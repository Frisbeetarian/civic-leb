<?php

namespace App\Enums;

enum TenureStatus: string
{
    case Substantive = 'substantive';
    case Acting = 'acting';
    case Assigned = 'assigned';
    case Caretaker = 'caretaker';
    case ElectedByBoard = 'elected_by_board';
    case ExpiredContinuing = 'expired_continuing';
    case Disposal = 'disposal';
    case Vacant = 'vacant';
}
