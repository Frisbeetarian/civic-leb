# civicleb

A complete data model of the Lebanese state: bodies, positions, the people who hold
them, and the legal relationships between them, each cited to its source. Modelled on
CivLab's US Gov Graph. Arabic first, English second.

Code MIT, data CC BY. Working name; public name and domain to be decided before launch.

## Layout

```
api/    Laravel 13 + Filament 5: Postgres is the source of truth, the admin panel is the
        curation and review tool, and /api/lb/* is the public read-only JSON API.
web/    Next.js 16 + React + Tailwind + d3: the public site (Arabic RTL and English).
data/   Nightly JSON/CSV dumps of the published graph (to be added).
docs/   Study of CivLab, decisions, research on Lebanese institutions, schema.
```

Read `docs/decisions.md` first. It records every product and architecture decision and
the research amendments that followed.

## Local development

Requirements: PHP 8.4, Composer, Node 20+, pnpm, Docker.

```sh
docker compose up -d                    # Postgres on 54329, Redis on 63799

cd api
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate
php artisan make:filament-user           # your reviewer login for /admin
CIVICLEB_SEED_PUBLISH=1 php artisan db:seed --class=CoreGraphSeeder   # milestone-1 core, published
php artisan serve                        # http://127.0.0.1:8000  (admin at /admin)

cd ../web
pnpm install
cp .env.example .env.local               # API_URL=http://127.0.0.1:8000
pnpm dev                                 # http://localhost:3000/ar  and  /en
```

Without `CIVICLEB_SEED_PUBLISH=1` the seed lands as drafts and nothing appears on the
public site until it is reviewed and published in Filament, which is the intended flow.

## Tests

```sh
cd api && php artisan test     # Pest, against a Postgres test database (civicleb_test)
cd web && pnpm lint && pnpm exec tsc --noEmit && pnpm build
```

## Data model in one paragraph

`bodies` are organisations (constituency, elected, department, commission, advisory)
with a Lebanon-specific subtype, legal form, sector and status. `positions` are offices
attached to a body (heads, ministers, seats, board members). `persons` hold positions
through `tenures`, which carry a status (substantive, acting, assigned, caretaker,
vacant…), three dates (decision, instrument, effective) and an appointing instrument.
`edges` relate bodies and positions (elects, appoints, confirms, tutelage, owns,
inspects, prosecutes, reviews, commands…). `legal_instruments` are versioned
(in force, annulled by, superseded by). Every curated row has `sources` and a review
state; the API and the graph export read published rows only. See `docs/schema.md`.

## Public API

| Endpoint | Returns |
|---|---|
| `GET /api/lb/graph` | the published snapshot: nodes, edges, counts, layout descriptor |
| `GET /api/lb/nodes/{slug}` | one node with its edges and connected nodes |
| `GET /api/lb/layout` | the `lb-sectors` layout descriptor |
| `GET /api/lb/graph/version` | cache version, changes on every publish |

## Status

Milestone 1 (September 2026): schema, Filament review workflow, graph export, public
API, and the constitutional core (97 nodes, 74 edges) rendered on the four-sector
layout in Arabic and English. Screenshots in `docs/reference/milestone-1-*.png`.
Next: the full executive inventory, Parliament's 128 seats, the judiciary and independent
bodies, then the changes-feed sweep and deployment (Laravel Cloud + Cloudflare).
