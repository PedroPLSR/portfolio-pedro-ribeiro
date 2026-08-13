---
description: "Task list for Pré-publish — menus, dark mode e i18n"
---

# Tasks: Pré-publish — menus, dark mode e i18n

**Input**: Design documents from `/specs/002-pre-publish-readiness/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md, constitution v1.1.0

**Tests**: Manual only (quickstart A/B/C). No automated TDD tasks.

**Organization**: Three `/speckit-implement` chats. Do not invent ACF keys. Do not break one-pager / Now / Escritos / omit-empty. No career-level labels. No dark or Polylang in Chat 1; no Polylang in Chat 2.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no incomplete dependencies)
- **[Story]**: US1–US3 from spec.md (omitted on Setup / Foundational / Polish)
- Exact file paths required

## Path Conventions

- Theme: `wp-content/themes/pedro-ribeiro/`
- Specs: `specs/002-pre-publish-readiness/`

## Chat map

| Phase | Story | Chat | Branch | Gate |
|-------|-------|------|--------|------|
| 1–2 Setup + Foundational | — | Start of Chat 1 | `feat/menus` | T003 |
| 3 Menus | US1 P1 | **Chat 1** | `feat/menus` | T012 (A-01…A-07) |
| 4 Dark | US2 P2 | **Chat 2** | `feat/dark-mode` (after A merged, or on top of A) | T020 (B-01…B-08) |
| 5 i18n | US3 P3 | **Chat 3** | `feat/i18n` (after A; B may already be merged) | T034 (C-01…C-12) |
| 6 Polish | — | End of Chat 3 | `feat/i18n` | T037 |

---

## Phase 1: Setup (Chat 1 preamble)

**Purpose**: Confirm 001 stack. No new product folders. No WordPress core in Git.

**Independent Test**: Docker WP serves the existing one-pager; theme `pedro-ribeiro` is active.

- [X] T001 Confirm local Docker WordPress is up via `docker/docker-compose.yml` and theme `wp-content/themes/pedro-ribeiro/` is active (one-pager from 001 loads)
- [X] T002 Create git branch `feat/menus` for Chat 1; work only under `wp-content/themes/pedro-ribeiro/` (no Polylang install, no dark CSS yet)

---

## Phase 2: Foundational (Chat 1 preamble)

**Purpose**: Shared nav include so header/footer stay thin. **Blocks US1.** US2/US3 chats still wait for their own gates (A merged for C; A merged or stacked for B).

**⚠️ CRITICAL**: Do not start US1 header/footer swaps until T003 exists.

- [X] T003 Add `wp-content/themes/pedro-ribeiro/inc/nav.php` (file header + `ABSPATH` guard only) and `require_once` it from `wp-content/themes/pedro-ribeiro/functions.php`

**Checkpoint**: Foundation ready — implement US1 on `feat/menus`. **Stop Chat 1 after Phase 3 gate (T012).** Do not start dark or i18n in this chat.

---

## Phase 3: User Story 1 — Owner-managed header and footer menus (Priority: P1) 🎯 MVP

**Goal**: Aparência → Menus locations `primary` + `footer`; brand **PR** hardcoded; fallback if empty/unassigned; footer copyright-only when empty.

**Independent Test**: `specs/002-pre-publish-readiness/quickstart.md` A-01…A-07. Dark and Polylang are not required.

**Contract**: `specs/002-pre-publish-readiness/contracts/ui-chrome.md`

**Out of scope this chat**: `prefers-color-scheme`, Polylang, language switcher, `en_US` translations.

### Implementation for User Story 1

- [X] T004 [US1] Register nav locations `primary` and `footer` via `register_nav_menus` in `wp-content/themes/pedro-ribeiro/inc/nav.php` (hook `after_setup_theme`)
- [X] T005 [US1] Add `home_url('/#…')` helper plus primary fallback (Sobre, Projetos, Experiência, Contato → `#sobre` `#projetos` `#experiencia` `#contato`; labels `esc_html__()` text domain `pedro-ribeiro`; hashes not translated) in `wp-content/themes/pedro-ribeiro/inc/nav.php`
- [X] T006 [US1] Add helper that treats unassigned **or** zero items as empty (so assigned-empty menus do not render a blank `<ul>`) in `wp-content/themes/pedro-ribeiro/inc/nav.php`
- [X] T007 [US1] Replace hardcoded `.site-nav__list` items in `wp-content/themes/pedro-ribeiro/header.php` with `wp_nav_menu` (`theme_location` => `primary`, `depth` => 1, `container` => false, existing `menu_class` `site-nav__list`, `fallback_cb` from T005); keep brand **PR** hardcoded to `home_url('/#topo')`
- [X] T008 [US1] In `wp-content/themes/pedro-ribeiro/footer.php`, output footer `<nav>` with `wp_nav_menu` (`theme_location` => `footer`, `depth` => 1) **only** when T006 says the location has ≥1 item; always keep `© {year} Pedro Ribeiro`; never render an empty footer nav
- [X] T009 [P] [US1] Add compact footer menu styles (flat list, does not fight copyright) in `wp-content/themes/pedro-ribeiro/src/styles/main.css`
- [X] T010 [US1] If T009 changed CSS, run `npm run build` in `wp-content/themes/pedro-ribeiro/` so `dist/` updates
- [X] T011 [US1] Copy audit: no júnior/pleno/sênior (or junior/senior) in `wp-content/themes/pedro-ribeiro/inc/nav.php`, `header.php`, `footer.php`
- [X] T012 [US1] **GATE — Chat 1**: run quickstart A-01…A-07 in `specs/002-pre-publish-readiness/quickstart.md` (assigned menu updates; clear assignment restores fallback; footer empty = copyright only; inner page → home+hash; Now/Escritos/omit-empty/section order unchanged)

