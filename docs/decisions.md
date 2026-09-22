# Decisions (grilling session, 2026-09-18)

Outcome of a structured Q&A. Supersedes the architecture section of `lebanon-plan.md`
where they differ (notably: database, not YAML, is the source of truth).

## Product

| # | Decision | Choice |
|---|---|---|
| Q1 | Audience | Lebanese public and civil society; a public transparency tool |
| Q2 | v1 scope | The national state end to end: constitutional core, full executive, all independent bodies, full judiciary, security services, all 128 parliamentary seats with members and blocs. 500+ nodes. Local government is phase 2 |
| Q4 | Languages | Arabic (default, RTL first-class) + English. Trilingual names/aliases for search. French descriptions later |
| Q6 | Live layers in v1 | Changes feed only. News summaries and power map later |
| Q8 | Confessional allocation | Shown per position with its legal basis (Constitution / National Pact / custom), legend toggle to colour the graph by sect. A structural fact with sources, never a label on people |
| Q9 | Graph layout | Radial, electorate at centre, 4 sectors: Legislative, Executive, Judicial, Independent & Regulatory. Parliament pill (128 seats), Council of Ministers pill (24) |
| Q10 | Parliament seats | 128 seat position nodes, each with district + confession and a tenure |
| Q11 | History depth | Current holders plus all changes since January 2025 (end of the presidential vacuum, Salam government) |
| Q13 | Openness | Open source: MIT code, CC BY data. Portraits self-hosted (R2) with licence and attribution recorded |
| Q16 | Team | Solo build; contributions welcome later via validator + CONTRIBUTING |
| Q17 | First milestone | Constitutional core (about 60 nodes) rendered on the 4-sector layout in Arabic and English, end to end through API and frontend |
| Q26 | Name | Working name `civicleb`, slug `/lb`; public name and domain decided before launch |
| Q27 | Visual identity | Same interaction patterns as CivLab, own palette, own type (Arabic-first pair), own glyphs |
| Q29 | Node taxonomy | CivLab's six types (constituency, elected, department, dept_head, commission, advisory) plus Lebanon subtypes: ministry, public_institution, security_service, regulator, court, confessional_court, central_bank, seat |

## Data and backend

| # | Decision | Choice |
|---|---|---|
| Q3 / Q18 | Data production and source of truth | Postgres is the source of truth. LLM-assisted drafting (Claude jobs) writes draft rows with sources; you review in Filament. Git holds code only |
| Q18 | Backend | Laravel + Filament, backend and API only. Filament provides the admin panel and its auth |
| Q20 | Hosting / DB | Laravel Cloud + Postgres |
| Q7 | Changes feed source | Daily scheduled sweep of pcm.gov.lb decrees and NNA; Claude extracts candidate tenure changes with citations |
| Q12 / Q14 | Approval | Candidates reviewed and approved in Filament (the "private admin page"); approval publishes |
| Q28 | Record lifecycle | draft → reviewed → published. Public API exposes published only. Sweep candidates use the same states |
| Q21 | Open data delivery | Public read-only JSON API + nightly JSON/CSV dumps committed to `data/` |

## Frontend

| # | Decision | Choice |
|---|---|---|
| Q5 / Q22 | Framework and hosting | Next.js on Cloudflare via OpenNext; React + Tailwind + d3 SVG; next-intl for ar/en |
| Q23 | Freshness | Server-rendered pages fetch from the API; Cloudflare caches API responses; Laravel purges the cache on publish |
| Q25 | Search | Client-side index (MiniSearch/FlexSearch) over names, aliases, district, confession, built from the graph JSON |
| Q24 | Repo layout | Monorepo: `api/` (Laravel), `web/` (Next.js), `data/` (nightly dumps), shared TS types generated from the API |

## Consequences worth remembering

