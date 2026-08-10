---
description: "Task list for Portfólio One-Page Pedro Ribeiro"
---

# Tasks: Portfólio One-Page Pedro Ribeiro

**Input**: Design documents from `/specs/001-portfolio-one-pager/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md, constitution v1.1.0

**Tests**: Manual only (quickstart scenarios). No automated TDD tasks.

**Organization**: Phases A–F map to separate implementation chats. Front-first gate blocks all WordPress work. ACF schema is owner-owned; code only wires after JSON sync.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no incomplete dependencies)
- **[Story]**: US1–US6 from spec.md (omitted on Setup / Foundational / Polish-only chores)
- Exact file paths required

## Path Conventions

- Phase B: `frontend/`
- Phase C+: `docker/`, `wp-content/themes/pedro-ribeiro/`

## Chat map

| Phase | Chat | Gate |
|-------|------|------|
| A — Setup | Chat 0 | — |
| B — Front estático | Chat 1 | Ends with **visual stability** acceptance |
| C — Docker + tema _s | Chat 2 | **Blocked until** Phase B gate (T020) |
| D — ACF wiring | Chat 3 | **Blocked until** owner JSON sync (T040) |
| E — Now live | Chat 4 | Needs C (+ D config fields if toggles live in ACF) |
| F — Polish / smoke | Chat 5 | After E (or after C if Now deferred) |

---

## Phase A: Setup repo / tooling

**Purpose**: Minimal repo hygiene and folder skeleton. No WordPress.

**Independent Test**: Repo has ignored secrets/build artifacts; empty target dirs exist; README points to specs/quickstart.

- [X] T001 Expand root `.gitignore` for `node_modules/`, `frontend/dist/`, `docker/.env`, WP uploads/cache noise, OS junk (keep WP core out of Git)
- [X] T002 [P] Create directory placeholders `frontend/`, `docker/`, `wp-content/themes/` per plan.md
- [X] T003 [P] Add short root `README.md` with project name, link to `specs/001-portfolio-one-pager/quickstart.md`, and note that WordPress core is Docker-only

**Checkpoint**: Setup done — start Phase B (Chat 1)

---

## Phase B: Front estático (PRIORIDADE — Chat 1)

**Purpose**: Visually complete one-pager in `frontend/`. Escritos omitted. Now omitted or clearly non-blocking mock. Hardcoded copy.

**Depends on**: Phase A

**CRITICAL**: No Docker / WP / ACF / live Now tasks until **T020** (visual stability) is checked.

### B0 — Scaffold (foundational for US1–US3)

- [X] T004 Initialize Vite + npm project in `frontend/package.json` and `frontend/vite.config.js`
- [X] T005 Configure Tailwind v4 entry with `@theme` tokens (cool off-white, single petrol-blue accent, expressive fonts) in `frontend/src/styles/main.css`
- [X] T006 Create shell `frontend/index.html` linked to CSS/JS entries and `frontend/src/js/main.js`
- [X] T007 [P] Add `frontend/public/` for static assets (CV PDF stub optional, images placeholders)

### B1 — User Story 1: Hero + nav (P1) 🎯 MVP slice

**Goal**: Recruiter understands who Pedro is in the first viewport; can jump via CTAs and persistent nav.

**Independent Test**: quickstart P1-01, P1-02, P1-06 (hero + nav + no level labels).

- [X] T008 [US1] Implement compact persistent in-page nav (anchors including Projetos + Contato; must not overpower brand) in `frontend/index.html` + `frontend/src/js/main.js`
- [X] T009 [US1] Implement full-bleed typographic/atmospheric Hero (dominant “Pedro Ribeiro”, positioning line, CTAs Ver projetos / Contato; no cards/badges/stats/photo required) in `frontend/index.html`
- [X] T010 [US1] Implement Sobre section (2–3 professional sentences, no hobby-primary copy) in `frontend/index.html`

### B2 — User Story 2: Projetos + Experiência (P1)

**Goal**: Recruiter can evaluate 2+ cases and timeline.

**Independent Test**: quickstart P1-03 (partial), P1-04.

- [X] T011 [US2] Implement Projetos as 2–4 stacked cases (Correnem + Index; skills inside cases; optional outbound links only when URL set) in `frontend/index.html`
- [X] T012 [US2] Implement Experiência/Formação compact timeline (Index Digital; UNIFOR CC + pós) in `frontend/index.html`

### B3 — User Story 3: Contato (P1)

**Goal**: Contact/CV without a form.

**Independent Test**: quickstart P1-05.

- [X] T013 [US3] Implement Contato links (email, WhatsApp, LinkedIn, GitHub, curriculum PDF) with no form; omit empty links in `frontend/index.html` + `frontend/public/` as needed

### B4 — Now mock policy + motion + section order

- [X] T014 [US4] Omit `#now` entirely OR add a clearly non-authoritative mock that does not call APIs or embed secrets in `frontend/index.html` (Escritos MUST remain omitted)
- [X] T015 [P] Add light motion (section entrance, case hover; optional now cue only if mock visible) in `frontend/src/js/main.js` + `frontend/src/styles/main.css`
- [X] T016 Verify section order Hero → Sobre → Projetos → Experiência → (optional Now) → Contato and banned aesthetics absent per `contracts/ui-home.md` in `frontend/`

