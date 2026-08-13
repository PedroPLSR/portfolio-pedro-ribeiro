# Research: Pré-publish — menus, dark mode e i18n

**Feature**: `002-pre-publish-readiness` | **Date**: 2026-08-13

Locked by spec clarifications (Session 2026-08-13) and the `/speckit-plan` prompt. Do not reopen these product choices in implement.

## R1 — Delivery base (no new stack)

**Decision**: Implement only in `wp-content/themes/pedro-ribeiro/` on the existing Docker WordPress from feature 001. Do not add a second CMS, headless front, Multisite, or paid plugins. WordPress core stays out of Git. Polylang free is installed in the local WP (admin/WP-CLI), not committed as a plugin tree unless the repo already tracks plugins (it does not).

**Rationale**: Constitution VII/VIII + spec FR-021. 001 A–F already shipped the one-pager, Now, Escritos, and ACF omit-empty.

**Alternatives considered**:
- Multisite PT/EN — rejected (cost/ops, spec forbid).
- New theme or `frontend/` revisit — rejected (front-first already done).

## R2 — Phase A: native menus + fallback

**Decision**:

- `register_nav_menus` in `pedro_ribeiro_setup()`: `primary` (header) and `footer`.
- Header: `wp_nav_menu` into the existing `.site-nav__list` chrome. Brand **PR** stays hardcoded (`home_url('/#topo')`). Depth **1** (flat). `container` false; keep current list markup/classes.
- If primary is unassigned **or** has zero items: fallback callback (or equivalent helper) prints Sobre / Projetos / Experiência / Contato with `home_url('/#sobre')` etc. Labels via `esc_html__()` (text domain `pedro-ribeiro`); hashes never translated.
- Footer: output a footer `<nav>` only when the location has ≥1 item; otherwise copyright only (no empty nav). Copyright line unchanged.
- From inner pages, hash items must use `home_url('/#…')` so they land on the current-language home (Polylang filters `home_url` in Phase C; Phase A still benefits).

**Rationale**: Spec US1 / FR-001–006. Native Appearance → Menus; no extra menu plugin.

**Alternatives considered**:
- Customizer-only links — weaker than Menus locations the owner asked for.
- Nested walker — out of spec (flat only).
- Fallback as Custom Menu widget — extra chrome.

## R3 — Phase B: CSS-only dark via `prefers-color-scheme`

**Decision**: Remap the existing `@theme` token family in `src/styles/main.css` inside `@media (prefers-color-scheme: dark)` on `html` / `:root`:

`canvas`, `canvas-deep`, `ink`, `ink-muted`, `accent`, `accent-soft`, `line`.

Also:

- `html { color-scheme: light; }` stays the authored default.
- In the dark media query: `color-scheme: dark;` plus token overrides.
- **No** JS class, cookie, toggle, or admin force-dark.
- Vite rebuild (`npm run build` in the theme) so `dist/` CSS in `wp_head` applies on first paint → no light→dark FOUC.
- Replace leftover non-token paints that would stay “light” in dark mode: `.btn--primary` `#f7fbfc`, `::selection` mix with `white`, `.site-atmosphere` mix with `#9bb8b0`, other `color-mix(..., white, ...)`. Introduce a token such as `--color-on-accent` if needed for button text.

**Rationale**: Spec US2; constitution “dark MUST NOT be the default”; CSS-only follows system live (FR-011) without a control (FR-009).

**Alternatives considered**:
- `html.dark` + toggle — forbidden in v1.
- Duplicate dark stylesheet — extra request, FOUC risk.
- Tailwind `dark:` variant with class strategy — needs JS; media strategy is enough if tokens are remapped.

## R4 — Dark palette + WCAG 2.2 AA

**Decision**: Keep petrol hue; invert canvas/ink; **lighten accent in dark** so links/buttons still hit AA (the light accent `#1a5f6e` on a dark canvas fails 4.5:1). Starting candidates (verify with a contrast checker during Phase B; adjust if needed):

| Token | Light (current) | Dark (starting point) |
|-------|-----------------|------------------------|
| canvas | `#eef3f6` | `#121a1e` |
| canvas-deep | `#e2ebe8` | `#0e1518` |
| ink | `#142028` | `#e8eef1` |
| ink-muted | `#4a5c66` | `#a8b8c0` |
| accent | `#1a5f6e` | `#5eb3c0` (petrol, lighter) |
| accent-soft | `#2a7a8c` | `#7ac4ce` |
| line | mix ink 12% | mix ink ~16% |
| on-accent (new if needed) | `#f7fbfc` | `#0e1518` |

AA bars: normal text ≥ 4.5:1; large text / essential UI (nav, buttons, borders that convey state) ≥ 3:1. Both light (regression) and dark. Ban generic “AI dark” (purple/indigo glow, neon, cream inversion).

**Rationale**: Clarify Q5; FR-010 / SC-003.