- Two deployables: Laravel Cloud (API + Filament + scheduler + queues) and Cloudflare (Next.js). Cloudflare fronts both.
- Every edge and tenure carries a structured legal source (instrument, number, date, gazette URL) and a review state.
- The graph payload for the frontend is one published JSON document (nodes, edges, layout descriptor `lb-sectors`), regenerated on publish and cached at the edge.
- Milestone 1 order: Laravel schema + Filament resources → seed 60 core nodes via Claude drafts → publish endpoint → Next.js graph on `lb-sectors` → Arabic/English switch.

## Research amendments (2026-09-18, approved)

Source: `docs/research/lebanon-public-institutions.md`, synthesis section B. These extend
the decisions above; where they conflict, the amendment wins.

| Area | Amendment |
|---|---|
| Q29 taxonomy | Keep the six CivLab types. Add subtypes `state_company` (with `ownership`), `oversight` and `council` (on `commission`), `directorate_general` (on `department`, only for DGs with attached bodies or public salience), `territorial_unit` (phase 2). Add a `legalForm` attribute distinct from `subtype`: `ministry, directorate_general, public_institution, autonomous_service, independent_authority, central_bank, company, temporary_committee, local_authority`. Boards are `commission` child nodes with a dated member list and a separate chair tenure |
| Edges | Add `tutelage`, `owns` (with `share`), `inspects`, `prosecutes`, `reviews` (with `outcome`), `commands` in v1; `regulates`, `disciplines`, `refers_to` later. `oversees` keeps a `kind` (`audit, parliamentary, civil_service, financial_control, prudential`). `appoints` carries a structured payload: instrument type, proposer, signatories, majority, advisory bodies; its source is the deciding authority. Hierarchy stays in `parent`/`children` |
| Body status | `active, never_constituted, dormant, expired_continuing, transitioning, ad_hoc, dissolved` |
| Tenure status | `substantive, acting, assigned, caretaker, elected_by_board, expired_continuing, disposal`; `endReason: term, resignation, dismissal, death, retirement, extension, annulment`. Vacancies are explicit records with `reason: expired, death, resignation, removal, never_constituted` |
| Legal sources | Versioned: `inForce`, `annulledBy`, `supersededBy`. Instrument types: `constitution, taif, law, legislative_decree, decree, decree_in_com, cabinet_decision, ministerial_decision, board_election, parliament_vote, court_decision, rules_of_procedure, custom`. Each carries number, date, signatories, gazette issue, gazette date, URL |
| Tenure dates and provenance | `decision_date`, `instrument_date`, `effective_date`; `sources[]` with kind (`official_page, gazette, nna, press_headline, wikidata`), URL, fetched-at, reliability note |
| Positions | `grade` (`one, one_equivalent, null`), `appointingAuthority`, `confession`, `confessionBasis` (`constitution` for the 128 seats only; `pact` for President, PM, Speaker, Deputy Speaker, Deputy PM; `custom` elsewhere, with a source URL) |
| Q10 seats | 128 seat nodes keyed by major district, minor district, confession, ordinal (Law 44/2017 Annex 1, Part 01 §5.2), plus 6 Article 112 expatriate seats as `never_constituted`, flagged suspended. Blocs are dated memberships |
| Persons | `names {ar, en, fr, variants[]}` |
| Q9 layout | Four sectors stand. PCM-attached oversight bodies sit in Independent & Regulatory under an "Oversight and control" band; Court of Audit on the Judicial/Independent boundary with `functions: [audit, jurisdiction]`; security services as an inner ring of Executive; confessional courts in Judicial (6 state-organised systems inside, 13 community tribunals on the outer edge, `stateFunded: false`) |
| Q7 sweep | Order: pcm.gov.lb session-decision PDFs (cap ~5 requests/day; CAPTCHA after ~70), NNA Arabic (Next.js payload), presidency.gov.lb and lp.gov.lb sequential IDs, Google News RSS (~20 Arabic queries, ±3-day corroboration rule), cc.gov.lb weekly, Official Gazette monthly back-fill. legallaw.ul.edu.lb lookups are a batch job with retries |
| Q13 portraits | Commons covers 9 of 22 ministers; official sites are a second source with per-image licence; positions may launch without portraits |
| Q2 / Q17 scope | 600 to 650 nodes expected. Judges are never nodes; public hospitals are one class node in v1. Milestone 1 (about 60 nodes) must include one body per new subtype and one instance of each new status |
| Facts to encode | Parliament term extended to 31 May 2028 (Law 41/2026; CC Decision 7/2026); 127/128 seats filled (Skaff seat vacant since 13 Dec 2025); Salam government in office, renewed confidence 16 Sep 2026; Law 36/2026 annulled by CC Decision 1/2026; nine governorates |

