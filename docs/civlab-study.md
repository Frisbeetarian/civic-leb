# Study: graph.civlab.org/us (CivLab "US Gov Graph")

Studied on 2026-09-18 by reading the live site's server-rendered HTML, the embedded
Next.js data payload (`__NEXT_DATA__`), the CSS bundle, and the client app bundle.
A compact excerpt of the real data shapes is in `reference/us-graph-sample.json`.

## 1. What it is

CivLab ("The Civilization Lab", civlab.org) builds "a complete data model" of a
government: every entity, every position, the people holding them, and the legal
relationships between them, rendered as an interactive graph. Their tagline on the
page: "We cannot govern systems we don't understand."

They run two graphs on one codebase, selected by URL slug:

| Slug | Label | Layout | Features on |
|---|---|---|---|
| `/sf` | SF Gov (City & County of San Francisco) | `sf-rings` | budget, news, articles, article search, meetings, topics, overview |
| `/us` | US Gov (federal) | `us-sectors` | news, articles, changes feed, power map |

There is also `/request` ("Where should we map next?") that collects requests for new
governments, so a Lebanon graph is exactly the kind of thing they built the platform
to host. Building our own is still the right call if we want control over data,
language (Arabic/French/English) and the Lebanon-specific model (see `lebanon-plan.md`).

## 2. Pages and routes

Next.js pages router. Routes from the build manifest:

```
/[gov]                         home: news, power map, changes, overview, about + the graph
/[gov]/elected/[slug]          e.g. /us/elected/us-the-house-of-representatives (435 members list)
/[gov]/departments/[slug]      e.g. /us/departments/us-department-of-justice
/[gov]/dept-heads/[slug]       e.g. /us/dept-heads/us-attorney-general (the position, not the person)
/[gov]/commissions/[slug]
/[gov]/advisories/[slug]
/[gov]/topics, /[gov]/topics/[slug]   (SF only)
/request
```

Every page is fully server-rendered with the **entire graph** in `pageProps.data`
(2 MB HTML for US). Entity pages add `department | elected | commission | advisory |
deptHead`, a `selection`, a `breadcrumb` (sector name), `articles` (up to 10 tagged
news items) and `meetings` (SF only). The graph panel stays mounted while the
right-hand entity panel changes.

## 3. Home page layout (US)

Left/top column, in order:

1. **Header**: `CivLab / US Gov` breadcrumb, government switcher, search box,
   light/dark toggle. Search hint text: "Try simplifying your query or search by an
   agency's common name (FBI, IRS, NASA)" (aliases are searchable).
2. **Latest News**: 4 one-sentence summaries, each with inline links to graph
   entities (`<gov_entities='us-department-of-justice'>Justice Department</gov_entities>`
   markup in the data) and a numbered source citation.
3. **Who's in the news**: portrait strip of the top people from the power map.
4. **Power map** (view toggle next to "Graph"): people ranked by news mentions over
   a 90-day window; each has `total`, `recent`, a 12-week sparkline (`weeks[]`), a
   `heat` score, party, job, and `latest` headline.
5. **Latest Changes** with 7D / 30D / 90D filter: "Our agents monitor official sources
   to track every appointment, departure, and structural change in the graph."
   Stat tiles: Seats vacant (6), Acting officials (86), Last change (1d ago).
   A timeline histogram of changes in the window, then a list of change cards:
   `Appointed | Sep 16 | position | Out: predecessor | In: person`, or
   `Departure | ... | In: Seat now vacant`.
6. **Overview**: counts "By Type" (Elected offices 4, Members of Congress 535,
   Departments & agencies 329, Military services 6, Commissions 46, Advisory bodies
   14, Courts 19, Government corporations 18, Quasi-official bodies 45) and
   "By Branch" (Legislative 19, Executive 343, Judicial 17, Independent 103;
   "482 organizations in total, 339 of them sub-agencies").
7. **About** + footer: "Built by CivLab for you", Email / Twitter / Substack.

Right side: the graph canvas with a **Legend** panel (toggle node kinds on/off)
and a Graph / Power map view switch.

## 4. Entity page layout

Breadcrumb `CivLab / US Gov / Executive`, name, long description (with legal basis
inline), buttons **Legal Source** and **Official Website**, seat count, the people
holding the position(s) with portrait and "Appointed 2026" / "Elected", then tabs:

