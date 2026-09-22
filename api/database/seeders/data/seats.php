<?php

/**
 * The 128 parliamentary seats (Law 44/2017 Annex 1, reconciled in docs/research/parts/01 §5.2) and the
 * members of the 2022 chamber holding them, from database/seeders/data/seats/roster-2022.json.
 *
 * Seat = (major district, minor district, confession, ordinal). The ordinal ranks seats of the same
 * confession in a minor district by the holder's preferential votes in 2022, so slugs are stable
 * across re-seeds. Blocs and parties are as at the 2022 election (they shift; see decisions Q10).
 * History encoded: the Constitutional Council's 24 Nov 2022 recount (Finge -> Karami, Salloum -> Nasser)
 * and the West Bekaa Greek Orthodox vacancy since Ghassan Skaff's death on 13 Dec 2025.
 * The six Article 112 expatriate seats exist as never-constituted positions (suspended by Law 8/2021).
 */

$roster = json_decode(file_get_contents(__DIR__.'/seats/roster-2022.json'), true);
$ANNEX = 'http://legallaw.ul.edu.lb/ClarificationsNoteDetails.aspx?id=18508&language=ar';
$LIST = $roster['election']['source'];
$NAHARNET = 'https://www.naharnet.com/stories/en/293923-karami-returns-to-parliament-as-wins-of-fanj-salloum-annulled';
$SKAFF = 'https://en.wikipedia.org/wiki/Ghassan_Skaff';

$CONFESSION = [
    'maronite' => ['ماروني', 'Maronite', 'maronite'],
    'sunni' => ['سني', 'Sunni', 'sunnite'],
    'shia' => ['شيعي', 'Shia', 'chiite'],
    'greek_orthodox' => ['روم أرثوذكس', 'Greek Orthodox', 'grec-orthodoxe'],
    'greek_catholic' => ['روم كاثوليك', 'Greek Catholic', 'grec-catholique'],
    'druze' => ['درزي', 'Druze', 'druze'],
    'armenian_orthodox' => ['أرمن أرثوذكس', 'Armenian Orthodox', 'arménien-orthodoxe'],
    'armenian_catholic' => ['أرمن كاثوليك', 'Armenian Catholic', 'arménien-catholique'],
    'evangelical' => ['إنجيلي', 'Evangelical', 'évangélique'],
    'alawite' => ['علوي', 'Alawite', 'alaouite'],
    'minorities' => ['أقليات', 'Minorities', 'minorités'],
];

$instruments = [
    ['key' => 'results-2022', 'kind' => 'ministerial_decision', 'date' => '2022-05-17', 'title_ar' => 'إعلان نتائج الانتخابات النيابية في 15 أيار 2022', 'title_en' => 'Announcement of the results of the 15 May 2022 parliamentary elections (Minister of Interior and Municipalities)', 'source_url' => 'https://elections.gov.lb/'],
    ['key' => 'cc-2022-tripoli', 'kind' => 'court_decision', 'date' => '2022-11-24', 'title_ar' => 'قرارا المجلس الدستوري في الطعون الانتخابية لدائرة الشمال الثانية', 'title_en' => 'Constitutional Council decisions of 24 November 2022 on the North II election challenges', 'source_url' => $NAHARNET],
];

$bodies = [];
$positions = [];
$persons = [];
$tenures = [];
$edges = [];

$seatSlug = fn (array $m) => "lb-seat-{$m['minor']}-{$m['confession']}-{$m['ordinal']}";
$members = $roster['members'];
usort($members, fn ($a, $b) => [$a['major'], $a['minor'], $a['confession'], $a['ordinal']] <=> [$b['major'], $b['minor'], $b['confession'], $b['ordinal']]);