## Milestone 1 status (2026-09-18)

Done: monorepo (`api/`, `web/`), Laravel 13 + Filament 5 with the full schema
(`docs/schema.md`), enums for every vocabulary in the amendments, review lifecycle with
publish actions that invalidate the graph cache, `GraphExporter` and the public API,
`lb-sectors` layout descriptor, Next.js 16 site with Arabic (RTL, default) and English,
d3 SVG radial graph with four sectors, cabinet pill, oversight/regulator/confessional
bands, entity panel, client-side search, legend, light and dark themes. Seeded and
published the constitutional core: 29 instruments, 55 bodies, 43 positions, 44 persons,
46 tenures, 74 edges (97 graph nodes). Tests: 10 Pest tests green on Postgres; web
lint, types and production build green.

Known gaps carried forward: seed rows with placeholder dates are flagged UNVERIFIED in
their notes and must be verified before real publication; portraits absent; Parliament
pill has no seat nodes yet; no deployment config (OpenNext, Laravel Cloud), no nightly
dumps, no sweep Worker, no Cloudflare cache purge; api/CLAUDE.md is the Laravel
installer's default and can be replaced.

## Frontend revision (2026-09-18, user request: "look and act more like graph.civlab.org/us")

Q27 revised. The site now follows CivLab's structure and chrome, not only its interaction
patterns: a 560px card column (breadcrumb header card, search button, Latest changes,
Overview, About; entity pages replace the column with the entity cards) beside a
persistent full-height graph canvas; entity pages are routes (`/{locale}/n/{slug}`) and
the graph stays mounted with the selection derived from the URL; a Legend trigger
bottom-start with Entities and Relationships sections and "Show all"; a selection chip at
the bottom centre; back/forward and theme controls over the canvas; a search modal. The
graph draws sector territories from an inner disc to an outer rim with seams, dashed ring
rulers with arc labels, a scalloped centre seal, CivLab's glyph geometry (white base,
50% colour, 1px stroke), no node labels at rest (tooltips on hover), idle edges at
CivLab's alphas (none in light, 0.26 in dark), and full-strength coloured edges with verb
labels for the selected node. Palette moved to CivLab's warm stone greys with our own
branch colours; type is Inter for Latin and IBM Plex Sans Arabic for Arabic. Dark is the
default theme. The changes feed is derived from published tenures until the sweep exists.

## Selection focus (2026-09-19)

Implemented CivLab's focus behaviour (study in `civlab-study.md` §10): selecting a node
rotates the wheel so it sits at 6 o'clock (rotation kept continuous so the 750 ms tween
takes the shortest way), the focused sector widens by 1.35×, neighbours are compressed to
leave 0.16 rad around the selection, the glyph grows 1.3× (1.5× for commissions and
advisory bodies), dotted fan lines join the focused body to its children and parent, edges
attach after the tween, head positions are hidden at rest and revealed for the focused
family, and the top-end arrows step through nodes of the same type (history back/forward
when nothing is selected). Territories and pills tween their paths in browsers that
animate the `d` property; elsewhere they snap while nodes still glide.

## Graph layout rules (2026-09-19, after the CivLab-dimensions pass)

