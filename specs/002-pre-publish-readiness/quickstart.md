# Quickstart: Pré-publish — menus, dark mode e i18n

**Feature**: `002-pre-publish-readiness` | **Date**: 2026-08-13

Validation guide for implement chats **A → B → C**. Contracts: [ui-chrome.md](./contracts/ui-chrome.md), [appearance.md](./contracts/appearance.md), [i18n.md](./contracts/i18n.md). Content entities: [data-model.md](./data-model.md). 001 Now/home contracts still apply for regression.

## Prerequisites

- Docker WordPress from feature 001 already running (`docker/`)
- Theme `pedro-ribeiro` active; ACF + owner `acf-json` already wired
- Node.js LTS (theme Vite) for Phase B/C asset rebuild
- Phase C: Polylang **free** (install in WP admin or WP-CLI inside the container — plugin is not in Git)
- Pretty permalinks enabled before Phase C

Theme assets:

```bash
cd wp-content/themes/pedro-ribeiro
npm install
npm run build
```

---

## Phase A — Menus

Polylang and dark CSS are **not** required.

### Setup

1. Activate theme (already).
2. After code: Aparência → Menus → locations **primary** and **footer** appear.

### Validation

| ID | Scenario | Expected |
|----|----------|----------|
| A-01 | Assign primary with different labels/URLs | Header list matches the menu; **PR** brand unchanged; compact nav |
| A-02 | Remove primary assignment (or empty menu) | Fallback: Sobre, Projetos, Experiência, Contato → `#sobre` `#projetos` `#experiencia` `#contato` |
| A-03 | Assign footer with ≥1 link | Footer shows those links **and** `© {year} Pedro Ribeiro` |
| A-04 | Footer unassigned/empty | Copyright only; no empty nav |
| A-05 | From a single/archive, activate Projetos | Lands on home `#projetos`, not a missing hash on the post |
| A-06 | Copy audit | No júnior/pleno/sênior in new admin/menu strings |
| A-07 | Regression | Section order, Now, Escritos omit-empty, ACF omit-empty unchanged |

**Chat gate**: A-01–A-07 pass → stop. Dark/i18n in later chats.

---

## Phase B — Dark

Menus from A may already be present.

### Setup

1. Change tokens in `src/styles/main.css`; `npm run build`.
2. Test with OS/browser appearance light and dark (DevTools “emulate prefers-color-scheme” is enough).

### Validation

| ID | Scenario | Expected |
|----|----------|----------|
| B-01 | System light | Current cool off-white look; no dark overlay |
| B-02 | System dark | Dark canvas, light ink, petrol accent; header/footer/Now/contact readable |
| B-03 | No toggle | Zero appearance controls in header, footer, home |
| B-04 | Change OS appearance while page is open | Palette updates without navigating |
| B-05 | FOUC | Hard reload in dark: first paint is already dark (no full-page light flash) |
| B-06 | Contrast | Text + essential controls meet WCAG 2.2 AA in **light and dark** (contrast checker) |
| B-07 | Visual ban | Not purple/indigo/neon “AI dark”; light identity still recognizable |
| B-08 | Regression | A-07 still true; light system unchanged from pre-B |

**Chat gate**: B-01–B-08 pass → stop. Do not install Polylang in this chat unless already there (must still work without it).

---

## Phase C — i18n

### Setup (outline)

1. Install/activate **Polylang** free in local WP.
2. Add `pt_BR` (default, hide in URL) and `en_US` (slug `en`).
3. Disable **Detect browser language**. Enable hide default language in URL. Language from directory.
4. Translate the static front page to EN and fill ACF flex when ready to show English.
5. Assign EN menus if desired; otherwise EN uses gettext fallback hashes.
6. Theme `languages/` `en_US` translations loaded; rebuild JS if hash helper was added.

### Validation

| ID | Scenario | Expected |
|----|----------|----------|
| C-01 | Cold load `/` with English browser | Portuguese home; **no** redirect to `/en/` |
| C-02 | EN home **not** published | Language control has no EN; `GET /en/` is **404** (not PT copy as English) |
| C-03 | EN home published | `/en/` shows same section story in English from sister ACF |
| C-04 | Switch from `/#projetos` | Lands `/en/#projetos`; reverse keeps hash |
| C-05 | Empty EN flex block | That block omitted; rest of EN home OK |
| C-06 | Now on `/en/` | English chrome labels; source titles OK; fail → omit part/section |
| C-07 | Escritos | Zero EN posts → no Escritos on `/en/` even if PT posts exist |
| C-08 | Section IDs | `#projetos` etc. unchanged on `/en/` |
| C-09 | `html lang` | `pt-BR` on `/`, `en-US` (or `en`) on `/en/` |
| C-10 | Copy audit | No career-level labels in EN or PT |
| C-11 | Regression | A-07 + B-08; menus + dark still compose with i18n |
| C-12 | Polylang deactivated | Site stays PT; menus + dark still work |

### Owner content note

English is not “done” until the EN front translation is published and flex filled. Shipping theme+plugin with C-02 passing is valid; C-03+ need that content.

---

## Production note

Deploy theme + Polylang free + ACF + content. Do not run phpMyAdmin in production. No paid translation plugin. No Multisite.
