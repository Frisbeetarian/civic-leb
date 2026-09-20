<?php

namespace App\Enums;

enum PositionKind: string
{
    case Head = 'head';
    case DeputyHead = 'deputy_head';
    case Chair = 'chair';
    case Member = 'member';
    case Seat = 'seat';
    case Minister = 'minister';
    case Speaker = 'speaker';
    case DeputySpeaker = 'deputy_speaker';
    case President = 'president';
    case PrimeMinister = 'prime_minister';
    case DeputyPrimeMinister = 'deputy_prime_minister';
    case Other = 'other';
}
