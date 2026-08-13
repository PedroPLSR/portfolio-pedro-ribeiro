# Contract: Appearance (system dark / light)

**Feature**: `002-pre-publish-readiness`  
**Audience**: Implementers (`src/styles/main.css`, Vite build)  
**Date**: 2026-08-13  
**Phase**: B (independent of Polylang)

## Mechanism

| Rule | Requirement |
|------|-------------|
| Light | Authored default. `html { color-scheme: light; }` plus current `@theme` light tokens. Applies for `prefers-color-scheme: light` and `no-preference`. |
| Dark | `@media (prefers-color-scheme: dark)` remaps tokens on `html` / `:root` and sets `color-scheme: dark`. |
| Toggle | **None**. No button, cookie, `localStorage`, query param, or admin “force dark”. |
| Live update | CSS media query only — changing OS/browser appearance updates without navigation. |
| FOUC | Dark tokens MUST live in the CSS loaded from `wp_head` (Vite `dist/`). Do not apply dark by late JS. |
| Default ban | Dark MUST NOT apply when the system is light (constitution). |

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

Components (nav, footer, Now cards, cases, contact links) MUST use tokens so they invert with the palette.

## Contrast (WCAG 2.2 AA)

| Surface | Minimum |
|---------|---------|
| Body / nav / footer / contact text | 4.5:1 vs canvas |
| Large titles (if ≥18pt regular or 14pt bold) | 3:1 |
| Essential controls (buttons, link text, focus) | 4.5:1 for text; 3:1 for non-text UI that conveys state |

Failing a pair is a **defect**, not a taste call. Check **both** light (regression) and dark.

## Visual identity

- Dark still editorial-technical: cool dark canvas, petrol accent, expressive type.
- Forbidden: purple/indigo glow, neon gradients, cream/terracotta inversion, generic “AI dark”.
- Motion (nav scroll, reveal) unchanged; respect `prefers-reduced-motion` as today.

## Out of scope

- Manual theme switch
- Per-user saved preference
- Separate dark stylesheet file as the only dark path
- Changing section layout to “work around” contrast
