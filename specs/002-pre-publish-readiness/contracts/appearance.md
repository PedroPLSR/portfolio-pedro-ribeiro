# Contract: Appearance (system first, then Claro ↔ Escuro)

**Feature**: `002-pre-publish-readiness`  
**Audience**: Implementers (`header.php`, `src/styles/main.css`, optional `src/js/main.js`, Vite build)  
**Date**: 2026-08-13  
**Phase**: B (independent of Polylang)

## Resolve order

| Priority | Condition | Result |
|----------|-----------|--------|
| 1 | `localStorage` key `pedro-ribeiro-appearance` is `light` or `dark` | That explicit mapping (overrides the system) |
| 2 | No saved value; `prefers-color-scheme: dark` | Dark mapping |
| 3 | No saved value; `light` or `no-preference` / unknown | Light mapping (authored default) |

Set `html` `data-theme="light"` or `data-theme="dark"` (class equivalent allowed if it is the only switch) and matching `color-scheme` **before first paint**.

## Mechanism

| Rule | Requirement |
|------|-------------|
| Light | Authored default. `html { color-scheme: light; }` plus current `@theme` light tokens. Applies when resolved appearance is light. |
| Dark | `html[data-theme="dark"]` remaps tokens and sets `color-scheme: dark`. |
| Media fallback | `@media (prefers-color-scheme: dark)` MAY remap the same tokens when `data-theme` is **unset** (no JS). Once `data-theme` is present, the attribute wins. |
| Toggle | Compact header **Claro ↔ Escuro** (exactly two options — not System / Light / Dark). Writes `localStorage` (`pedro-ribeiro-appearance` = `light` \| `dark`) and updates `data-theme` immediately. MUST NOT overpower brand; MUST NOT be a `wp_nav_menu` item. |
| Follow system | Only while nothing is saved. `matchMedia('(prefers-color-scheme: dark)')` change updates `data-theme` without navigation. After a toggle, ignore OS changes until the visitor toggles again (or clears site data). No UI “reset to system”. |
| Storage | `localStorage` only. **No** cookie, query param, or admin “force dark”. |
| FOUC | Minimal **inline** script in `<head>` **before** `wp_head()` plus dark tokens in Vite `dist/` CSS from `wp_head`. Do not apply the first theme only from late footer JS. |
| Default ban | Dark MUST NOT be the site default. Light-system visitors with nothing saved stay light (constitution). |

## Token remap

Same names as today. Dark starting values (verify AA in implement; adjust if a pair fails):

| Token | Light | Dark |
|-------|-------|------|
| `--color-canvas` | `#eef3f6` | `#121a1e` |
| `--color-canvas-deep` | `#e2ebe8` | `#0e1518` |
| `--color-ink` | `#142028` | `#e8eef1` |
| `--color-ink-muted` | `#4a5c66` | `#a8b8c0` |
| `--color-accent` | `#1a5f6e` | `#5eb3c0` |
| `--color-accent-soft` | `#2a7a8c` | `#7ac4ce` |
| `--color-line` | mix ink 12% | mix ink ~16% |
| `--color-on-accent` (add if needed) | `#f7fbfc` | `#0e1518` |

Lighten accent in dark so links/buttons meet AA; keep petrol hue (not purple/neon).

## Must tokenize (today they fight dark)

- `.btn--primary { color: #f7fbfc; }`
- `::selection` mix with `white`
- `.site-atmosphere` mix with `#9bb8b0`
- Other `color-mix(..., white, ...)` used as page paint

Components (nav, footer, Now cards, cases, contact links, appearance control) MUST use tokens so they invert with the palette.

## Contrast (WCAG 2.2 AA)

| Surface | Minimum |
|---------|---------|
| Body / nav / footer / contact text | 4.5:1 vs canvas |
| Large titles (if ≥18pt regular or 14pt bold) | 3:1 |
| Essential controls (buttons, link text, focus, appearance control) | 4.5:1 for text; 3:1 for non-text UI that conveys state |

Failing a pair is a **defect**, not a taste call. Check **both** light (regression) and dark.

## Visual identity

- Dark still editorial-technical: cool dark canvas, petrol accent, expressive type.
- Forbidden: purple/indigo glow, neon gradients, cream/terracotta inversion, generic “AI dark”.
- Motion (nav scroll, reveal) unchanged; respect `prefers-reduced-motion` as today.

## Out of scope

- Three-way System / Light / Dark picker
- Cookie or server-stored preference
- Admin “force dark”
- Separate dark stylesheet file as the only dark path
- Changing section layout to “work around” contrast
