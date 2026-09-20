<?php

namespace App\Enums;

enum SourceKind: string
{
    case OfficialPage = 'official_page';
    case Gazette = 'gazette';
    case Nna = 'nna';
    case PressHeadline = 'press_headline';
    case Wikidata = 'wikidata';
    case Academic = 'academic';
    case Other = 'other';
}
