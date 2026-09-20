<?php

/**
 * Parliament's 16 standing committees (Rules of Procedure, 18 Oct 1994 as amended, Art. 20),
 * with the chairs and rapporteurs elected on 21 October 2025 (lp.gov.lb ContentRecordDetails?Id=34472).
 * Names were decoded from a PDF with a garbled font in the research pass: verify each before publishing.
 * Drafted from docs/research/lebanon-public-institutions.md, Part 01 §5.3.
 */

$LP = 'https://www.lp.gov.lb/ContentRecordDetails?Id=34472';
$RULES = 'https://www.lp.gov.lb/CustomPage.aspx?Id=7';

$committees = [
    // slug suffix, ar, en, fr, size, chair [slug, ar, en], rapporteur [slug, ar, en], overseen ministries
    ['finance-and-budget', 'لجنة المال والموازنة', 'Finance and Budget Committee', 'Commission des Finances et du Budget', 17, ['lb-ibrahim-kanaan', 'ابراهيم كنعان', 'Ibrahim Kanaan'], ['lb-ali-fayyad', 'علي فياض', 'Ali Fayyad'], ['lb-ministry-of-finance']],
    ['administration-and-justice', 'لجنة الإدارة والعدل', 'Administration and Justice Committee', 'Commission de l\'Administration et de la Justice', 17, ['lb-george-adwan', 'جورج عدوان', 'George Adwan'], ['lb-george-atallah', 'جورج عطالله', 'George Atallah'], ['lb-ministry-of-justice', 'lb-omsar']],
    ['foreign-affairs-and-emigrants', 'لجنة الشؤون الخارجية والمغتربين', 'Foreign Affairs and Emigrants Committee', 'Commission des Affaires étrangères et des Émigrés', 17, ['lb-fadi-alameh', 'فادي علامة', 'Fadi Alameh'], ['lb-hagop-pakradounian', 'هاغوب بقرادونيان', 'Hagop Pakradounian'], ['lb-ministry-of-foreign-affairs-and-emigrants']],
    ['public-works-transport-energy-and-water', 'لجنة الأشغال العامة والنقل والطاقة والمياه', 'Public Works, Transport, Energy and Water Committee', 'Commission des Travaux publics, des Transports, de l\'Énergie et de l\'Eau', 17, ['lb-sajih-attieh', 'سجيع عطية', 'Sajih Attieh'], ['lb-mohammad-khawaja', 'محمد خواجة', 'Mohammad Khawaja'], ['lb-ministry-of-public-works-and-transport', 'lb-ministry-of-energy-and-water']],
    ['education-higher-education-and-culture', 'لجنة التربية والتعليم العالي والثقافة', 'Education, Higher Education and Culture Committee', 'Commission de l\'Éducation, de l\'Enseignement supérieur et de la Culture', 12, ['lb-hassan-mrad', 'حسن مراد', 'Hassan Mrad'], ['lb-edgard-traboulsi', 'ادغار طرابلسي', 'Edgard Traboulsi'], ['lb-ministry-of-education-and-higher-education', 'lb-ministry-of-culture']],
    ['public-health-labour-and-social-affairs', 'لجنة الصحة العامة والعمل والشؤون الاجتماعية', 'Public Health, Labour and Social Affairs Committee', 'Commission de la Santé publique, du Travail et des Affaires sociales', 12, ['lb-bilal-abdallah', 'بلال عبدالله', 'Bilal Abdallah'], ['lb-samer-el-tom', 'سامر التوم', 'Samer El Tom'], ['lb-ministry-of-public-health', 'lb-ministry-of-labour', 'lb-ministry-of-social-affairs']],
    ['national-defence-interior-and-municipalities', 'لجنة الدفاع الوطني والداخلية والبلديات', 'National Defence, Interior and Municipalities Committee', 'Commission de la Défense nationale, de l\'Intérieur et des Municipalités', 17, ['lb-jihad-al-samad', 'جهاد الصمد', 'Jihad al-Samad'], ['lb-asaad-dergham', 'أسعد درغام', 'Asaad Dergham'], ['lb-ministry-of-national-defence', 'lb-ministry-of-interior-and-municipalities']],
    ['displaced', 'لجنة شؤون المهجرين', 'Displaced Affairs Committee', 'Commission des Déplacés', 12, ['lb-hagop-pakradounian', 'هاغوب بقرادونيان', 'Hagop Pakradounian'], ['lb-hussein-jashi', 'حسين جشي', 'Hussein Jashi'], ['lb-ministry-of-the-displaced']],
    ['agriculture-and-tourism', 'لجنة الزراعة والسياحة', 'Agriculture and Tourism Committee', 'Commission de l\'Agriculture et du Tourisme', 12, ['lb-ayoub-hmayed', 'أيوب حميد', 'Ayoub Hmayed'], ['lb-adib-abdel-massih', 'أديب عبد المسيح', 'Adib Abdel Massih'], ['lb-ministry-of-agriculture', 'lb-ministry-of-tourism']],
    ['environment', 'لجنة البيئة', 'Environment Committee', 'Commission de l\'Environnement', 12, ['lb-ghayath-yazbeck', 'غياث يزبك', 'Ghayath Yazbeck'], ['lb-qassem-hashem', 'قاسم هاشم', 'Qassem Hashem'], ['lb-ministry-of-environment']],
    ['national-economy-trade-industry-and-planning', 'لجنة الاقتصاد الوطني والتجارة والصناعة والتخطيط', 'National Economy, Trade, Industry and Planning Committee', 'Commission de l\'Économie nationale, du Commerce, de l\'Industrie et du Plan', 12, ['lb-farid-boustany', 'فريد البستاني', 'Farid Boustany'], ['lb-nasser-jaber', 'ناصر جابر', 'Nasser Jaber'], ['lb-ministry-of-economy-and-trade', 'lb-ministry-of-industry']],
    ['media-and-communications', 'لجنة الإعلام والاتصالات', 'Media and Communications Committee', 'Commission de l\'Information et des Télécommunications', 12, ['lb-ibrahim-moussawi', 'ابراهيم الموسوي', 'Ibrahim Moussawi'], ['lb-yassine-yassine', 'ياسين ياسين', 'Yassine Yassine'], ['lb-ministry-of-information', 'lb-ministry-of-telecommunications']],
    ['youth-and-sports', 'لجنة الشباب والرياضة', 'Youth and Sports Committee', 'Commission de la Jeunesse et des Sports', 12, ['lb-simon-abi-ramia', 'سيمون أبي رميا', 'Simon Abi Ramia'], ['lb-raed-berro', 'رائد برو', 'Raed Berro'], ['lb-ministry-of-youth-and-sports']],
    ['human-rights', 'لجنة حقوق الإنسان', 'Human Rights Committee', 'Commission des Droits de l\'homme', 12, ['lb-michel-moussa', 'ميشال موسى', 'Michel Moussa'], ['lb-nazih-matta', 'نزيه متى', 'Nazih Matta'], []],
    ['women-and-child', 'لجنة المرأة والطفل', 'Women and Child Committee', 'Commission de la Femme et de l\'Enfant', 12, ['lb-inaya-ezzeddine', 'عناية عز الدين', 'Inaya Ezzeddine'], ['lb-adnan-traboulsi', 'عدنان طرابلسي', 'Adnan Traboulsi'], []],
    ['information-technology', 'لجنة تكنولوجيا المعلومات', 'Information Technology Committee', 'Commission des Technologies de l\'information', 9, ['lb-tony-frangieh', 'طوني فرنجية', 'Tony Frangieh'], ['lb-elias-hankache', 'الياس حنكش', 'Elias Hankache'], []],
];

