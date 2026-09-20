<?php

namespace App\Enums;

enum LegalForm: string
{
    case Ministry = 'ministry';
    case DirectorateGeneral = 'directorate_general';
    case PublicInstitution = 'public_institution';
    case AutonomousService = 'autonomous_service';
    case IndependentAuthority = 'independent_authority';
    case CentralBank = 'central_bank';
    case Company = 'company';
    case TemporaryCommittee = 'temporary_committee';
    case LocalAuthority = 'local_authority';
    case ConstitutionalBody = 'constitutional_body';
}
