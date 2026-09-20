# Database schema (Postgres, Laravel)

Implements `decisions.md` including the research amendments. Postgres is the source of
truth; every curated table carries a review lifecycle (`review_state`: draft, reviewed,
published; `reviewed_at`, `published_at`). The public API and the graph export read
published rows only.

## Tables

### bodies — organisations (CivLab types constituency, elected, department, commission, advisory)

| Column | Type | Notes |
|---|---|---|
| id | bigint | |
| slug | text unique | `lb-…`, stable public id |
| type | text | constituency, elected, department, commission, advisory |
| subtype | text null | ministry, directorate_general, public_institution, security_service, regulator, court, confessional_court, central_bank, state_company, oversight, council, legislature, chamber, advisory_body, territorial_unit |
| legal_form | text null | ministry, directorate_general, public_institution, autonomous_service, independent_authority, central_bank, company, temporary_committee, local_authority |
| sector | text | legislative, executive, judicial, independent, local |
| parent_id | fk bodies null | hierarchy (CivLab parent/children) |
| level | smallint | depth under the top of its sector |
| name_ar, name_en, name_fr | text | fr nullable |
| description_ar, description_en | text null | |
| aliases | jsonb | `{ar: [], en: [], fr: []}` for search |
| official_url | text null | |
| legal_instrument_id | fk legal_instruments null | creating text; versioning lives on the instrument |
| seats_count | int | multi-seat bodies |
| functions | jsonb null | e.g. `["audit","jurisdiction"]` for the Court of Audit |
| state_funded | bool null | confessional courts |
| ownership | jsonb null | `{owner_body_id, share}` for state companies |
| status | text | active, never_constituted, dormant, expired_continuing, transitioning, ad_hoc, dissolved |
| status_note | text null | |
| layout_hints | jsonb null | per-node overrides for the layout descriptor (band, pill) |
| review_state, reviewed_at, published_at | | lifecycle |

### positions — offices attached to a body (CivLab dept_head nodes, plus seats and members)

| Column | Type | Notes |
|---|---|---|
| id, slug | | |
| body_id | fk bodies | the body this position heads or belongs to |
| kind | text | head, deputy_head, chair, member, seat, minister, speaker, deputy_speaker, president, prime_minister, other |
| is_graph_node | bool | true for heads and seats rendered as nodes; false for board members listed on the body |
| title_ar, title_en, title_fr | text | |
| description_ar, description_en | text null | |
| grade | text null | one, one_equivalent |
| appointing_authority_id | fk bodies null | deciding authority |
| confession | text null | maronite, sunni, shia, greek_orthodox, greek_catholic, druze, armenian_orthodox, armenian_catholic, evangelical, alawite, minorities, … |
| confession_basis | text null | constitution, pact, custom |
| confession_source_url | text null | required when basis = custom |
| seat_major_district, seat_minor_district | text null | seats only |
| seat_ordinal | smallint null | seats only |
| term_years | smallint null | |
| status | text | active, never_constituted, suspended, abolished |
| legal_instrument_id | fk null | |
| sort_order | int | |
| review lifecycle | | |

### persons

| Column | Notes |
|---|---|
| id, slug | |
| name_ar, name_en, name_fr | fr nullable |
| name_variants | jsonb array of alternative transliterations |
| party | text null (current or last known) |
| wikidata_qid | text null |
| portrait_path, portrait_licence, portrait_attribution, portrait_source_url | self-hosted portraits with licence |
| review lifecycle | |

### tenures — a person (or nobody) holding a position over time

| Column | Notes |
|---|---|
| id | |
| position_id | fk positions |
| person_id | fk persons null; null means an explicit vacancy record |
| status | substantive, acting, assigned, caretaker, elected_by_board, expired_continuing, disposal, vacant |
| vacancy_reason | expired, death, resignation, removal, never_constituted (when person_id is null) |
| decision_date, instrument_date, effective_date | date null |
| start_date | date; effective ?? instrument ?? decision |
| end_date | date null |
| end_reason | term, resignation, dismissal, death, retirement, extension, annulment |
| predecessor_tenure_id | fk tenures null |
| legal_instrument_id | fk null (the appointing decree or decision) |
| bloc, party | text null, as at the time |
| notes | text null |
| review lifecycle | |

### edges — relationships between nodes (bodies or positions)

| Column | Notes |
|---|---|
| id | |
| type | elects, appoints, confirms, dept_head, ex_officio, oversees, advises, administers, office, tutelage, owns, inspects, prosecutes, reviews, commands, regulates, disciplines, refers_to |
| from_type, from_id | polymorphic: body or position |
| to_type, to_id | polymorphic |
| seats_appointed | int |
| metadata | jsonb: `kind` (oversees), `share` (owns), `outcome` (reviews), `role` (ex_officio), `instrument_type`, `proposer_id`, `signatories[]`, `majority`, `advisory_ids[]` (appoints), `basis`, `cite`, `note` |
| legal_instrument_id | fk null |
| review lifecycle | |

### legal_instruments — versioned legal sources

| Column | Notes |
|---|---|
| id | |
| kind | constitution, taif, law, legislative_decree, decree, decree_in_com, cabinet_decision, ministerial_decision, board_election, parliament_vote, court_decision, rules_of_procedure, custom |
| number | text null (e.g. "44", "823") |
| date | date null |
| title_ar, title_en | text null |
| issuer_body_id | fk bodies null |
| signatories | jsonb null (body or position ids) |
| gazette_issue, gazette_date, gazette_url | |
| source_url, text_url | |
| in_force | bool |
| annulled_by_id, superseded_by_id | fk legal_instruments null |
| notes | |

### sources — provenance attached to any curated row

| Column | Notes |
|---|---|
| id | |
| sourceable_type, sourceable_id | polymorphic (body, position, person, tenure, edge, legal_instrument) |
| kind | official_page, gazette, nna, press_headline, wikidata, academic, other |
| url, title, publisher | |
| published_at, fetched_at | |
| reliability_note | text null |
| excerpt | text null |

### Later (milestone 5): change_candidates (sweep output), articles, power map.

## Graph export

A published snapshot is one JSON document: nodes (published bodies plus positions with
`is_graph_node`), edges (published, both ends published), people per node (current
tenures), counts, and the layout descriptor `lb-sectors` from `config/layouts/`. It is
regenerated on publish and cached at the edge.
