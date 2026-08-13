# Implementation Plan: Pré-publish — menus, dark mode e i18n

**Branch**: `002-pre-publish-readiness` | **Date**: 2026-08-13 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `/specs/002-pre-publish-readiness/spec.md`

## Summary

Stack three pre-publish capabilities onto the existing `pedro-ribeiro` WordPress theme (feature 001 already delivered): **(A)** native header/footer menus with hardcoded fallback, **(B)** system dark appearance by remapping CSS tokens under `prefers-color-scheme` (light stays authored default; WCAG 2.2 AA; no toggle), **(C)** Polylang free — PT at `/`, EN at `/en/`, ACF flex on a sister home, no browser auto-redirect, hide EN until the English home is published. Implement in **separate chats** A → B → C. Do not break one-pager order, Now, Escritos, omit-empty ACF, or the career-label ban.

### Clarify delta (2026-08-13)

| Topic | Decision |
|-------|----------|
| Missing EN home | Hide EN in switcher; `/en/` is not found (never PT copy as EN) |
| Browser language | No auto-redirect; root always PT |
| Section IDs | Same in PT and EN (`#projetos` stays `#projetos`) |
| Language switch | Preserve home hash (`/#projetos` ↔ `/en/#projetos`) |
| Contrast | WCAG 2.2 AA for text + essential controls in light and dark |

## Technical Context

**Language/Version**: PHP 8.2+ (existing theme); HTML5 / CSS / ES modules; Node.js LTS for theme Vite

**Primary Dependencies**: WordPress (Docker), theme `pedro-ribeiro`, ACF (already), Tailwind v4 `@theme` + Vite; **Phase C only**: Polylang free

**Storage**: MySQL (WP); menus in WP nav tables; ACF JSON already in theme; no new DB product. Polylang language tables after plugin activate.

**Testing**: Manual quickstart scenarios per phase; contrast check (AA) in Phase B; no automated suite required

**Target Platform**: Evergreen browsers (mobile + desktop); local Docker WP; production WP host without phpMyAdmin

**Project Type**: Classic WordPress theme (incremental pre-publish)

**Performance Goals**: Menu/dark/i18n MUST NOT add blocking third-party requests on first paint. Dark via CSS in `wp_head` (no FOUC). Now timeouts/transients unchanged.

**Constraints**: No Multisite; no WPML/TranslatePress Pro; no paid extras; no dark default; no appearance toggle/cookie; no Accept-Language redirect; no career-level labels; secrets stay out of Git; do not invent ACF keys; WP core not in repo

**Scale/Scope**: One one-pager + existing single/archive; two menu locations; two locales (pt_BR, en_US); token remap only (not a second design system)

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Gate | Status | Notes |
|------|--------|-------|
| I. Conteúdo no CMS | Pass | Menus in Appearance → Menus; home copy still ACF; fallback is chrome only |
| II. Tema como produto | Pass | Changes stay in `_s` theme; `esc_*` on menu URLs/labels; partials kept |
| III. Front honesto | Pass | Real CSS tokens + small hash JS; Vite rebuild; no page builder |
| IV. Design editorial técnico | Pass | Light remains authored default; dark only when system requests it; petrol accent; no AI-default looks |
| V. One-pager focado | Pass | Section order and IDs unchanged; Escritos still conditional; no contact form |
| VI. Integrações resilientes | Pass | Now fetchers/transients untouched; chrome labels gettext-only |
| VII. Dev reproduzível | Pass | Existing Docker; Polylang installed in that WP, not a new compose stack |
| VIII. Simplicidade / YAGNI | Pass | Polylang free is the justified bilingual slice; no Multisite/paid suite; A/B work without C |
| Development Order | Pass | Front-first already satisfied by 001; this feature is theme-only follow-on |

**Post-design re-check**: research/data-model/contracts/quickstart keep dark non-default, ACF keys owner-owned, Now/Escritos behavior intact, and Phase C optional relative to A/B — gates still Pass. No constitution amendment required.

