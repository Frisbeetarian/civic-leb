<?php

namespace App\Enums;

enum Sector: string
{
    case Legislative = 'legislative';
    case Executive = 'executive';
    case Judicial = 'judicial';
    case Independent = 'independent';
    case Local = 'local';
}
