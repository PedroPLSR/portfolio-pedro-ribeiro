# Data Model: Pré-publish — menus, dark mode e i18n

**Feature**: `002-pre-publish-readiness` | **Date**: 2026-08-13

Conceptual model for chrome, appearance, and locales. Home editorial entities remain those in [001 data-model](../001-portfolio-one-pager/data-model.md). **Do not invent ACF field keys.**

## Entities

### Menu location

Named theme placement the owner assigns in Aparência → Menus.

| Attribute | Description | Required | Notes |
|-----------|-------------|----------|-------|
| slug | `primary` or `footer` | Yes | Registered in theme setup |
| assigned_menu | Menu or empty | No | Empty → fallback behavior |

**Rules**: Flat only (depth 1). Per-language assignment once Polylang is on (Polylang natively duplicates menus per language).

### Menu

Ordered list of items.

| Attribute | Description | Required | Notes |
|-----------|-------------|----------|-------|
| name | Admin label | Yes | |
| items[].label | Visible text | Yes | Translated per language menu |
| items[].url | Destination | Yes | Prefer `home_url` + stable hash for sections |
| items[].order | Position | Yes | |

**Validation**: No nested children in v1 (owner convention + walker depth 1). No career-level words in labels.

### Fallback chrome

Used when a location is unassigned or has zero items.

| Location | Behavior |
|----------|----------|
| primary | Links: Sobre, Projetos, Experiência, Contato → `#sobre` `#projetos` `#experiencia` `#contato` on current-language home. Brand **PR** → `#topo` (not a menu item). Labels gettext-translated; hashes not. |
| footer | Copyright only: `© {year} Pedro Ribeiro`. No empty `<nav>`. |

### Appearance palette

Two mappings of the same token names. Light is authored default. Dark applies when the system prefers dark **and** nothing is saved, **or** when the visitor has saved `dark`.

| Token | Role | Light | Dark (starting; verify AA) |
|-------|------|-------|------------------------------|
| canvas | Page ground | `#eef3f6` | `#121a1e` |
| canvas-deep | Atmosphere depth | `#e2ebe8` | `#0e1518` |
| ink | Primary text | `#142028` | `#e8eef1` |
| ink-muted | Secondary text | `#4a5c66` | `#a8b8c0` |
| accent | Petrol accent / links | `#1a5f6e` | `#5eb3c0` |
| accent-soft | Hover/soft accent | `#2a7a8c` | `#7ac4ce` |
| line | Hairlines | mix ink 12% | mix ink ~16% |
| on-accent | Text on accent buttons | `#f7fbfc` | `#0e1518` |

**Rules**: WCAG 2.2 AA for text and essential controls in both mappings. `color-scheme` and `html` `data-theme` reflect the **resolved** mapping. Visitor override is `localStorage` only.

### Visitor appearance preference

Browser-local override of the system palette. Not a CMS entity.

| Attribute | Description | Required | Notes |
|-----------|-------------|----------|-------|
| key | `pedro-ribeiro-appearance` | Yes | `localStorage` only |
| value | `light` or `dark` | When saved | Absent → follow system |
| control | Claro ↔ Escuro | Yes | Header; two options only |

**Rules**: No cookie. No admin force-dark. No third “System” value. Clearing site data restores follow-system.

### Locale

| Attribute | Portuguese (default) | English |
|-----------|----------------------|---------|
| WP locale | `pt_BR` | `en_US` |
| URL | unprefixed `/` | `/en/` |
| Home content | Original static front + ACF flex | Sister translation of that page + ACF flex |
| Menus | PT assignments | EN assignments (same locations) |
| Chrome strings | Theme default (PT msgids) | `languages/*.mo` for `en_US` |
| Escritos | Published posts in PT | Published posts in EN only |
| Now chrome | “Ouvindo”, “Última review”, … | English gettext equivalents |
| Now data | Same APIs; item titles as source | Same |

**Rules**: No Accept-Language redirect. Hide EN in the switcher and 404 `/en/` until a published EN home exists. Section IDs identical across locales.

### Translated home

| Attribute | Description |
|-----------|-------------|
| source_page | Static front (PT) |
| translation_page | Polylang sister (EN), same template / flex layouts |
| flex layouts | Same layout names as 001; empty layouts omitted per language |
| publication | EN home unpublished or missing → not found + no EN switcher option |

**Relationships**: One Locale has one Translated home (when published). Menu locations are assigned per Locale. Appearance palette is independent of Locale. Visitor appearance preference is per-browser, not per Locale.

## State transitions

### Primary / footer location

```text
unassigned or 0 items → fallback chrome
≥1 item              → render wp_nav_menu (depth 1)
```

### Appearance

```text
saved localStorage light | dark
  → that mapping + data-theme + color-scheme (ignore OS)

no saved value:
  prefers-color-scheme: light | no-preference → light tokens + data-theme=light
  prefers-color-scheme: dark                  → dark tokens + data-theme=dark
```

No cookie / admin-forced states. No third UI state `system`.

### English availability

```text
EN home missing/unpublished → switcher omits EN; GET /en/ → 404
EN home published           → switcher shows EN; /en/ renders sister ACF
```

### Language switch (home)

```text
hash in {#topo,#sobre,#projetos,#experiencia,#now,#escritos,#contato}
  → equivalent home URL + same hash
else
  → equivalent home URL (top)
```

## Validation / content rules

- Omit-empty ACF still applies on each sister page independently.
- Escritos query must remain language-scoped (do not disable Polylang filters).
- Forbidden labels: júnior, pleno, sênior, junior, senior (any spelling) in menus, ACF, gettext, meta.
- Now degrade rules unchanged from 001.