- Node radii: elected 17, commission and regulator 14, advisory 13, departments 14 to 20 by number
  of attached bodies, top offices (President, PM, Speaker) 15 on the elected ring. Heads are
  6px person badges attached to the top-left of their body's glyph, always visible, stacked
  along the top edge when a body has several; they turn with the body and are clickable. Column type: 16px body, 20px card
  titles, 32px entity titles.
- Each ring, pill, band or apex group spreads across its whole sector on its own radius;
  groups that share a radius in a sector are spread together. A group whose pitch is
  smaller than a glyph plus a gap staggers alternate members ±13px (CivLab rowOffset).
- Ring radii (units of min(width,height)/9): elected 1.4; cabinet pill 2.05 and committees pill
  2.7 (two staggered rows, stagger 15px); security band 3.0; oversight band 2.0 and regulators
  2.6 in the independent sector; personal-status courts 3.0 in the judicial sector; departments,
  commissions and advisory bodies on the outer ring at 3.7; apex nodes (Constitutional Council,
  Cassation) at 1.25. Children of a body on a pill or on the outer ring spread on their own ring
  (fan line still drawn); other children sit 0.62 units beyond their parent.
- Every labelled grouping (pills and bands) is outlined by a capsule sized from its glyphs:
  thickness = stagger rows + glyph + 14px margin each side, angular margin glyph + 14px at
  each end. Groups spread evenly but never wider than a comfortable pitch (2 glyphs + 44px), so
  small groups stay compact around the sector's middle. Labels sit 12px inside or outside the
  capsule per the descriptor's `labelSide`; they hide while a node is selected.
- (Superseded 2026-09-20: heads are attached badges, see radii above.)
- Selection: pure rotation to 6 o'clock (one compositor transform on an HTML layer, glyphs
  tilt with the wheel, 750ms) plus 1.3x/1.5x growth after the turn. No sector widening, no
  compression, no stagger change. Selection is optimistic on click; entity pages prefetch
  on hover. Edges, labels and fan lines hide during the turn and attach after it.
- Edge hover: the hovered edge lights up in its family colour with arrowhead and verb, its
  endpoints stay bright, and the tooltip explains the relationship type; with a node
  selected only that node's edges are hoverable.
- An overlap check script lives in the session scratchpad pattern: compute the layout at
  the canvas size and assert no two placed nodes are closer than r1 + r2 + 2.

## Standing committees (2026-09-19)

