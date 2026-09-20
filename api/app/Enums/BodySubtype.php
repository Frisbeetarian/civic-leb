<?php

namespace App\Enums;

enum BodySubtype: string
{
    case Ministry = 'ministry';
    case DirectorateGeneral = 'directorate_general';
    case PublicInstitution = 'public_institution';
    case SecurityService = 'security_service';
    case Regulator = 'regulator';
    case Court = 'court';
    case ConfessionalCourt = 'confessional_court';
    case CentralBank = 'central_bank';
    case StateCompany = 'state_company';
    case Oversight = 'oversight';
    case Council = 'council';
    case Legislature = 'legislature';
    case Chamber = 'chamber';
    case Committee = 'committee';
    case AdvisoryBody = 'advisory_body';
    case Board = 'board';
    case TerritorialUnit = 'territorial_unit';
}