foreach ($members as $i => $m) {
    $major = $roster['districts']['major'][$m['major']];
    $minor = $roster['districts']['minor'][$m['minor']];
    [$cAr, $cEn, $cFr] = $CONFESSION[$m['confession']];
    $ord = $m['seats_in_group'] > 1 ? " ({$m['ordinal']})" : '';
    $slug = $seatSlug($m);
    $positions[] = [
        'slug' => $slug, 'body' => 'lb-parliament', 'kind' => 'seat', 'is_graph_node' => true,
        'title_ar' => "مقعد {$minor['ar']} – {$cAr}{$ord}", 'title_en' => "{$minor['en']} seat, {$cEn}{$ord}", 'title_fr' => "Siège de {$minor['fr']}, {$cFr}{$ord}",
        'description_ar' => "أحد مقاعد مجلس النواب الـ128 وفق الجدول الملحق بالقانون 44/2017 (المادة 2): الدائرة الكبرى {$major['ar']}، الدائرة الصغرى {$minor['ar']}، مخصص للطائفة ({$cAr}) عملاً بالمادة 24 من الدستور. يُنتخب بالاقتراع النسبي على أساس اللوائح لولاية أربع سنوات؛ مُدّدت ولاية مجلس 2022 حتى 31 أيار 2028 بالقانون 41/2026.",
        'description_en' => "One of the 128 seats of Parliament under Law 44/2017 Annex 1 (Art. 2): {$major['en']} major district, {$minor['en']} minor district, reserved for the {$cEn} community under Constitution Art. 24. Filled by proportional list vote for a four-year term; the 2022 chamber's term was extended to 31 May 2028 by Law 41/2026.",
        'appointing_authority' => 'lb-electorate', 'confession' => $m['confession'], 'confession_basis' => 'constitution',
        'seat_major_district' => $m['major'], 'seat_minor_district' => $m['minor'], 'seat_ordinal' => $m['ordinal'], 'term_years' => 4,
        'instrument' => 'law-44-2017', 'sort_order' => 100 + $i,
        'sources' => [[$ANNEX, 'official_page', 'Law 44/2017 Annex 1 (seat allocation by district and confession)']],
    ];

    if ($m['name_en']) {
        $persons[] = ['slug' => $m['person'], 'name_ar' => $m['name_ar'], 'name_en' => $m['name_en'], 'name_variants' => $m['name_variants'], 'party' => $m['party'], 'wikidata_qid' => $m['wikidata_qid']];
    }

    $h = $roster['history'][$m['person']] ?? null;
    $listNote = $m['list'] ? " Elected on the list \"{$m['list']}\" with {$m['votes']} preferential votes." : '';
    if ($h && isset($h['predecessor'])) {
        $p = $h['predecessor'];
        $persons[] = ['slug' => $p['person'], 'name_ar' => $p['name_ar'], 'name_en' => $p['name_en'], 'name_variants' => $p['name_variants'], 'party' => $p['party']];
        $tenures[] = ['position' => $slug, 'person' => $p['person'], 'status' => 'substantive', 'decision_date' => '2022-05-15', 'effective_date' => '2022-05-17', 'end_date' => '2022-11-24', 'end_reason' => 'annulment', 'instrument' => 'results-2022', 'bloc' => $p['bloc'], 'party' => $p['party'],
            'notes' => 'Proclaimed elected on 17 May 2022; membership annulled by the Constitutional Council on 24 November 2022 after a recount of the North II challenge.', 'sources' => [[$NAHARNET, 'press_headline', 'Naharnet, 24 Nov 2022']]];
        $tenures[] = ['position' => $slug, 'person' => $m['person'], 'status' => 'substantive', 'decision_date' => '2022-11-24', 'effective_date' => '2022-11-24', 'instrument' => 'cc-2022-tripoli', 'bloc' => $m['bloc'], 'party' => $m['party'],
            'notes' => 'Declared elected by the Constitutional Council on 24 November 2022 after the recount of the North II challenge.'.$listNote.' Bloc and party as at 2022.', 'sources' => [[$NAHARNET, 'press_headline', 'Naharnet, 24 Nov 2022'], [$LIST, 'other', 'Wikipedia, list of members of the 2022-2026 Parliament']]];
        continue;
    }
    $tenure = ['position' => $slug, 'person' => $m['person'], 'status' => 'substantive', 'decision_date' => '2022-05-15', 'effective_date' => '2022-05-17', 'instrument' => 'results-2022', 'bloc' => $m['bloc'], 'party' => $m['party'],
        'notes' => 'Elected on 15 May 2022, results proclaimed 17 May 2022.'.$listNote.' Bloc and party as at 2022.', 'sources' => [[$LIST, 'other', 'Wikipedia, list of members of the 2022-2026 Parliament']]];
    if ($h && isset($h['died'])) {
        $tenure['end_date'] = $h['died'];
        $tenure['end_reason'] = 'death';
        $tenure['sources'][] = [$SKAFF, 'other', 'Wikipedia, Ghassan Skaff'];
        $tenures[] = $tenure;
        $tenures[] = ['position' => $slug, 'person' => null, 'status' => 'vacant', 'vacancy_reason' => 'death', 'effective_date' => $h['died'], 'notes' => 'Vacant since the death of Ghassan Skaff on 13 December 2025; no by-election called (Law 44/2017 by-election rule; UNVERIFIED whether one will be held before 2028).', 'sources' => [[$SKAFF, 'other', 'Wikipedia, Ghassan Skaff']]];
        continue;
    }
    $tenures[] = $tenure;
}

// Article 112 expatriate seats: one per continent for six confessions, never constituted (suspended for 2022 by Law 8/2021)
foreach (['maronite', 'greek_orthodox', 'greek_catholic', 'sunni', 'shia', 'druze'] as $k => $c) {
    [$cAr, $cEn, $cFr] = $CONFESSION[$c];
    $positions[] = [
        'slug' => "lb-seat-expatriates-{$c}", 'body' => 'lb-parliament', 'kind' => 'seat', 'is_graph_node' => false,
        'title_ar' => "مقعد المغتربين – {$cAr}", 'title_en' => "Expatriate seat, {$cEn}", 'title_fr' => "Siège des émigrés, {$cFr}",
        'description_ar' => 'أحد المقاعد الستة المخصصة للمنتشرين بموجب المادة 112 من القانون 44/2017 (مقعد لكل قارة). لم يُشغل بعد: عُلّق العمل بالمادة 112 لانتخابات 2022 بالقانون رقم 8 تاريخ 3/11/2021.',
        'description_en' => 'One of the six seats reserved for expatriates by Law 44/2017 Art. 112 (one per continent), never constituted: Art. 112 was suspended for the 2022 election by Law 8 of 3 November 2021, so the chamber remained at 128 seats.',
        'appointing_authority' => 'lb-electorate', 'confession' => $c, 'confession_basis' => 'constitution',
        'seat_major_district' => 'expatriates', 'seat_ordinal' => 1, 'term_years' => 4, 'status' => 'never_constituted',
        'instrument' => 'law-44-2017', 'sort_order' => 300 + $k,
        'sources' => [['http://legallaw.ul.edu.lb/Law.aspx?lawId=288099', 'official_page', 'Law 8/2021 suspending Art. 112 for the 2022 election']],
    ];
}

return ['instruments' => $instruments, 'bodies' => $bodies, 'positions' => $positions, 'persons' => $persons, 'tenures' => $tenures, 'edges' => $edges];
