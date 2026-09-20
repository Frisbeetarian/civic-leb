<?php

namespace App\Enums;

enum InstrumentKind: string
{
    case Constitution = 'constitution';
    case Taif = 'taif';
    case Law = 'law';
    case LegislativeDecree = 'legislative_decree';
    case Decree = 'decree';
    case DecreeInCom = 'decree_in_com';
    case CabinetDecision = 'cabinet_decision';
    case MinisterialDecision = 'ministerial_decision';
    case BoardElection = 'board_election';
    case ParliamentVote = 'parliament_vote';
    case CourtDecision = 'court_decision';
    case RulesOfProcedure = 'rules_of_procedure';
    case Custom = 'custom';
}
