# Plan: Lebanon Gov Graph ("civicleb")

> Superseded in part. Architecture, data model and sweep design are now governed by
> `decisions.md` (grilling session and research amendments, 2026-09-18) and the research
> in `research/lebanon-public-institutions.md`. Section 4 below (YAML in git, Workers)
> is historical; the source of truth is Postgres behind Laravel + Filament.

Goal: the same product as graph.civlab.org/us, for the Lebanese state. Same core
model (entities, positions, people, cited relationships), same interaction pattern
(sector graph + entity pages + live layers), adapted to how Lebanon's government
actually works. Companion to `civlab-study.md`.

## 1. Scope for v1

- **Constituency**: People of Lebanon (electorate), centre of the graph.
- **Legislative**: Parliament (128 seats, 15 electoral districts, 64 Christian /
  64 Muslim allocation), Speaker, Deputy Speaker, Bureau, the 16 standing
  committees, Secretariat General.
- **Executive**: President, Prime Minister, Deputy PM, Council of Ministers, the
  ministries (about 22) with ministers and directors general, Ministers of State,
  Presidency and Grand Serail secretariats, OMSAR.
- **Judicial**: Higher Judicial Council, Court of Cassation, courts of appeal and
  first instance, State Council (Shura, administrative), Court of Audit,
  Constitutional Council, Judicial Council, Military Court, and the confessional
  personal-status courts as one grouped node per sect.