## Project Structure

### Documentation (this feature)

```text
specs/002-pre-publish-readiness/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   ├── ui-chrome.md
│   ├── appearance.md
│   └── i18n.md
└── tasks.md             # Created later by /speckit-tasks — NOT this command
```

### Source Code (repository root)

```text
wp-content/themes/pedro-ribeiro/
├── functions.php                 # register_nav_menus; optional i18n helpers
├── header.php                    # wp_nav_menu primary + fallback; language switcher (C)
├── footer.php                    # optional footer menu + copyright
├── inc/
│   ├── acf.php                   # home_id() language-aware in C
│   ├── now.php / now-*.php       # unchanged behavior; gettext labels already
│   └── i18n.php                  # Phase C: 404 guard, switcher helper (optional split)
├── template-parts/               # stable section IDs; gettext chrome
├── languages/                    # Phase C: pedro-ribeiro-en_US.po/.mo
├── src/styles/main.css           # Phase B: dark token media query + detokenize leftovers
├── src/js/main.js                # Phase C: preserve hash on language switch
├── dist/                         # Vite build output (rebuild after B/C CSS/JS)
└── acf-json/                     # no new keys; EN is sister page content

docker/                           # unchanged stack; install Polylang inside running WP
```

**Structure Decision**: Single existing theme. No `frontend/` work. No new packages beyond Polylang free in the WP container.

## Complexity Tracking

> No constitution violations. Polylang is in-spec complexity (FR-016), not a gate failure.

## Phase Plan

Task generation (`/speckit-tasks`) and implement chats **must** follow this split.

### Phase A — Menus (US1) — implement chat 1

1. `register_nav_menus` (`primary`, `footer`) in theme setup.
2. Replace hardcoded header `<ul>` with `wp_nav_menu` (depth 1, existing classes). Brand **PR** stays hardcoded.
3. Fallback if unassigned/empty: Sobre, Projetos, Experiência, Contato with `#sobre` `#projetos` `#experiencia` `#contato` via `home_url`.
4. Footer: menu beside copyright only when it has items; else copyright only.
5. Confirm inner-page hash links hit the home section. No Polylang, no dark work in this chat.

### Phase B — Dark (US2) — implement chat 2

1. Remap tokens in `prefers-color-scheme: dark`; set `color-scheme` accordingly.
2. Tokenize leftover light-only paints (button text, atmosphere mix, selection).
3. Vite rebuild. Verify no toggle/cookie and no FOUC (CSS in head).
4. Contrast-check light + dark to WCAG 2.2 AA; adjust dark accent lighter if needed.
5. Visual ban: no purple/neon “AI dark”. Light look unchanged when system is light.

### Phase C — i18n (US3) — implement chat 3

1. Install/activate Polylang free; configure per [contracts/i18n.md](./contracts/i18n.md) (no browser detect; default lang hidden in URL).
2. Language-aware `home_id()`; sister EN front page for ACF flex.
3. Switcher in header: hide EN without translation; 404 `/en/` without published EN home.
4. Hash-preserving JS on switcher; stable section IDs; per-language menus.
5. Theme `en_US` translations for chrome; Escritos/Now omit-empty per locale; no career-level strings.

## Design Artifacts

| Artifact | Role |
|----------|------|
| [research.md](./research.md) | Menus fallback, CSS dark/AA, Polylang, hash JS, gettext |
| [data-model.md](./data-model.md) | Locations, palettes, locales, EN home states |
| [contracts/ui-chrome.md](./contracts/ui-chrome.md) | Header/footer/switcher/IDs |
| [contracts/appearance.md](./contracts/appearance.md) | Tokens, AA, FOUC, no toggle |
| [contracts/i18n.md](./contracts/i18n.md) | Polylang settings, URLs, 404, ACF sister page |
| [quickstart.md](./quickstart.md) | Validation scenarios A → B → C |