### B5 — Visual stability gate

- [X] T017 Run Phase 1 quickstart scenarios P1-01…P1-08 from `specs/001-portfolio-one-pager/quickstart.md` against `npm run dev` in `frontend/`
- [X] T018 [P] Copy audit: ensure no júnior/pleno/sênior (or equivalents) in `frontend/index.html` and meta tags
- [X] T019 [P] Responsive pass (mobile + desktop, no horizontal scroll on core content) for `frontend/index.html`
- [X] T020 **GATE — Visual stability accepted** (manual checkbox): record acceptance before any Phase C task; blocks T021+

**Checkpoint**: Front MVP (US1–US3) accepted. Stop Chat 1 here.

---

## Phase C: Docker + tema Underscores (Chat 2)

**Purpose**: Local WP stack + `_s` theme; port markup; Escritos templates + conditional home loop.

**Depends on**: **T020** (visual stability). Do not start without it.

### C0 — Docker & theme scaffold

- [X] T021 Create `docker/docker-compose.yml` with WordPress + MySQL + phpMyAdmin (phpMyAdmin local-only profile/service); mount `wp-content/` ; do not vendor WP core in Git
- [X] T022 [P] Add `docker/.env.example` (DB creds, ports, `LASTFM_API_KEY` placeholder) and ensure `docker/.env` is gitignored
- [X] T023 [P] Document local `docker compose` up/down and “no phpMyAdmin in production” in `docker/README.md`
- [X] T024 Scaffold Underscores-based theme into `wp-content/themes/pedro-ribeiro/` (`style.css`, `functions.php`, `header.php`, `footer.php`)
- [X] T025 Add theme Vite pipeline (`wp-content/themes/pedro-ribeiro/package.json`, `vite.config.js`, `src/styles/`, `src/js/`) porting tokens/assets from `frontend/`
- [X] T026 Enqueue built theme CSS/JS from `wp-content/themes/pedro-ribeiro/functions.php`

### C1 — Port one-pager markup

- [X] T027 Create `wp-content/themes/pedro-ribeiro/front-page.php` composing section partials in constitution order
- [X] T028 [P] [US1] Port Hero + nav + Sobre markup into `wp-content/themes/pedro-ribeiro/template-parts/hero.php`, `header.php` (nav), `template-parts/sobre.php` with `esc_*`
- [X] T029 [P] [US2] Port Projetos + Experiência into `wp-content/themes/pedro-ribeiro/template-parts/projetos.php` and `template-parts/experiencia.php` with `esc_*`
- [X] T030 [P] [US3] Port Contato into `wp-content/themes/pedro-ribeiro/template-parts/contato.php` with `esc_*` (no form)
- [X] T031 [US4] Add stub `wp-content/themes/pedro-ribeiro/template-parts/now.php` that renders nothing until live wiring (or only if safe hardcoded empty guard)

### C2 — User Story 5: Escritos ready (P2)

**Goal**: Conditional home list + minimal single/archive; zero posts → no section.

**Independent Test**: quickstart P2-06, P2-07 (after WP up).

