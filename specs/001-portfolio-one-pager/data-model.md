# Data Model: Portfólio One-Page Pedro Ribeiro

**Feature**: `001-portfolio-one-pager` | **Date**: 2026-08-10

## Purpose

Conceptual content model for the one-pager and Now integrations. Used as a **guide for the owner when creating ACF field groups** and for theme wiring after JSON sync.

**Important**: This document does **not** invent ACF field keys, group keys, or JSON schemas. After the owner exports ACF JSON into `wp-content/themes/pedro-ribeiro/acf-json/`, implementers map attributes below to the real keys in that export.

## Entities

### Profile

Site-wide identity and contact used by Hero, Sobre, Contato, and meta.

| Attribute | Description | Required | Notes |
|-----------|-------------|----------|-------|
| display_name | Public name | Yes | Default “Pedro Ribeiro” |
| positioning_line | One-line focus | Yes | e.g. Full-stack · WordPress & Laravel · Fortaleza / remoto |
| about_text | 2–3 professional sentences | Yes | No hobby-primary copy |
| email | Mailto target | Optional* | Omit link if empty |
| whatsapp_url | Chat URL | Optional* | Omit if empty |
| linkedin_url | Profile URL | Optional* | Omit if empty |
| github_url | Profile URL | Optional* | Omit if empty |
| curriculum_file | PDF or media ref | Optional* | Omit download if empty |

\*At least some contact channels expected in production; empty fields omit controls (spec edge case).

**Validation / content rules**:
- No career-level labels (júnior / pleno / sênior) in any text attribute or admin label.
- Phase 1: hardcoded in HTML. Phase 2: ACF (likely options page or front-page fields)—exact placement chosen by owner.

### Project case

One stacked case in Projetos (2–4 instances).

| Attribute | Description | Required | Notes |
|-----------|-------------|----------|-------|
| title | Case title | Yes | |
| summary | Context / problem | Yes | |
| role | Owner’s role | Yes | |
| skills | List of skills used | Yes | Rendered only inside the case |
| media | Image(s) | Optional | |
| client_label | Display client/org name | Optional | May be anonymized for Index work |
| link_live | Live site URL | Optional | Omit control if empty |
| link_repo | Repository URL | Optional | Omit if empty |
| link_writeup | Case study URL | Optional | Omit if empty |
| sort_order | Display order | Optional | Owner-managed |

**Relationships**: Many Project cases belong to one Profile / front page.

**Rules**: Skills never appear as a standalone page section. Include Correnem + Index Digital work among the set (Index may use generalized client names).

### Experience / education entry

Compact timeline rows for Experiência/Formação.

| Attribute | Description | Required | Notes |
|-----------|-------------|----------|-------|
| entry_type | experience \| education | Yes | Or equivalent labeling |
| organization | Company or school | Yes | Index Digital; UNIFOR |
| title | Role or program | Yes | CC + pós for UNIFOR |
| period | Date range or years | Yes | |
| note | Short description | Optional | |

**Relationships**: Ordered list on the home page.

### Now configuration

Admin-tunable settings for live integrations (not API secrets).

| Attribute | Description | Required | Notes |
|-----------|-------------|----------|-------|
| lastfm_username | Last.fm user | Optional | Needed if Ouvindo enabled |
| backloggd_username | Backloggd user | Optional | Needed if Última review enabled |
| show_listening | Hide toggle for Ouvindo (ACF label "Esconder…") | Optional | Checked = hide; unchecked = expose (default) |
| show_gaming | Hide toggle for Última review (ACF label "Esconder…") | Optional | Checked = hide; unchecked = expose (default) |

**Secrets (not ACF content)**:
- `LASTFM_API_KEY` — environment / `wp-config` only.

### Now activity (runtime, not stored editorially)

Derived at request time from external sources + transients.

| Attribute | Description | Rules |
|-----------|-------------|-------|
| listening | Track (title, artist, optional art/url) | Prefer nowplaying; else last scrobble; absent on fail/empty |
| gaming | Latest review (title, image_url?, review, rating?, url?) | First `.review-card` on `/reviews/`; absent on fail/empty/parse |

**State**:
- Both absent → do not render Now section.
- One present → render only that part.
- Listening UI label always "Ouvindo"; Backloggd label always "Última review".
- Review text truncated to ~100 characters + “…” in UI.
- Never persist as editorial CMS content.

### Written post

Native WordPress `post` type.

| Attribute | Source | Notes |
|-----------|--------|-------|
| title | WP | |
| excerpt | WP | Home list |
| date | WP | |
| body | WP | Single view |
| status | WP | Only `publish` on home |

**Rules**: Home Escritos lists up to 3 latest published posts; if zero, section not rendered. `single` / `archive` templates always exist in theme.

## Phase mapping

| Entity | Phase 1 | Phase 2 |
|--------|---------|---------|
| Profile | Hardcoded HTML | ACF after owner JSON sync |
| Project case | Hardcoded blocks | ACF repeater/flexible (owner-defined) |
| Experience entry | Hardcoded timeline | ACF repeater (owner-defined) |
| Now configuration | N/A or mock | ACF toggles/usernames; key in env |
| Now activity | Omit or mock | PHP fetchers + transients |
| Written post | Section omitted | Native WP posts + conditional partial |

## Owner checklist (ACF)

Before theme wiring tasks:

1. Create field groups covering Profile, Project cases, Experience entries, Now configuration.
2. Attach groups to the intended location (front page / options—owner choice).
3. Export/sync JSON into `acf-json/`.
4. Notify implementers that keys are ready for `get_field` mapping.
