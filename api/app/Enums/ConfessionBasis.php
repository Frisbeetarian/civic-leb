<?php

namespace App\Enums;

enum ConfessionBasis: string
{
    case Constitution = 'constitution';
    case Pact = 'pact';
    case Custom = 'custom';
}
