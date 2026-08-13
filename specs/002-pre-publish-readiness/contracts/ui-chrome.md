# Contract: UI chrome (menus, fallback, language control)

**Feature**: `002-pre-publish-readiness`  
**Audience**: Implementers (theme `header.php` / `footer.php` / setup)  
**Date**: 2026-08-13  
**Phases**: A (menus), B (appearance control), C (switcher). B adds the compact appearance control; it MUST NOT restyle the nav into a second brand or replace section links.

Extends [001 ui-home](../../001-portfolio-one-pager/contracts/ui-home.md). Section order and IDs from 001 remain required.

## Menu locations

| Location | Surface | Empty / unassigned |
|----------|---------|-------------------|
| `primary` | Header `.site-nav__list` | Fallback four section links |
| `footer` | Footer, with copyright | Copyright only; **no** empty `<nav>` |

- Register via `register_nav_menus`.
- Depth 1 only. No submenu markup.
- Native Aparência → Menus; no extra plugin.
- After Phase C, locations are assigned **per language**.

## Header

| Element | Requirement |
|---------|-------------|
| Brand | Hardcoded text **PR**; `href` = current-language home + `#topo`. Not a menu item. |
| Nav landmark | `aria-label` via gettext (current: “Seções”). |
| Assigned primary | `wp_nav_menu` items replace the hardcoded `<li>` list; keep `.site-nav__list` and compact persistent styles. MUST NOT overpower brand in the first viewport. |
| Fallback primary | Sobre → `#sobre`; Projetos → `#projetos`; Experiência → `#experiencia`; Contato → `#contato`. Labels gettext; **hashes never translated**. URLs via `home_url('/#…')` so inner pages reach home. |
| Appearance control | Phase B. Compact **Claro ↔ Escuro** in the header (after section nav; before the language control once C exists). Exactly two options. MUST NOT overpower brand, MUST NOT replace section links, MUST NOT be a menu item. See [appearance.md](./appearance.md). |
| Language control | Phase C only. Compact PT \| EN after section nav (and after the appearance control). MUST NOT replace section links. See [i18n.md](./i18n.md). |

Treat “assigned but zero items” the same as unassigned (use fallback).

## Footer

| Element | Requirement |
|---------|-------------|
| Copyright | Always: `© {year} Pedro Ribeiro` (year behavior unchanged). |
| Footer menu | Render only if `footer` has ≥1 item; appears **with** copyright. |
| Empty | No footer nav region. |

## Stable section IDs (PT and EN)

| ID | Section |
|----|---------|
| `#topo` | Hero |
| `#sobre` | Sobre |
| `#projetos` | Projetos |
| `#experiencia` | Experiência/Formação |
| `#now` | Now (conditional) |
| `#escritos` | Escritos (conditional) |
| `#contato` | Contato |

Do not rename IDs in English markup.

## Hash targets from inner pages

A menu or fallback item pointing at a home section MUST use the language home URL + hash (not `#projetos` alone on `single`/`archive`).

## Forbidden

- Nested menus in v1
- Career-level labels in menu text
- Appearance control as a WordPress menu item, a three-way System/Light/Dark picker, a cookie, or an admin “force dark”
- Language control that hides or replaces Projetos/Contato nav