**Alternatives considered**:
- Keep `#1a5f6e` in dark — fails AA on dark canvas.
- Near-black + white only — loses editorial petrol identity.

## R5 — Phase C: Polylang free, subdirectory `/en/`

**Decision**: Polylang **free** only. Languages: Portuguese default locale `pt_BR` (hide language in default URL), English `en_US` with slug `en` → `/en/`. Pretty permalinks required.

Owner/admin settings (must match spec; document in quickstart):

- Detect browser language: **OFF** (no Accept-Language redirect).
- Hide URL language information for default language: **ON**.
- Language from directory name in pretty permalinks.
- Do **not** show original-language content when a translation is missing (no PT body presented as EN).
- Media shared across languages: ON (YAGNI).

Theme guard: if the request is English and the English translation of the static front page is missing or not published, `template_redirect` → 404. Language switcher: `pll_the_languages` (or equivalent) with `hide_if_no_translation` so EN is omitted until that translation exists.

**Rationale**: Spec FR-012–016, FR-015a; clarifications Q1–Q2.

**Alternatives considered**:
- WPML / TranslatePress Pro — paid, forbidden.
- `?lang=en` query — not `/en/`.
- Browser auto-redirect — rejected in clarify.

## R6 — ACF home = sister page, not a second IA

**Decision**: Keep one static front page; Polylang creates the English **translation** (sister post). `flex_content` (and other home ACF) is filled independently on the EN page. Theme keeps using `get_field` / `have_rows` on the current front-page ID.

Update `pedro_ribeiro_home_id()` so it resolves the **current-language** front page (`pll_get_post` when Polylang is active; today it only reads `page_on_front`). Omit-empty stays per language (empty EN layout → that block omitted).

Now **usernames / hide toggles**: owner may copy them onto the EN page. Optional nicety (not required): if EN Now usernames are empty, read default-language home for Now *config only* so live widgets do not vanish because of a forgotten username — editorial flex still omit-empty. Prefer documenting “copy Now usernames on translate” first (YAGNI).

Do **not** invent new ACF keys.

**Rationale**: FR-014, FR-017, 001 ACF ownership rule.

**Alternatives considered**:
- ACF Options duplicated per language with Pro sync — extra plugin surface.
- Two unrelated WP pages with different templates — splits IA.

## R7 — Language switcher + hash preserve

**Decision**: Compact header control (e.g. PT | EN) after the section nav, not replacing it. Current language `aria-current="true"`. Hide EN when the current page has no published EN equivalent.

Server-side links are language homepages without hash. **Small JS** in existing `src/js/main.js`: on language-switch click (or when painting hrefs), if `location.hash` is a known home id (`#topo`, `#sobre`, `#projetos`, `#experiencia`, `#now`, `#escritos`, `#contato`), append that hash → `/#projetos` ↔ `/en/#projetos`. No hash → equivalent home top.

**Rationale**: Clarify Q3–Q4; PHP cannot see the URL hash.

**Alternatives considered**:
- Polylang widget in a sidebar — wrong chrome.
- Always drop hash — rejected in clarify.

## R8 — Chrome strings (gettext) vs ACF copy

**Decision**: Keep Portuguese msgids already in the theme (`'Sobre'`, `'Ouvindo'`, `'Última review'`, etc.). Add theme translations under `wp-content/themes/pedro-ribeiro/languages/` for `en_US` (`.po` / `.mo`) so `/en/` loads English chrome when Polylang sets locale. Fallback nav uses the same `__()`. ACF/editorial copy is translated on the sister page, not via gettext.

**Rationale**: FR-018; header already uses the text domain. Polylang string translations in admin would duplicate that.

**Alternatives considered**:
- Rewrite all msgids to English — noisy diff, not needed for v1.
- Hardcode English labels in `pll_current_language()` branches — brittle.

## R9 — Regression: Now, Escritos, omit-empty, section IDs

**Decision**: Do not change Now fetchers, transients, or degrade rules. Escritos `WP_Query` stays as-is (Polylang will language-filter unless `lang` is emptied — **do not** empty it). Section `id` attributes stay `#topo` `#sobre` `#projetos` `#experiencia` `#now` `#escritos` `#contato` in both languages. No career-level labels in new strings (PT or EN).

**Rationale**: FR-018a, FR-019, FR-020.

**Alternatives considered**: Translated IDs (`#projects`) — rejected in clarify.

## R10 — Implement chats / task grouping

**Decision**: `/speckit-tasks` and later `/speckit-implement` split:

| Phase | User story | Chat |
|-------|------------|------|
| A | US1 menus | Separate |
| B | US2 dark | Separate; depends on A only for shared chrome not breaking |
| C | US3 i18n | Separate; menus + dark already in |

A and B MUST work with Polylang inactive.

**Rationale**: Spec assumption + user request.

**Alternatives considered**: One mega-PR — harder review, contradicts requested chat split.
