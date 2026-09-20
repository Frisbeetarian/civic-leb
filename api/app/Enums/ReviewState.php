<?php

namespace App\Enums;

enum ReviewState: string
{
    case Draft = 'draft';
    case Reviewed = 'reviewed';
    case Published = 'published';
}
