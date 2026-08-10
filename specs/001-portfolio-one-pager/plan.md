# Implementation Plan: Portfólio One-Page Pedro Ribeiro

**Branch**: `001-portfolio-one-pager` | **Date**: 2026-08-10 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `/specs/001-portfolio-one-pager/spec.md`

## Summary

Build a recruiter-facing one-pager for Pedro Ribeiro in two constitution-mandated phases: (1) a visually complete static front in `frontend/` (HTML/CSS/JS + Tailwind via Vite) with hardcoded copy, persistent in-page nav, typographic/atmospheric hero, Escritos omitted, and Now mocked or omitted; (2) after visual acceptance, Dockerized WordPress + custom Underscores theme that ports markup, wires owner-authored ACF JSON (no invented field keys in this plan), live Now via `wp_remote_get` + transients (Last.fm + Backloggd), and conditional Escritos with minimal editorial `single`/`archive`. WordPress core is never committed to Git.

## Technical Context

**Language/Version**: PHP 8.2+ (WordPress theme, phase 2); HTML5 / modern CSS / ES modules (phase 1–2); Node.js LTS for Vite

**Primary Dependencies**: Tailwind CSS v4 (`@theme` tokens), Vite; WordPress (Docker image), Advanced Custom Fields (plugin), Underscores (_s) starter theme

**Storage**: Phase 1 — none (static files). Phase 2 — MySQL (WP content); ACF field groups JSON synced into the theme by the owner; API secrets in env / `wp-config` (not Git)

**Testing**: Manual visual acceptance + quickstart scenarios; smoke checks for Now degrade and Escritos empty-state; no automated test suite required for v1

**Target Platform**: Modern evergreen browsers (mobile + desktop); local Docker for WP; production WP host without phpMyAdmin

**Project Type**: Personal marketing site (static front → classic WordPress theme)

**Performance Goals**: First viewport usable quickly on typical broadband; Now fetches MUST use short timeouts + transients so page render never depends on live API latency beyond a short budget

**Constraints**: Front-first gate; no headless / Filament / contact form / dark-mode default; no career-level labels; secrets out of Git; phpMyAdmin local-only; do not invent ACF field keys—owner creates groups in admin then syncs JSON; WP core not in repo

**Scale/Scope**: One home one-pager + minimal post templates; 2–4 project cases; two Now sources (current item only each)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Gate | Status | Notes |
|------|--------|-------|
| I. Conteúdo no CMS | Pass | Phase 1 hardcoded OK; phase 2 ACF after owner JSON sync; theme wires `get_field` only |
| II. Tema como produto | Pass | `_s` theme, `esc_*`, partials per section, ACF JSON versioned once owner exports |
| III. Front honesto | Pass | Real HTML/CSS/JS; Tailwind + Vite; no page builders |
| IV. Design editorial técnico | Pass | Brand-first typographic hero allowed; no hero cards; banned AI looks; no dark default |
| V. One-pager focado | Pass | Section order + skills-in-cases + conditional Escritos + contact links only |
| VI. Integrações resilientes | Pass | `wp_remote_get` + transients; silent degrade; keys in env/wp-config |
| VII. Dev reproduzível | Pass | Docker Compose documented; secrets gitignored; phpMyAdmin local profile only |
| VIII. Simplicidade / YAGNI | Pass | No headless/parallel CMS; Escritos slot future-ready without mandatory posts |
| Development Order | Pass | Phase 1 `frontend/` before any WP/CMS work |

**Post-design re-check**: Artifacts below keep ACF as owner-authored (no invented keys), keep WP core out of Git, and preserve front-first structure — gates still Pass.

## Project Structure

### Documentation (this feature)

```text
specs/001-portfolio-one-pager/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   ├── ui-home.md
│   └── now-integrations.md
└── tasks.md             # Created later by /speckit-tasks
```

### Source Code (repository root)