- **News**: tagged articles (title, date, excerpt, domain, "Load more").
- **Who's connected?**: a local sub-graph of the entity and its neighbours.
- **Budget** (SF departments only).

Elected chambers also render a full member roster (`members[]`: position, state,
district, seat class, holder, vacant, since, image, party).

## 5. Data model (exact, from the payload)

### 5.1 Node

```jsonc
{
  "id": "us-department-of-justice",       // slug, prefixed with gov slug
  "type": "department",                   // constituency | elected | department | dept_head | commission | advisory
  "subtype": "department",                // department | court | adjudicative_body | regulatory_body |
                                          // government_corporation | quasi_official | military_service |
                                          // advisory_body | legislature | chamber | governing_board | series
  "sector": "executive",                  // legislative | executive | judicial | independent
  "name": "...", "description": "...",    // description embeds the legal basis
  "aliases": ["DOJ", "Justice Department"],
  "legalSourceUrl": "...", "officialUrl": "...",
  "parent": "us-congress", "level": 1,    // hierarchy (349 of 952 nodes have a parent)
  "children": ["us-federal-bureau-of-investigation", ...],
  "head": "us-attorney-general",          // dept -> its head position node
  "headOf": "us-department-of-justice",   // head position -> its dept
  "seatsCount": 5,                        // for multi-seat bodies (commissions, chambers)
  "people": { "type": "people", "people": [ Person ] }   // or { "type": "count", "count": 435 } for big chambers
  "employeeCount": { "actual": null, "budget": null },
  "departmentCode": null,
  "topicsWithRelevance": [],
  "featuredPersonImageUrl": "/gov_group_pictures/us-congress.jpg",
  "edges": ["24f6d703", ...],             // edge ids touching this node
  "connectedNodes": ["us-attorney-general", ...]
}
```

US node counts: dept_head 460, department 426, commission 46, advisory 14, elected 5,
constituency 1 (952 total). Positions (dept_head) are nodes; people are *not* nodes,
they hang off positions.

### 5.2 Person / tenure (inside `node.people.people[]`)

```jsonc
{ "id": "us-todd-blanche", "name": "Todd Blanche",
  "positionId": "us-attorney-general", "positionName": "Attorney General",
  "type": "appointed",                    // appointed | elected  (changes feed also has "acting")
  "startedAt": "2026-08-08", "imageUrl": "https://upload.wikimedia.org/...", "party": null }
```

Vacant seats appear as a person entry whose `name` is the seat name.

### 5.3 Edge

```jsonc
{ "id": "20425240", "type": "appoints", "fromId": "us-speaker-of-the-house",
  "toId": "us-institute-of-...", "seatsAppointed": 2,
  "metadata": { "basis": "statute", "cite": "20 U.S.C. 4412(a)(1)(B)(i)",
                "note": "\"2 Members ... appointed by the Speaker ...\"", "source": "20260908_phase_d3_seats" } }
```

Edge types and counts (US): appoints 565, dept_head 457, confirms 260, ex_officio 99,
elects 20, oversees 12, advises 11, office 5, administers 4. SF adds heavy use of
`advises` and `oversees`. Every edge carries a citation to the legal instrument that
creates the relationship. This provenance is the core of the product.

### 5.4 Home-page feeds

- `news[]`: `{ id, summary (with gov_entities markup), url, publication }`
- `changes`: `{ generatedAt, stats: { vacantSeats, actingOfficials, lastChangeDate },
  items[]: { kind: "personnel", id, date, personName, positionId, positionName, groupId,
  entryMode: appointed|acting|elected, departure: bool, predecessorName, sourceUrl } }`
- `powerMap`: `{ windowDays: 90, since, computedAt, articleCount: 964,
  sources: ["NPR Politics","Government Executive","Federal News Network"],
  people[]: { id, name, job, positionId, party, nodeId, bodyNodeId, imageUrl, rank,
  total, recent, weeks[12], heat, latest: { at, date, headline, source, url } } }`
- `articles[]` (entity pages): scraped full articles `{ id, url, date, slug, title,
  author, content (HTML), excerpt, categories, titleImage }` from a Supabase-style DB.
- `satellites[]`: node ids to place on the outer ring regardless of type.

## 6. Rendering and layout

