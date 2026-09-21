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

Wheel fitted to 1.25x the screen width with its centre 1.55 units above the band's bottom, so
the upper two-thirds fill a 46vh band (320 to 420px) and the selected node lands at the bottom
edge; glyph scale 0.9. Ring labels hidden on phones (capsules still outline the groups); sector
labels ride the rim just inside the band. Touch: first tap previews (edges light up, chip at the
band's bottom names the node and its holder, "Tap to open"), second tap or tapping the chip opens
the page; tapping the canvas clears the preview; no hover tooltips on touch. Legend is an icon
button in the header row opening a full-width panel under the header; the mobile toolbar carries
the tap hint, language and theme only (no Graph / Power map switch until the power map exists).
Stat tiles wrap 2+1 below 640px.