$instruments = [
    ['key' => 'rules-1994', 'kind' => 'rules_of_procedure', 'date' => '1994-10-18', 'title_ar' => 'النظام الداخلي لمجلس النواب', 'title_en' => 'Rules of Procedure of Parliament (18 Oct 1994, as amended to 2003)', 'gazette_issue' => '52', 'gazette_date' => '2003-11-13', 'source_url' => $RULES],
];
$bodies = [];
$positions = [];
$persons = [];
$seen = [];
$tenures = [];
$edges = [];

foreach ($committees as [$suffix, $ar, $en, $fr, $size, $chair, $rapporteur, $ministries]) {
    $slug = "lb-committee-{$suffix}";
    $bodies[] = [
        'slug' => $slug, 'type' => 'commission', 'subtype' => 'committee', 'legal_form' => 'constitutional_body', 'sector' => 'legislative', 'parent' => 'lb-parliament',
        'name_ar' => $ar, 'name_en' => $en, 'name_fr' => $fr, 'aliases' => ['ar' => [], 'en' => [], 'fr' => []], 'instrument' => 'rules-1994', 'official_url' => $RULES, 'seats_count' => $size, 'layout_hints' => ['pill' => 'committees'],
        'description_en' => "Standing committee of Parliament (Rules of Procedure, Art. 20), {$size} members elected at the opening of each October session; the committee elects its chair and rapporteur by secret ballot (Art. 23). Examines bills in its remit and questions the ministers concerned.",
        'sources' => [[$RULES, 'official_page', 'Rules of Procedure, Arts. 19-23'], [$LP, 'official_page', 'Committee elections, 21 Oct 2025']],
    ];
    $positions[] = ['slug' => "{$slug}-chair", 'body' => $slug, 'kind' => 'chair', 'title_ar' => "رئيس {$ar}", 'title_en' => "Chair of the {$en}", 'appointing_authority' => $slug, 'term_years' => 1, 'instrument' => 'rules-1994'];
    $positions[] = ['slug' => "{$slug}-rapporteur", 'body' => $slug, 'kind' => 'other', 'is_graph_node' => false, 'title_ar' => "مقرر {$ar}", 'title_en' => "Rapporteur of the {$en}", 'appointing_authority' => $slug, 'term_years' => 1, 'instrument' => 'rules-1994'];
    foreach ([$chair, $rapporteur] as $p) {
        if (! isset($seen[$p[0]])) {
            $persons[] = ['slug' => $p[0], 'name_ar' => $p[1], 'name_en' => $p[2]];
            $seen[$p[0]] = true;
        }
    }
    $note = 'Elected by the committee on 21 October 2025 (lp.gov.lb Id=34472); name decoded from a garbled PDF font, UNVERIFIED until checked against the committee page.';
    $tenures[] = ['position' => "{$slug}-chair", 'person' => $chair[0], 'status' => 'elected_by_board', 'decision_date' => '2025-10-21', 'effective_date' => '2025-10-21', 'instrument' => 'rules-1994', 'notes' => $note, 'sources' => [[$LP, 'official_page', 'Committee elections, 21 Oct 2025']]];
    $tenures[] = ['position' => "{$slug}-rapporteur", 'person' => $rapporteur[0], 'status' => 'elected_by_board', 'decision_date' => '2025-10-21', 'effective_date' => '2025-10-21', 'instrument' => 'rules-1994', 'notes' => $note];
    foreach ($ministries as $m) {
        $edges[] = ['type' => 'oversees', 'from' => $slug, 'to' => $m, 'instrument' => 'rules-1994', 'metadata' => ['kind' => 'parliamentary', 'cite' => 'Rules of Procedure Art. 20; Constitution Art. 66', 'note' => 'Examines the ministry\'s bills and budget and questions its minister.']];
    }
}

return ['instruments' => $instruments, 'bodies' => $bodies, 'positions' => $positions, 'persons' => $persons, 'tenures' => $tenures, 'edges' => $edges];
