# Contributing

civicleb maps the institutions of the Lebanese state and the legal relationships
between them. Every claim in the graph is meant to carry a source. The most useful
contributions right now are corrections and verifications, not new features.

## Fix or verify a fact

1. Find the record in `api/database/seeders/data/` (`core.php` for the constitutional
   core and executive, `committees.php` for Parliament's standing committees).
2. Many tenures carry a note saying the date or decree number is UNVERIFIED. If you can
   cite the Official Gazette, a pcm.gov.lb decision, lp.gov.lb, or an official site,
   fix the value, remove the note, and add the URL to the record's `sources`.
3. Open a pull request with the source in the description. One fact per PR is ideal.

## Add an institution

Read `docs/decisions.md` (taxonomy, edge types, statuses) and skim
`docs/research/lebanon-public-institutions.md` for what is already known. Add the body,
its head position, its creating instrument and its tutelage or oversight edges, each with
a source URL. Keep names in Arabic and English; French is welcome.

## Run it locally

See `README.md`. The site can also run with no backend from the bundled snapshot:
`cd web && pnpm install && pnpm build:static && npx serve out`.

## Licence

Code is MIT; data is CC BY 4.0 (see `LICENSE`). By contributing you agree your
contribution is released under the same terms.