- [X] T032 [US5] Implement conditional Escritos home partial (up to 3 posts; omit entire section if zero) in `wp-content/themes/pedro-ribeiro/template-parts/escritos.php` and include from `front-page.php`
- [X] T033 [P] [US5] Create minimal editorial `wp-content/themes/pedro-ribeiro/single.php`
- [X] T034 [P] [US5] Create minimal editorial `wp-content/themes/pedro-ribeiro/archive.php`
- [X] T035 Ensure `wp-content/themes/pedro-ribeiro/acf-json/` exists (empty placeholder; ready for owner sync) and load path registered in `wp-content/themes/pedro-ribeiro/inc/acf.php`

**Checkpoint**: Theme mirrors accepted front; Escritos templates exist. Content still hardcoded or empty until Phase D.

---

## Phase D: ACF wiring (Chat 3) — BLOQUEADA até o owner

**Purpose**: Owner-authored ACF schema, then theme wiring only. **Do not invent field keys.**

**Depends on**: Phase C (theme running). Wiring tasks depend on **T040**.

### D0 — Owner (not implementer code)

- [ ] T040 **OWNER GATE**: Owner creates ACF field groups in WP admin covering entities in `specs/001-portfolio-one-pager/data-model.md` (Profile, Project cases, Experience entries, Now usernames/toggles only—no API secrets) and syncs JSON into `wp-content/themes/pedro-ribeiro/acf-json/`

### D1 — User Story 6: Owner edits without code (P3)

**Goal**: Public page reflects admin edits for hero/about/projects/experience/contact/media.

**Independent Test**: quickstart P2-02, P2-08.

- [ ] T041 [US6] After T040, map Profile fields via `get_field` in `wp-content/themes/pedro-ribeiro/template-parts/hero.php`, `sobre.php`, `contato.php` using keys from synced JSON only
- [ ] T042 [US6] After T040, map Project case repeater/fields in `wp-content/themes/pedro-ribeiro/template-parts/projetos.php` (omit link controls when URL empty)
- [ ] T043 [US6] After T040, map Experience entries in `wp-content/themes/pedro-ribeiro/template-parts/experiencia.php`
- [ ] T044 [US6] After T040, wire Now config (usernames + visibility toggles only) readable from ACF in `wp-content/themes/pedro-ribeiro/inc/acf.php` or helpers—never store `LASTFM_API_KEY` in ACF
- [ ] T045 [US6] Confirm secrets only via env/`wp-config` constants documented in `docker/.env.example` and theme bootstrap; no secret fields in admin content UI

**Checkpoint**: US6 satisfied for editorial content. Now still not live until Phase E.

---

## Phase E: Now live (Chat 4)

**Purpose**: Last.fm + Backloggd current-item integrations per `contracts/now-integrations.md`.

**Depends on**: Phase C partials; ideally T044 for usernames/toggles (else temporary constants in env only—prefer T044 first).

### User Story 4: Live Now without risking the site (P2)

**Independent Test**: quickstart P2-03, P2-04, P2-05.

- [ ] T050 [US4] Implement Last.fm fetcher (`user.getRecentTracks`, nowplaying-only, transient, short timeout) in `wp-content/themes/pedro-ribeiro/inc/now-lastfm.php`
- [ ] T051 [P] [US4] Implement Backloggd playing-page fetcher (first current game, transient, short timeout, silent fail) in `wp-content/themes/pedro-ribeiro/inc/now-backloggd.php`
- [ ] T052 [US4] Add aggregator returning normalized `{ listening, gaming }` (nulls on fail/empty) and load from `wp-content/themes/pedro-ribeiro/functions.php`
- [ ] T053 [US4] Render `template-parts/now.php`: show only non-null parts; omit entire section if both null; no error/unavailable UI; title as live integrations
- [ ] T054 [US4] Verify API key read from env/`wp-config` only; usernames/toggles from ACF config (post-T044)

**Checkpoint**: US4 complete with silent degrade.

---

## Phase F: Polish / smoke (Chat 5)

**Purpose**: Cross-cutting acceptance against quickstart and constitution.

**Depends on**: Phases B–E as applicable (minimum: B+C; full: through E).