**Checkpoint**: US1 done. Merge/PR `feat/menus`. **Stop Chat 1.** Next chat is Phase 4 on `feat/dark-mode`.

---

## Phase 4: User Story 2 — Appearance follows the visitor’s system (Priority: P2)

**Goal**: Dark via `prefers-color-scheme` token remap; light stays authored default; WCAG 2.2 AA; no toggle; no FOUC.

**Independent Test**: `specs/002-pre-publish-readiness/quickstart.md` B-01…B-08.

**Contract**: `specs/002-pre-publish-readiness/contracts/appearance.md`

**Depends on**: Phase 3 merged **or** this branch stacked on `feat/menus`. **No Polylang in this chat.**

### Implementation for User Story 2

- [ ] T013 [US2] Create git branch `feat/dark-mode` from merged A (or from `feat/menus`); touch appearance only under `wp-content/themes/pedro-ribeiro/src/styles/main.css` (+ Vite `dist/`)
- [ ] T014 [US2] Set `html { color-scheme: light; }` as authored default and `color-scheme: dark` inside `@media (prefers-color-scheme: dark)` in `wp-content/themes/pedro-ribeiro/src/styles/main.css`
- [ ] T015 [US2] Remap `@theme` tokens `canvas`, `canvas-deep`, `ink`, `ink-muted`, `accent`, `accent-soft`, `line` (and `--color-on-accent` if added) in that dark media query in `wp-content/themes/pedro-ribeiro/src/styles/main.css` per `specs/002-pre-publish-readiness/contracts/appearance.md` (lighten accent in dark; keep petrol hue)
- [ ] T016 [US2] Replace light-only leftovers in `wp-content/themes/pedro-ribeiro/src/styles/main.css` (`.btn--primary` `#f7fbfc`, `::selection` mix with `white`, `.site-atmosphere` `#9bb8b0`, other page-paint `color-mix(..., white, ...)`) with tokens
- [ ] T017 [US2] Run `npm run build` in `wp-content/themes/pedro-ribeiro/` so dark CSS ships in `dist/` / `wp_head` (no JS class, no cookie, no FOUC)
- [ ] T018 [US2] Confirm there is no appearance toggle in `wp-content/themes/pedro-ribeiro/header.php`, `footer.php`, and `src/js/main.js`
- [ ] T019 [US2] Contrast-check text + essential controls to WCAG 2.2 AA in **light and dark** against tokens in `wp-content/themes/pedro-ribeiro/src/styles/main.css`; adjust dark pairs if they fail; reject purple/neon “AI dark”
- [ ] T020 [US2] **GATE — Chat 2**: run quickstart B-01…B-08 in `specs/002-pre-publish-readiness/quickstart.md` (light unchanged; dark readable; live OS change; first paint dark; no toggle; A-07 regression still holds)

