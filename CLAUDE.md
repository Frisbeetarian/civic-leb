# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A cited data model of the Lebanese state (bodies, positions, people, tenures, legal edges), modelled on
CivLab's US Gov Graph. Arabic first, English second. Two apps in one repo:

- `api/` Laravel 13 + Filament 5. Postgres is the source of truth, Filament is the curation and review
  tool, `/api/lb/*` is the public read-only JSON API.
- `web/` Next.js 16 + React + Tailwind + d3. The public site, deployed as a static export on Cloudflare.

Read `docs/decisions.md` before changing the data model, taxonomy, layout, or scope. It records every
product and architecture decision (numbered Q1..Q29 plus research amendments) and wins over older
docs where they differ. `docs/schema.md` is the table-by-table reference.

## Commands

Local services (Postgres on 54329, Redis on 63799): `docker compose up -d`

API (`cd api`):

```sh
composer install && cp .env.example .env && php artisan key:generate && php artisan migrate
php artisan make:filament-user                                          # reviewer login for /admin
CIVICLEB_SEED_PUBLISH=1 php artisan db:seed --class=CoreGraphSeeder    # seed AND publish (local only)
php artisan serve                                                       # http://127.0.0.1:8000
php artisan test                                                        # Pest, needs Postgres db civicleb_test
php artisan test --filter=GraphApiTest                                  # one file
php artisan test --filter='exports only published rows'                 # one test by name
```

Without `CIVICLEB_SEED_PUBLISH=1` the seed lands as drafts and nothing reaches the API or site until
published in Filament. That is the intended production flow; the flag is a local shortcut.

Web (`cd web`, pnpm):

```sh
pnpm dev                                    # http://localhost:3000/ar and /en, needs API_URL in .env.local
pnpm lint && pnpm exec tsc --noEmit && pnpm build   # the full check; there are no frontend unit tests
pnpm snapshot                               # refresh data/lb-graph.json from the running API
pnpm build:static                           # STATIC_EXPORT=1: fully static site into out/ from the bundled snapshot
pnpm deploy:static                          # build:static + wrangler deploy to civ-leb.com
```

`web/AGENTS.md` (included via `web/CLAUDE.md`) is written by `next dev` and points at the Next.js docs
in `node_modules/next/dist/docs/`; this Next.js version differs from training data, read those docs
before touching routing, caching or config. `api/CLAUDE.md` is the generated Laravel Boost guideline
file; its setup steps (installer scripts, `composer require`) are for a fresh machine, not for
routine work in this repo.

## Architecture

### Data flow: Postgres -> review -> snapshot -> site

1. Every curated table (`bodies`, `positions`, `persons`, `tenures`, `edges`, `legal_instruments`)
   carries `review_state` (draft, reviewed, published) via the `HasReviewState` trait, and `sources`
   via `HasSources`. Filament's shared review actions live in `app/Filament/Support/Review.php`.
2. `App\Services\Graph\GraphExporter` builds one JSON snapshot of published rows only (an edge needs
   both ends published; tenures included only when open, i.e. `end_date` null). Node ids are slugs
   (`lb-...`). The snapshot is cached forever under `graph:lb:snapshot`; publishing or seeding calls
   `invalidate()`, which also bumps the version key that `/api/lb/graph/version` and the
   `X-Graph-Version` header expose.
3. `GraphController` serves the snapshot, a per-node view derived from it, and the layout descriptor,
   all with long CDN cache headers.
4. `web/src/lib/graph.ts` reads the same shape in two modes: live (`API_URL` set, revalidate 60s) or
   bundled (`web/data/lb-graph.json`, used by the static export). `nodeDetailFrom` mirrors the API's
   per-node response so both modes render identically. Keep the two in sync when the node shape changes.

### Seed data is the curated dataset

`api/database/seeders/data/core.php` (constitutional core and executive), `committees.php` and
`seats.php` (128 seats built from `seats/roster-2022.json` plus `config/districts.php`) are PHP arrays
merged by `CoreGraphSeeder`, which upserts by slug or natural key so re-seeding is idempotent.
Instruments and bodies are loaded in two passes so cross references (annulled_by, superseded_by,
parent) resolve. Facts marked UNVERIFIED in notes are awaiting an Official Gazette or official-site
source; fixing one means changing the value, removing the note and adding the URL to `sources`.

### Layout is data, rendered client side

`api/resources/layouts/lb-sectors.json` is the layout descriptor: electorate at the centre, four
angular sectors (legislative, executive, judicial, independent) with minimum angles, rings by node
kind, pills for Parliament (128 seats) and the Council of Ministers, bands for oversight and
security bodies. Per-node overrides live in `bodies.layout_hints`. The API only ships it;
`web/src/lib/layout.ts` (`computeLayout`) turns descriptor + snapshot into polar positions and
`GraphView.tsx` draws the wheel with d3 (rotation, hover and tap preview, mobile drag with momentum).
Node glyph sizes follow CivLab's scale and are set in `layout.ts`. Colours per sector and edge family
come from `web/src/lib/palette.ts` and CSS variables in `globals.css`.

### i18n

`next-intl` with locales `ar` (default, RTL) and `en`, always prefixed (`/ar`, `/en`), routing in
`web/src/i18n/`. UI strings live in `web/messages/{ar,en}.json`; add both when adding a key. Data
names are trilingual objects (`{ar, en, fr}`) resolved with `localized()` from `web/src/lib/types.ts`.
Enum values (sector, type, subtype, confession, edge type, statuses) are defined once in
`api/app/Enums/` and mirrored as string unions in `web/src/lib/types.ts`.

### Confession

Confessional allocation is shown per position with its legal basis (`ConfessionBasis`: constitution,
national pact, custom). It is a structural fact about the seat or office with sources, never a label
on a person. Keep that distinction in models, exporter and UI.

## Conventions worth knowing

- Slugs are the stable public ids and are `lb-` prefixed; never rename a published slug.
- Every curated row needs at least one source URL. Seeds without one are drafts by design.
- Tests run against a real Postgres database (`civicleb_test` on port 54329), not SQLite.
- Code is MIT, data is CC BY 4.0. Portraits are self-hosted with licence and attribution recorded.
