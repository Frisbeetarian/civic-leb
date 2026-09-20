<?php

namespace App\Enums;

enum EdgeType: string
{
    case Elects = 'elects';
    case Appoints = 'appoints';
    case Confirms = 'confirms';
    case DeptHead = 'dept_head';
    case ExOfficio = 'ex_officio';
    case Oversees = 'oversees';
    case Advises = 'advises';
    case Administers = 'administers';
    case Office = 'office';
    case Tutelage = 'tutelage';
    case Owns = 'owns';
    case Inspects = 'inspects';
    case Prosecutes = 'prosecutes';
    case Reviews = 'reviews';
    case Commands = 'commands';
    case Regulates = 'regulates';
    case Disciplines = 'disciplines';
    case RefersTo = 'refers_to';
}