**Checkpoint**: US2 done. Merge/PR `feat/dark-mode`. **Stop Chat 2.** Next chat is Phase 5 on `feat/i18n`.

---

## Phase 5: User Story 3 — Portuguese default and English at `/en/` (Priority: P3)

**Goal**: Polylang free; PT at `/`; EN at `/en/`; no Accept-Language redirect; hide EN until published English home; 404 otherwise; hash preserve; sister ACF page; menus per language; gettext chrome.

**Independent Test**: `specs/002-pre-publish-readiness/quickstart.md` C-01…C-12.

**Contract**: `specs/002-pre-publish-readiness/contracts/i18n.md`

**Depends on**: Phase 3 (menus/fallback). Phase 4 may already be merged (dark + EN must compose).

**Forbidden**: Multisite, WPML, TranslatePress Pro, inventing ACF field keys, translating section IDs.

### Implementation for User Story 3

- [ ] T021 [US3] Create git branch `feat/i18n` from merged A (and B if merged); install **Polylang free only** in the Docker WP from `docker/` (plugin not committed); pretty permalinks on
- [ ] T022 [US3] Configure Polylang per `specs/002-pre-publish-readiness/contracts/i18n.md` (`pt_BR` default hide-in-URL, `en_US` slug `en`, detect browser language **OFF**, no original-content fallback) — record owner steps already in `specs/002-pre-publish-readiness/quickstart.md`
- [ ] T023 [US3] Add `wp-content/themes/pedro-ribeiro/inc/i18n.php` (`ABSPATH` guard) and `require_once` it from `wp-content/themes/pedro-ribeiro/functions.php`
- [ ] T024 [US3] Make `pedro_ribeiro_home_id()` language-aware (`pll_get_post` when Polylang is active) in `wp-content/themes/pedro-ribeiro/inc/acf.php` so EN home reads sister ACF flex (do **not** add ACF keys; do not change `acf-json/` schema)
- [ ] T025 [US3] In `wp-content/themes/pedro-ribeiro/inc/i18n.php`, 404 English home when the EN translation of the static front is missing/unpublished (`template_redirect`); never render PT body as English
- [ ] T026 [US3] Render compact PT | EN switcher in `wp-content/themes/pedro-ribeiro/header.php` (after section nav, not replacing it; `hide_if_no_translation`; `aria-current` on current language; no EN until published EN home)
- [ ] T027 [US3] Preserve home hashes on language-switch clicks (`#topo` `#sobre` `#projetos` `#experiencia` `#now` `#escritos` `#contato`) in `wp-content/themes/pedro-ribeiro/src/js/main.js` (`/#projetos` ↔ `/en/#projetos`)
- [ ] T028 [US3] Run `npm run build` in `wp-content/themes/pedro-ribeiro/` after T027 so `dist/` includes hash helper
- [ ] T029 [P] [US3] Add `wp-content/themes/pedro-ribeiro/languages/pedro-ribeiro-en_US.po` and compiled `.mo` for existing PT msgids (fallback nav, “Ouvindo”, “Última review”, “Seções”, “Papel:”, single/archive chrome)
- [ ] T030 [P] [US3] Confirm section `id`s stay `#topo` `#sobre` `#projetos` `#experiencia` `#now` `#escritos` `#contato` in `wp-content/themes/pedro-ribeiro/template-parts/*.php` (do not translate IDs)
- [ ] T031 [US3] Confirm Escritos `WP_Query` in `wp-content/themes/pedro-ribeiro/template-parts/escritos.php` does **not** set `lang` => `''` (Polylang must filter by locale; omit section when that language has zero posts)
- [ ] T032 [US3] Confirm Now fetchers in `wp-content/themes/pedro-ribeiro/inc/now.php`, `inc/now-lastfm.php`, `inc/now-backloggd.php` are unchanged except gettext labels; degrade/omit-empty still apply on `/en/`
- [ ] T033 [US3] Copy audit: no júnior/pleno/sênior (or junior/senior) in `wp-content/themes/pedro-ribeiro/languages/` and any new EN chrome
- [ ] T034 [US3] **GATE — Chat 3**: run quickstart C-01…C-12 in `specs/002-pre-publish-readiness/quickstart.md` (root stays PT with English browser; no EN home → switcher hides EN and `/en/` 404; published EN home + hash preserve; omit-empty per locale; Polylang off → menus+dark still work)

