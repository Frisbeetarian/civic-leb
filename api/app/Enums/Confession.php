<?php

namespace App\Enums;

enum Confession: string
{
    case Maronite = 'maronite';
    case Sunni = 'sunni';
    case Shia = 'shia';
    case GreekOrthodox = 'greek_orthodox';
    case GreekCatholic = 'greek_catholic';
    case Druze = 'druze';
    case ArmenianOrthodox = 'armenian_orthodox';
    case ArmenianCatholic = 'armenian_catholic';
    case Evangelical = 'evangelical';
    case Alawite = 'alawite';
    case Minorities = 'minorities';
    case Christian = 'christian';
    case Muslim = 'muslim';
}