```text
frontend/                      # Phase 1 — static one-pager
├── index.html
├── src/
│   ├── styles/
│   │   └── main.css           # Tailwind + @theme tokens
│   ├── js/
│   │   └── main.js            # Nav, motion, optional Now mock
│   └── partials/              # Optional HTML includes if used
├── public/                    # Static assets (CV PDF stub, images)
├── package.json
└── vite.config.js

docker/                        # Phase 2 — local stack (no WP core in Git)
├── docker-compose.yml         # wordpress + mysql + phpmyadmin (local)
├── .env.example               # DB, ports, LASTFM_API_KEY placeholders
└── README.md                  # Or docs in quickstart — setup notes

wp-content/                    # Mounted into container; only theme (+ later ACF JSON)
└── themes/
    └── pedro-ribeiro/         # Custom _s-based theme
        ├── style.css
        ├── functions.php
        ├── front-page.php
        ├── header.php / footer.php
        ├── single.php
        ├── archive.php
        ├── template-parts/
        │   ├── hero.php
        │   ├── sobre.php
        │   ├── projetos.php
        │   ├── experiencia.php
        │   ├── now.php
        │   ├── escritos.php
        │   └── contato.php
        ├── inc/
        │   ├── now-lastfm.php
        │   ├── now-backloggd.php
        │   └── acf.php          # JSON load path; wiring after owner sync
        ├── acf-json/            # Owner-exported field groups (empty until sync)
        ├── src/                 # Theme Vite entry (ported from frontend/)
        ├── package.json
        └── vite.config.js

.gitignore                     # node_modules, .env, WP core volumes, uploads, vendor noise
```

**Structure Decision**: Two rooted areas — `frontend/` for phase-1 visual delivery, then `wp-content/themes/pedro-ribeiro/` + `docker/` for phase 2. WordPress core and MySQL data live only in Docker volumes (not committed). ACF schema arrives via owner export into `acf-json/`; plan/tasks reference conceptual entities only until that sync exists.

## Complexity Tracking

> No constitution violations requiring justification.

## Phase Plan

### Phase 1 — Front estático (`frontend/`)

1. Scaffold Vite + Tailwind with design tokens (`@theme`: cool off-white, single petrol-blue accent, expressive fonts).
2. Build one composition: persistent compact nav + sections Hero → Sobre → Projetos → Experiência/Formação → Contato (Escritos omitted; Now omitted or clearly mocked/non-blocking).
3. Hardcode Portuguese copy; 2–4 stacked cases (Correnem + Index); optional outbound links only when URL present; contact links without form.
4. Light motion: section entrance, case hover; typographic atmospheric hero (no photo required).
5. Visual acceptance gate (“visual stability”) before any WordPress work.

### Phase 2 — WordPress

1. Docker Compose: WordPress + MySQL + phpMyAdmin (compose profile/service tagged local-only).
2. Scaffold theme from Underscores; port markup into `template-parts/*`; Vite builds theme assets.
3. **ACF**: Owner creates field groups in WP admin covering conceptual entities (see `data-model.md`). Owner syncs JSON into `acf-json/`. Theme code then wires `get_field` / loops — **do not invent field keys in plan/tasks ahead of that JSON**.
4. Now: Last.fm + Backloggd fetchers (`wp_remote_get`, timeouts, transients); render current item only; partial/empty → omit parts or whole section.
5. Escritos: query up to 3 recent posts on front-page partial; hide section if zero; ship minimal editorial `single.php` / `archive.php`.
6. Secrets via env / `wp-config` constants; Never in ACF content fields.
7. Document local up and production note (no phpMyAdmin).

## Design Artifacts

| Artifact | Role |
|----------|------|
| [research.md](./research.md) | Stack, ACF workflow, Now APIs, Git boundaries |
| [data-model.md](./data-model.md) | Conceptual entities (no invented ACF keys) |
| [contracts/ui-home.md](./contracts/ui-home.md) | Home section/UI contract |
| [contracts/now-integrations.md](./contracts/now-integrations.md) | Now source contracts + degrade rules |
| [quickstart.md](./quickstart.md) | Validation scenarios phase 1 and 2 |