**Checkpoint**: US3 done on `feat/i18n`. Run Phase 6 polish in the same chat, then merge.

---

## Phase 6: Polish & cross-cutting (end of Chat 3)

**Purpose**: Full-feature regression. No new product surface.

- [ ] T035 Re-run A-07 / B-08 plus Now/Escritos empty-state from `specs/001-portfolio-one-pager/quickstart.md` against `wp-content/themes/pedro-ribeiro/` (section order Hero → Sobre → Projetos → Experiência → Now → Escritos → Contato)
- [ ] T036 [P] Confirm `wp-content/themes/pedro-ribeiro/acf-json/` has **no new field keys/groups** invented in this feature
- [ ] T037 [P] Final copy grep for career-level labels across `wp-content/themes/pedro-ribeiro/` (PHP, CSS comments, `languages/`)

**Checkpoint**: Feature 002 ready to merge. Suggested integrate order: `feat/menus` → `feat/dark-mode` → `feat/i18n`.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: Immediate. Chat 1.
- **Foundational (Phase 2)**: After T001–T002. Blocks US1 header/footer tasks.
- **US1 (Phase 3)**: After T003. Chat 1 only. Blocks Chat 2/3 by merge/stack, not by sharing a dirty tree with dark/i18n work.
- **US2 (Phase 4)**: After US1 on the branch (merged A or `feat/menus`). Chat 2. Must not install Polylang.
- **US3 (Phase 5)**: After US1. US2 optional but recommended so dark+EN compose. Chat 3.
- **Polish (Phase 6)**: After T034.

### User Story Dependencies

- **US1 (P1)**: No dependency on US2/US3. MVP.
- **US2 (P2)**: Independent of Polylang; uses existing chrome from US1.
- **US3 (P3)**: Needs US1 fallback/`home_url` hashes; uses US2 CSS if already merged.

### Parallel Opportunities

- T009 (footer CSS) can overlap T007/T008 once T006 exists (different files).
- T018 can overlap T017 (header/js vs build) after T016.
- T029 and T030 can run in parallel after T026.
- T036 and T037 in parallel after T035 starts.
- **Do not** parallelize Chat 1+2+3 in one working tree (user asked separate implement chats/branches).

---

## Parallel Example: User Story 1

```text
# After T006 (empty-location helper):
Task: "Replace hardcoded nav in wp-content/themes/pedro-ribeiro/header.php"
Task: "Conditional footer menu in wp-content/themes/pedro-ribeiro/footer.php"
Task: "Footer menu CSS in wp-content/themes/pedro-ribeiro/src/styles/main.css"
```

## Parallel Example: User Story 3

```text
# After switcher markup exists:
Task: "en_US po/mo in wp-content/themes/pedro-ribeiro/languages/"
Task: "Confirm stable section IDs in wp-content/themes/pedro-ribeiro/template-parts/"
```

---

## Implementation Strategy

### MVP First (User Story 1 only)

1. T001–T003 (Setup + Foundational)
2. T004–T012 (US1) on `feat/menus`
3. **STOP** — Chat 1 gate. Merge menus.

### Incremental Delivery

1. Chat 1 `feat/menus` → A-01…A-07
2. Chat 2 `feat/dark-mode` → B-01…B-08
3. Chat 3 `feat/i18n` → C-01…C-12 then T035–T037

### Chat rules

- Chat 1: **SEM** dark, **SEM** Polylang
- Chat 2: **SEM** Polylang
- Chat 3: Polylang **free only**; no Multisite/WPML/TranslatePress Pro
- Never invent ACF keys
- Never rename section IDs
- Never add a dark toggle

---

## Notes

- [P] = different files, no incomplete deps
- Manual smoke lives in T012 / T020 / T034 (not a TDD suite)
- Fallback hashes stay `#sobre` `#projetos` `#experiencia` `#contato` in every language
- `pedro_ribeiro_home_id()` language mapping is US3-only; Phases A/B keep current `page_on_front` behavior
- Suggested branches: `feat/menus` → `feat/dark-mode` → `feat/i18n`
