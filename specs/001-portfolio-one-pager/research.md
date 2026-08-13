# Research: Portfólio One-Page Pedro Ribeiro

**Feature**: `001-portfolio-one-pager` | **Date**: 2026-08-10 (R4/R5/R6/R11 updated 2026-08-13)

## R1 — Phase split & repository layout

**Decision**: Deliver phase 1 as a standalone Vite app under `frontend/`; phase 2 adds `docker/` + `wp-content/themes/pedro-ribeiro/` without committing WordPress core.

**Rationale**: Constitution Development Order requires visual stability before CMS. Separating `frontend/` keeps the first PR reviewable as pure HTML/CSS/JS. Docker volumes hold WP core and DB data; Git tracks only theme, compose files, env examples, and docs.

**Alternatives considered**:
- Theme-only from day one — violates front-first gate and slows visual iteration.
- Monorepo with full WP tree in Git — bloated, merge-hostile, contradicts “não commitar WordPress core”.

## R2 — Tailwind + Vite (both phases)

**Decision**: Tailwind v4 with `@theme` design tokens; Vite for CSS/JS bundling in `frontend/` and later in the theme.

**Rationale**: Constitution mandates honest front + Tailwind learning + Vite in theme. Tokens encode cool off-white, single petrol-blue accent, and type scales once, then port to the theme entry.

**Alternatives considered**:
- CDN Tailwind play CDN — fine for spike, weak for token discipline and production purge.
- Sass-only / no utility layer — slower iteration against the agreed learning goal.

## R3 — ACF ownership & JSON sync (no invented keys)

**Decision**: Owner creates ACF field groups manually in WP admin. Plan/data-model list **conceptual entities and attributes only**. After the owner exports/syncs JSON into `theme/acf-json/`, implementers wire `get_field` / repeaters using the **actual** keys from that JSON. Do not invent `field_xxxxx` or premature key names in plan/tasks.

**Rationale**: User constraint + reduces schema drift. Constitution still expects versioned ACF JSON in the repo once it exists—ownership of the first export stays with the owner.

**Alternatives considered**:
- Plan invents full ACF schema — rejected (user: “NÃO deve inventar o schema completo”).
- Hardcode all copy forever — violates Conteúdo no CMS after phase 2.

## R4 — Last.fm (Ouvindo)

**Decision**: Official Last.fm API `user.getRecentTracks` via `wp_remote_get`. Prefer the first track when `@attr.nowplaying === true`; otherwise fall back to the most recent scrobble. Omit Ouvindo only on failure or empty track list. Still one item (not a history list). Cache with a WordPress transient (suggested TTL ~5 minutes; tunable). API key from env/`wp-config` only. UI label always "Ouvindo".

**Rationale**: Clarification 2026-08-13 — portfolio should still show listening activity when idle; nowplaying remains preferred when available.

**Alternatives considered**:
- Nowplaying-only (omit when idle) — superseded 2026-08-13.
- Always show last scrobble even when nowplaying exists — rejected (prefer live nowplaying).
- Client-side fetch with exposed key — insecure.

## R5 — Backloggd (Última review)

**Decision**: No official API. Fetch the user’s public reviews page `https://www.backloggd.com/u/{username}/reviews/` with `wp_remote_get`, parse the **first** `.review-card` for game name (`title`), cover (`image_url`), review body (`review`), optional star rating from `.stars-top` width%, and optional review/game URL. Cache in a transient. On HTTP/parse failure, bot wall, or empty list → omit the block. UI label **"Última review"** (not “Jogando”). Truncate review text to ~100 characters + “…” in the theme UI. Aggregator may keep the key `gaming` (ACF `show_gaming`) with this expanded review shape. Username from ACF/config; no secret required.

**Rationale**: Clarification 2026-08-13 — currently-playing scrape is less useful/reliable than showing the latest public review. Constitution still allows unofficial HTML via `wp_remote_get` + silent degrade.

**Alternatives considered**:
- `/playing/` currently-playing scrape — superseded 2026-08-13.
- Third-party Go/Python scrapers as sidecar — extra runtime, violates YAGNI for v1.
- Skip Backloggd until official API — contradicts dual Now demos.
- Rename aggregator key to `review` — optional later; keep `gaming` for ACF continuity unless implement chooses rename.

**Risk**: Markup changes on Backloggd (or Anubis bot walls) can break the parser. Mitigate with short timeout, transient cache, and silent omit (already required).

## R6 — Now degrade UX

**Decision**: Per-source omit on failure/empty/hidden; show Now only if ≥1 displayable item; omit entire section if none. No error or “unavailable” copy. Listening labeled “Ouvindo”; Backloggd labeled “Última review”.

**Rationale**: Spec clarifications 2026-08-10 (partial omit) + 2026-08-13 (last-scrobble, hide toggles, latest review).

**Alternatives considered**: Always show headings with placeholders — rejected. Alternate “Última” label for idle Last.fm — rejected 2026-08-13. “Jogando” for Backloggd — superseded 2026-08-13.

## R11 — ACF Now hide toggles

**Decision**: Owner JSON fields named `show_listening` / `show_gaming` use admin labels “Esconder API…”. Treat checked/true as **hide** (skip fetch, that side `null`); unchecked/false as **expose** (default). Do not invent new ACF keys—map semantics from labels + clarify session.

**Rationale**: Clarification 2026-08-13; matches admin UX and avoids inverted enable logic.

**Alternatives considered**: Interpret field names as show-when-checked — rejected (contradicts labels and owner intent).

## R7 — Escritos

**Decision**: Phase 1 omit section. Phase 2: `WP_Query` / `get_posts` for up to 3 published posts on the home partial; if count === 0, do not render the section markup. Ship minimal editorial `single.php` and `archive.php` even with zero posts.

**Rationale**: Spec + constitution.

## R8 — Navigation & hero

**Decision**: Compact persistent in-page nav (anchors including Projetos + Contato) plus hero CTAs. Typographic/atmospheric full-bleed hero; photography optional and not a phase-1 gate.

**Rationale**: Spec clarifications.

## R9 — Secrets & Docker

**Decision**: `.env` / `wp-config` constants for `LASTFM_API_KEY` and DB credentials; `.env.example` committed; real `.env` gitignored. phpMyAdmin as a local-only Compose service/profile, never documented as production.

**Rationale**: Constitution VII.

## R10 — Testing strategy

**Decision**: Manual validation via `quickstart.md` scenarios (visual, nav, contact links, Now degrade, Escritos empty/full). No mandatory unit-test framework in v1.

**Rationale**: Marketing one-pager; acceptance is visual and behavioral. Automated suite would add scope without constitution mandate.

## Resolved clarifications

All Technical Context items are decided; no remaining `NEEDS CLARIFICATION` markers for planning.