- [ ] T060 Run full Phase 2 quickstart table P2-01…P2-09 in `specs/001-portfolio-one-pager/quickstart.md` and note results
- [ ] T061 [P] Smoke: Now degrade (kill key / bad username) → section omits failed parts or whole Now; primary sections intact
- [ ] T062 [P] [US5] Smoke: zero posts → Escritos absent; publish one → list + single/archive OK
- [ ] T063 [P] [US3] Smoke: no contact form; all present contact links work; empty fields omitted
- [ ] T064 [P] Copy/meta/admin label audit: no career-level terms (júnior/pleno/sênior)
- [ ] T065 Confirm Git hygiene: no WP core, no `.env`, phpMyAdmin not required for prod docs (`docker/README.md`, root `README.md`)
- [ ] T066 Final visual diff: theme home still matches Phase B accepted composition (nav, typographic hero, stacked cases, tokens)

**Checkpoint**: Feature ready for `/speckit-implement` completion or release candidate.

---

## Dependencies & Execution Order

### Phase dependencies

```text
A (Setup)
  └─► B (Front) ──T020 visual stability──┐
                                         ├─► C (Docker + theme + Escritos templates)
                                         │     └─► D (Owner T040 → ACF wiring US6)
                                         │           └─► E (Now live US4) [needs C; prefers D toggles]
                                         └─► F (Polish) after C–E as scoped
```

- **A**: no deps
- **B**: after A; delivers US1–US3 (static); US4 mock optional; US5 omitted
- **C**: **blocked by T020**; delivers theme port + US5 structure
- **D**: blocked by T040 (owner); delivers US6
- **E**: after C (+ T044 recommended); delivers US4 live
- **F**: after chosen scope complete

### User story mapping

| Story | Priority | Primary tasks | Earliest phase |
|-------|----------|---------------|----------------|
| US1 Hero/nav | P1 | T008–T010, T028 | B then C |
| US2 Cases/timeline | P1 | T011–T012, T029 | B then C |
| US3 Contato | P1 | T013, T030 | B then C |
| US4 Now | P2 | T014, T031, T050–T054 | B mock → E live |
| US5 Escritos | P2 | T032–T034 | C |
| US6 Admin CMS | P3 | T040–T045 | D |

### Parallel opportunities

- Phase A: T002, T003 in parallel after T001
- Phase B: T011/T012 after shell; T015/T018/T019 in parallel near end
- Phase C: T022/T023; T028/T029/T030; T033/T034 in parallel
- Phase E: T050/T051 in parallel before T052
- Phase F: T061–T064 in parallel

### Parallel example: Phase B (Chat 1)

```bash
# After T004–T007 scaffold:
Task: "T008 [US1] persistent nav in frontend/index.html + main.js"
Task: "T009 [US1] Hero in frontend/index.html"
# Then:
Task: "T011 [US2] Projetos in frontend/index.html"
Task: "T012 [US2] Experiência in frontend/index.html"
Task: "T013 [US3] Contato in frontend/index.html"
```

### Parallel example: Phase E (Chat 4)

```bash
Task: "T050 [US4] now-lastfm.php"
Task: "T051 [US4] now-backloggd.php"
# Then serial:
Task: "T052 aggregator → T053 now.php render → T054 secrets check"
```

---

## Implementation Strategy

### MVP (Chat 0 + Chat 1)

1. Complete Phase A (T001–T003)
2. Complete Phase B through **T020** (US1–US3 static)
3. **STOP** — demo recruiter one-pager; do not open Chat 2 until T020
4. **Git**: ensure work is on feature branch `001-portfolio-one-pager` (or equivalent); after T020 acceptance, **commit** Phase A+B (frontend + setup) before starting Phase C

### Incremental delivery

1. Chat 2 (C): WP theme parity + Escritos ready (US5) → commit after C checkpoint
2. Chat 3 (D): Owner T040 → wiring (US6) → commit after D
3. Chat 4 (E): Live Now (US4) → commit after E
4. Chat 5 (F): Smoke / quickstart → final commit / PR

### Rules (constitution)

- Front-first: never start T021+ without T020
- Do not invent ACF field keys; wiring only after T040 JSON
- No contact form, no dark default, no headless, no WP core in Git
- Now: one current item per source; silent omit

---

## Notes

- Manual acceptance only; use `specs/001-portfolio-one-pager/quickstart.md`
- [P] = different files / no incomplete deps
- Owner tasks (T040) are checklist items for the human, not code generation prompts
- Suggested next command after tasks: implement Phase A+B in one chat, then stop at T020