- **Stack**: Next.js (pages router) + React, Tailwind v4 (oklch tokens), Radix UI,
  Inter font, Sentry, BProgress route progress bar, deployed on Vercel. Graph is
  **SVG drawn with d3** (d3-selection/d3-shape; markers for arrowheads). No WebGL,
  no force simulation: positions are computed deterministically.
- **Layout config is data, not code**. `us-sectors` config:
  - mode `sectors`: three angular sectors LEGISLATIVE (min 38°), EXECUTIVE (120°),
    JUDICIAL (56°), 5° gaps; `independent` is aliased into executive.
  - rings by bucket: elected (spacing 1.4, sized by seats), then commission /
    advisory / department (spacing 2.85, sized by number of children).
  - `depthMode`: sub-agencies are placed deeper than their parents.
  - special shapes: a **congress pill** (arc spanning 96°), a **cabinet pill**
    (the 15 executive departments on their own arc labelled CABINET), an apex node
    (Supreme Court) and "functional rings" OVERSIGHT / ADMINISTRATION in the judicial
    sector.
  - the constituency ("People of the United States") sits at the centre.
  - `sf-rings` is a simpler concentric ring layout: elected inner, then commissions,
    advisories, departments, sized by employee counts.
- **Node glyphs** (legend): elected = circle, department = rounded square,
  dept_head = person-on-square, commission = diamond, advisory = pentagon,
  court = triangle, corporation, quasi-official, constituency. Colour comes from the
  branch (`--branch-legislative #E4573D`, `--branch-executive #7A7AD0`,
  `--branch-judicial #AC7F14`) with type colours as fallback (elected #F2686F,
  commission #C15EF2, department #826DC8, advisory #F25EEF, constituency #F27836).
  Party colours: Democrat #084AB4, Republican #D1343B, Independent #826DC8.
- **Edge glyphs**: appoints = filled single arrowhead; elects = double arrowhead;
  confirms = hollow arrowhead; advises/oversees/administers = curved dashed line with
  open chevrons; dept_head/office/ex_officio = dashed, no arrow. Clicking a node
  dims everything not connected (connectedAlpha 0.35).
- **Theme**: light canvas #ECEAE4 / dark #161310, warm stone greys, brand orange
  #FD8055, `data-theme` attribute, per-gov default (US defaults to dark).

## 7. How the data is produced (inferred)

- The graph itself is curated: node descriptions read like edited encyclopaedia
  entries with legal citations; edge metadata `source` values like
  `complete-government scaffold` and `20260908_phase_d3_seats` show a batch pipeline
  (a scaffold pass, then phased enrichment sweeps stored with dates).
- Changes are produced by agents doing periodic sweeps (`20260916_acting_sweep_396`)
  of official sources, writing tenure start/end rows with a source id.
- News: articles scraped from 3 outlets, stored with full content, summarised to one
  sentence by an LLM, entity-linked to node ids, and counted per person for the
  power map.
- Images are hot-linked from Wikimedia Commons and the `unitedstates/images` repo.

## 8. What makes it good (keep these)

1. Positions are first-class and people are tenures, so vacancies, acting officials
   and turnover fall out naturally.
2. Every relationship cites its legal source.
3. A deterministic, meaningful layout (branches as sectors, rings by kind) instead
   of a force-directed hairball.
4. Live layers on top of the static structure: news, power map, changes feed.
5. Aliases make search work the way people actually talk ("FBI", "the House").
6. The same codebase serves multiple governments via config + layout descriptors.

## 9. Gaps we can improve on for Lebanon

- No multilingual support (Lebanon needs Arabic, French, English).
- No notion of confessional allocation of posts, which is central to how Lebanon's
  state is actually structured.
- Caretaker / vacant status is a stat, not a first-class state; in Lebanon it is
  the norm for long stretches (presidential vacuum 2022–2025, caretaker cabinets).
- No sub-national layer for the US; Lebanon's governorates, districts and
  municipalities are a natural second phase.

## 10. How a node gets focused on selection (studied 2026-09-19)

Read from the `us-sectors` renderer in the app bundle and confirmed with screenshots of
the Supreme Court, the FCC, the FBI and the President selected
(`reference/civlab-selection-*.png`). Selection is a URL change (`/us/departments/<slug>`);
the graph component stays mounted and re-lays itself out with transitions.

### 10.1 The whole graph rotates so the selected node sits at the bottom centre

- Every node has a resting angle from the sector layout. On selection the renderer
  computes `rotate = π/2 − angle(selected)` and applies it to every sector range, so the
  selected node lands at 6 o'clock, directly below the centre seal and just above the
  label chip. Selecting the Supreme Court spins the wheel until the judicial sector is at
  the bottom; selecting the Senate puts the legislative sector at the bottom.
- The spin is animated over 750 ms with a d3 `attrTween` on the ring group's
  `rotate(...)` and on the territory band paths (`d` attribute tween), so the sectors
  visibly slide around rather than jump. The previous rotation is kept in a ref and the
  delta (`frameSpin`) is normalised to the shortest direction.
- For chambers and legislature nodes the target angle is the middle of the congress pill,
  so Congress as a whole comes to the bottom.

### 10.2 The selected sector widens and neighbours make room

- Sector angular ranges are recomputed with the selected node's sector given a minimum
  half-span (`legislativeMinHalfSpan` and equivalents), so the focused sector grows and the
  other two shrink; the seams between territories animate to the new positions.
- Inside the ring, angles are spread evenly (`to`), then compressed away from the focus:
  `ts(angles, selectedIndex, 0.16)` keeps the selected node where it is and pulls its
  neighbours on both sides so there is 0.16 rad of clear space around it.
- In depth mode nodes alternate a ±13 px radial stagger (`rowOffset`) to avoid label and
  glyph collisions; the selected node's stagger is forced to 0 so it sits exactly on its
  ring.

### 10.3 The node itself grows and its family is revealed

- Sizes: a selected department is drawn at 1.3× its size, a commission at 1.5×, an
  advisory body at a fixed 21 px (`selectedSizeOverride`); sizes lerp over the transition.
- Sub-agencies of the selected body, normally shown only as dot grids ("subdivisions")
  on the outer band, are expanded into a row of small glyphs on the rim, fanned out with
  dotted lines from the parent (the DOJ view shows FBI, DEA, Bureau of Prisons and the
  rest as a row of squares). When a sub-agency itself is selected (FBI), it is drawn as a
  full glyph on the rim, its parent (DOJ) is enlarged on the cabinet ring, and the parent's
  other children stay as the row.
- Head positions (dept_head) are hidden at rest in depth mode (`hideIdleDeptHeads`) and
  only appear for the selected body and its neighbours.

### 10.4 Edges and dimming

- Only the selected node's edges are drawn. Each is coloured by the branch of its source
  node and carries the arrowhead for its type (single filled for appoints, double for
  elects, hollow for confirms, chevrons for oversight-type relations). Edge endpoints are
  tweened with the nodes over 750 ms so lines stay attached during the spin.
- Everything not connected drops to `connectedAlpha` (0.35 light, 0.55 dark); connected
  nodes stay at full alpha. There are no idle edges in light mode and 0.26 alpha in dark.
- The President view shows the scale this handles: about a hundred appointment edges fan
  out from the bottom-centre node across all three sectors.

### 10.5 Chrome that follows the selection

- A label chip appears at the bottom centre of the canvas with the node name in its
  branch colour on a translucent card, and the breadcrumb in the header card gains the
  branch name.
- Prev/next arrows in the top-right step through nodes of the same type
  (`onNext`, `onPrevious`, `onNextDepartment`), each step re-running the rotation.
- The left column swaps to the entity cards (title, description, legal source, official
  site, holders, "Part of" chip, tabs) while the graph keeps its state.
- Hovering any node shows a tooltip with its name; the label chip and tooltip are the
  only places names appear on the canvas.

### 10.6 What this means for civicleb

Our current graph selects without moving: the node is outlined, its edges light up, and a
chip appears, but the wheel stays put and the node may be anywhere on the circle. To act
like CivLab we would add: rotation to bottom centre with a 750 ms tween on the sector
group and territories; sector widening for the focused sector; neighbour compression
(0.16 rad) and zero stagger for the selected node; 1.3× to 1.5× growth of the selected
glyph; children revealed as a fanned row on the rim with dotted parent lines; head
positions hidden at rest and shown only for the focused family; prev/next stepping by
type. Everything else (edges by branch, connected alpha, chip, breadcrumb, entity cards)
is already in place.