Added the 16 standing committees of Parliament to the seed (`database/seeders/data/committees.php`,
merged by the seeder with `core.php`): commission-type bodies with subtype `committee`, parent
Parliament, placed in their own "Parliamentary committees" pill (radius 2.7, two staggered rows) in a legislative sector widened to 95° (executive 130°); a chair
position (graph node) and a rapporteur position (not a node) each, with tenures from the
21 October 2025 election flagged UNVERIFIED (names decoded from lp.gov.lb's PDF); parliamentary
`oversees` edges to the ministries in each remit. Joint committees and ad hoc subcommittees are
deliberately excluded (transient, no standing jurisdiction). Graph now 71 bodies, 75 positions,
75 persons, 78 tenures, 96 edges.

## Static preview deployment (2026-09-20)

The site has a static data mode: with no `API_URL`, `web/src/lib/graph.ts` reads the bundled
snapshot `web/data/lb-graph.json` (refresh with `pnpm snapshot` while the API runs) and derives
node pages from it. `pnpm build:static` (STATIC_EXPORT=1) exports every route for both
locales into `out/`; `pnpm deploy:static` builds and deploys it as Cloudflare Workers static
assets (`web/wrangler.jsonc`, worker `civicleb`, custom domains civ-leb.com and
www.civ-leb.com; `/` redirects to `/ar/` via public/index.html and `_redirects`). The locale
middleware was removed so the same code builds in both modes; Arabic is the default landing.
Updating the public site until Laravel Cloud exists means: publish in Filament locally, run
`pnpm snapshot`, then `pnpm deploy:static`. Code repo: git@github.com:Frisbeetarian/civic-leb.git
(push pending collaborator access for the SSH key's GitHub account).

## Mobile layout (2026-09-20)

Below 1024px the shell reorders to CivLab's phone structure: the graph band first
(54vh, 350 to 450px) with the header card floating over it, a toolbar row (Legend, Graph /
Power map, language, theme) under the band, then the cards in one column. The graph runs in
a mobile mode: the wheel is fitted to the width (unit = width / 10.4), glyphs and stagger
scale to 0.82, sector labels 10px, and the wheel's centre sits so the whole wheel is in view
with the selected node (rotated to 6 o'clock) just above the band's bottom edge, its name chip
below it. Descriptions clamp to four lines with "Read more" on phones. The viewport meta
fixes scale at 1 so pinch-zoom does not fight the wheel. Verified at 390x844 with real device
emulation (Playwright): document width equals the viewport, no horizontal overflow. Plain
headless Chrome without emulation ignores the viewport meta and lays out at 500px, so use
the emulated capture for mobile checks.

## Mobile refinements (2026-09-21)

Wheel fitted to 1.25x the screen width with its centre 1.95 units above the band's bottom, so
the upper part including the outer executive ring fills a 52vh band (360 to 480px) and the selected node lands at the bottom
edge; glyph scale 0.9. Ring labels hidden on phones (capsules still outline the groups); sector
labels use short names (`sectorsShort` messages) and ride the rim 18px inside the band. Touch: first tap previews (edges light up, chip at the
band's bottom names the node and its holder, "Tap to open"), second tap or tapping the chip opens
the page; tapping the canvas clears the preview; no hover tooltips on touch. Legend is an icon
button in the header row opening a full-width panel under the header; the mobile toolbar carries
the tap hint, language and theme only (no Graph / Power map switch until the power map exists).
Stat tiles wrap 2+1 below 640px.

## Mobile density mode (2026-09-21, replaces the earlier phone framing)

Structural alternatives (sector drill-down lists, neighbourhood diagram) were mocked
(`docs/reference/mobile-mocks/`) and rejected: the user wants CivLab's phone view, which keeps
the wheel and changes only density. Implemented: on phones the wheel is 1.5x the screen width
with its centre near the top of a 50vh band (the top sectors are cropped by the header, the
executive fills the band); glyphs at 0.5x with 1px strokes; badges hidden (top offices keep
their own slots); capsules replaced by faint dotted rulers; ring labels hidden; short sector
names on the outer rim at 9.5px; the seal at 0.5 units; selected glyph grows 1.7x; every node
has an invisible 28px hit circle. The rotation is recomputed when the layout mode changes after
hydration (rotation key includes mobile/desktop). Inner-ring nodes rotated to 6 o'clock sit
mid-band with their children arcs below, as in CivLab. SVG node groups carry `data-id` for
probing. Verified with device emulation at 390x760 in both languages; desktop unchanged.

Clipping fix (2026-09-21): the rotating layer is a square SVG the size of the wheel's diameter,
centred on the wheel with `overflow: visible`, so its own edges never cut the territories; the
band's overflow crops with fixed edges. Before this, on phones the SVG was the band's size and its
rotated rectangle clipped the wedges into straight-edged shapes.

Edge taps on phones (2026-09-21): edges carry a 26px touch stroke on phones (12px hover stroke on
desktop). Tapping an edge highlights it with its verb badge and shows a relationship chip at the
band's bottom (family, "A verb B", seats, plain-language explanation, citation) with a "See in
connections below" action that scrolls to and flashes that row in the entity page's list (rows
carry `id="edge-{id}"`). Tapping a node clears an edge preview and vice versa; tapping the canvas
clears both. The desktop keeps hover tooltips.

Drag-to-rotate on phones (2026-09-21): a one-finger drag on the band turns the wheel by the angle
swept around its centre (pointer events, touch only; mouse unaffected), with no CSS transition
while dragging and a momentum glide on release (velocity decays 8% per frame). A movement under
about 8px is a tap; after a drag the synthetic click is suppressed so nothing gets selected or
cleared. The band sets `touch-action: pan-y`, so vertical swipes still scroll the page while
mostly-horizontal or arcing drags rotate. Edges and labels hide while dragging (the `turning`
flag) and reattach on release. The next selection tweens from wherever the wheel was left.

## Parliament seats (2026-09-22)

The 128 seats of Law 44/2017 Annex 1 are position nodes (`kind: seat`) on Parliament, keyed
`lb-seat-{minor}-{confession}-{ordinal}`; the ordinal ranks seats of one confession in a minor
district by the holder's 2022 preferential votes, so slugs stay stable across re-seeds. Data:
`api/database/seeders/data/seats/roster-2022.json` (128 members with district, confession, list,
votes, 2022 bloc and party, Arabic and English names, variants) built from the English and Arabic
Wikipedia member lists, reconciled seat by seat against the Annex 1 table in research Part 01 §5.2
(one correction: Fadi Karam holds a Koura Greek Orthodox seat, not Maronite as listed). Seeded by
`seats.php`: 128 seat positions (confession basis `constitution`, appointing authority the
electorate, Law 44/2017), 97 new persons (33 seat holders already existed as committee chairs,
rapporteurs, Speaker, Deputy Speaker and keep their slugs), tenures from the 17 May 2022
proclamation. History encoded: the Constitutional Council recount of 24 November 2022 (Rami Finge
→ Faisal Karami, Firas Salloum → Haidar Nasser, `end_reason: annulment`) and the West Bekaa Greek
Orthodox vacancy since Ghassan Skaff's death on 13 December 2025 (`vacant`, reason `death`). The
six Article 112 expatriate seats exist as `never_constituted` positions, not graph nodes, so the
API can represent the 134-seat contingency without drawing it. District names live in
`api/config/districts.php` and are attached to seat nodes by the exporter (`seat.majorName`,
`seat.minorName`); the node endpoint and the static `nodeDetailFrom` carry `seat` and `confession`
on connected entries so the chamber's page can group holders by district.

Graph: seats fill the PARLIAMENT pill (radius 2.1, phone 1.8) in concentric rows, CivLab's
congress pill: ordered by district (`groupOrder` in the descriptor, north to south then Bekaa),
column-major so each district is a contiguous run, with the fewest rows that fit the sector
(five on a desktop canvas). Seat glyphs are 6px (3px on phones); a selected seat grows 2x and a
dotted line ties it to Parliament instead of the body fan. The committees pill moved out to 2.95
(phone 2.6) to clear the seat rows. Pages: a seat shows district, confession, holder with party
and bloc, and "Seat in Parliament"; Parliament's page has a "Seats by district" card (holders
grouped by major district, minor district and confession per card) and its structural edges to
seats are not listed under "Who is connected". Seats are a legend kind.

Phones: seat glyphs sit about 5px apart, far below a usable tap target, so the seat block carries
one transparent hit area instead of per-seat circles; a tap on it resolves to the nearest seat
centre (measured in the wheel's frame, so it holds mid-drag) and then follows the usual
first-tap-previews, second-tap-opens flow. The turned geometry for the static layer is a rotation
of the base layout (`rotateLayout`) rather than a recomputation on every drag frame; a side effect
is that edges to head badges now meet the badge where it is drawn, since the badge offset turns
with the wheel.

Known gaps: parties and blocs are English strings from the 2022 roster (no Arabic names, no
dated bloc memberships yet); portraits absent; the roster's secondary source (Wikipedia) should be
replaced by the Interior Ministry's results PDF when the elections.gov.lb SPA is scraped.