- **Independent / regulatory / public institutions** (Lebanon's "independent" ring):
  Banque du Liban, Banking Control Commission, Capital Markets Authority,
  Electricité du Liban, Ogero, Council for Development and Reconstruction, Civil
  Service Board, Central Inspection, Central Administration of Statistics,
  Telecommunications Regulatory Authority, Electricity Regulatory Authority
  (never constituted, a good "vacant" example), National Anti-Corruption Commission,
  Public Procurement Authority, Supervisory Commission for Elections, National
  Audiovisual Council, National Human Rights Commission, IDAL, Economic and Social
  Council, CNRS, Lebanese University, the four regional water establishments, Port
  of Beirut, Casino du Liban, Régie, MEA (state-owned via BDL), Higher Relief
  Council, Council of the South, Central Fund for the Displaced.
- **Security services** as `military_service` subtype: Lebanese Armed Forces,
  Internal Security Forces, General Security, State Security, Customs.
- **Local government** (phase 2): 8 governorates and governors, 26 districts and
  qaimaqams, unions of municipalities, municipalities (roughly 1,000; May 2025
  elections), mukhtars.

## 2. Data model changes vs CivLab

Keep the CivLab node/edge/person shapes exactly (see `reference/us-graph-sample.json`)
so the same rendering approach works, and add:

| Addition | Where | Why |
|---|---|---|
| `name_ar`, `name_fr`, `description_ar`, `aliases_ar` | node, position | trilingual UI and search ("البنك المركزي", "الداخلية", "Sûreté Générale") |
| `confession` (maronite, sunni, shia, greek_orthodox, druze, greek_catholic, armenian_orthodox, ...) and `confessionBasis` (constitution / national pact / custom) | position (dept_head, elected seat) | the confessional allocation of posts is the single most important structural fact about the Lebanese state |
| `status`: `filled` \| `acting` \| `caretaker` \| `vacant` \| `never_constituted` | position and tenure | caretaker cabinets and vacuums are the normal case, not an edge case |
| `district` and `seatConfession` | parliamentary seats (128 position nodes) | seat = district + confession, like state + district in the US |
| `bloc` (parliamentary bloc) and `party` | person tenure | Lebanese politics runs on blocs more than parties |
| `legalSource`: `{ instrument: constitution \| law \| decree \| decision, number, date, gazetteUrl }` | edge metadata and node | structured instead of a free-text cite, so we can render "Decree 1234/2025" and link to the Official Gazette |
| `sector` adds `local` (phase 2) | node | governorates, districts, municipalities |

Edge vocabulary stays the CivLab set (`elects`, `appoints`, `confirms`, `dept_head`,
`ex_officio`, `oversees`, `advises`, `administers`, `office`) with Lebanon meanings:

- `elects`: electorate → Parliament seats; Parliament → President; Parliament →
  Speaker and Deputy Speaker; municipal electorate → municipal councils.
- `appoints`: President + PM (by decree) → PM designation, ministers; Council of
  Ministers (decree) → grade-1 posts, directors general, BDL governor, army
  commander, board members; Minister (decision) → grade-2 and below; Higher Judicial
  Council → judicial appointments (with decree).
- `confirms`: Parliament → Council of Ministers (vote of confidence); Parliament →
  laws ratifying treaties. Keep it narrow.
- `ex_officio`: PM chairs Higher Defence Council; First President of Cassation chairs
  the Higher Judicial Council; ministers sit on CDR/EDL boards, etc.
- `oversees`: Parliament committees → ministries; Central Inspection and Court of
  Audit → administrations; BCC → banks (BCC is in graph, banks are not).
- `advises`: Shura Council opinions, Economic and Social Council, CNRS.

## 3. Sources

Structure and law:
- Lebanese Constitution (1926, Taif amendments 1990) and the National Pact
  conventions for confessional posts.
- Official Gazette (Al-Jarida al-Rasmiya) via the Presidency of the Council of
  Ministers site (pcm.gov.lb) for decrees and appointments.
- Lebanese University legal database (legallaw.ul.edu.lb) for laws and decrees
  creating each institution (the `legalSourceUrl` for every node).
- Parliament (lp.gov.lb) for members, blocs, committees, districts.
- Presidency (presidency.gov.lb), ministries' sites, OMSAR org charts, Civil Service
  Board for grade-1 positions.
- Ministry of Interior elections data and LADE for election results.
- Wikidata / Wikipedia (ar, en, fr) for people, dates and Wikimedia Commons portraits.
- Civil-society datasets: Gherbal Initiative, Lebanon Support / Civil Society
  Knowledge Centre, LCPS, Legal Agenda, Kulluna Irada.

News feed (for summaries, power map, articles): National News Agency (NNA, official,
trilingual), L'Orient-Today / L'Orient-Le Jour, An-Nahar, Al-Akhbar, LBCI, MTV,
Naharnet, The National (Lebanon desk). Start with 3 outlets like CivLab does.

## 4. Architecture

Mirror CivLab where it is proven and simplify where we can.

```
civicleb/
  data/                      curated source of truth, reviewed in git
    nodes/*.yaml             one file per entity or position (trilingual)
    edges/*.yaml             relationships with structured legal sources
    tenures/*.yaml           who held which position, from/to, status, source
    people/*.yaml            person records (names in 3 languages, image, party, bloc)
    layouts/lb-sectors.json  layout descriptor (sectors, rings, pills)
  pipeline/                  scripts: validate schema, derive edges/connectedNodes,
                             compute counts, emit public/graph/lb.json
  apps/web/                  Next.js app (or Astro + React islands), Tailwind,
                             d3 SVG graph, i18n (ar RTL / fr / en)
  workers/                   Cloudflare Workers + cron: news ingest, LLM summaries
                             and entity linking (Claude), power map, changes sweeps
```

- **Rendering**: d3 SVG with a deterministic `lb-sectors` layout. Sectors:
  LEGISLATIVE, EXECUTIVE (with an independent sub-ring), JUDICIAL, plus a
  **cabinet pill** for the Council of Ministers (24 ministers) and a
  **parliament pill** for 128 seats, matching CivLab's congress/cabinet pills.
- **Hosting**: Cloudflare Pages/Workers (the project already has the Cloudflare
  toolchain installed); D1 or R2 for articles, KV for computed feeds.
- **i18n**: `next-intl` or equivalent; Arabic is RTL, so the graph panel and the
  entity panel swap sides in Arabic.
- **Search**: client-side index over names, aliases and confession/district in all
  three languages (FlexSearch or MiniSearch).

## 5. Build order

1. **Schema + 40-node skeleton** (constitution-level bodies, cabinet, Parliament,
   top courts, BDL, army). Validate, render on the sector layout. This is the demo.
2. **Complete the executive**: every ministry, its DGs and attached public
   institutions, with legal sources. Roughly 150–250 nodes.
3. **Parliament roster**: 128 seat positions with district + confession, 2022
   members and blocs, committees.
4. **Judicial + independent bodies**, then security services.
5. **Live layers**: news ingest and summaries, changes feed (appointments decrees
   from the Gazette are the signal), power map.
6. **Phase 2**: governorates, districts, municipalities; budget layer from the
   Ministry of Finance.

## 6. Open decisions

- Framework: Next.js like CivLab (fastest path to the same feel) vs Astro islands
  (lighter, better for a static graph). Recommendation: Next.js on Cloudflare via
  OpenNext, unless SSR is not needed, in which case Astro.
- Default language and URL scheme: `/lb` with `?lang=` vs `/ar/lb`, `/fr/lb`, `/en/lb`.
  Recommendation: language prefix, English default, Arabic first-class.
- Name and domain (working name "civicleb").
