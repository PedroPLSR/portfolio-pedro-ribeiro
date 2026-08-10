# Contract: Home page UI

**Feature**: `001-portfolio-one-pager`  
**Audience**: Implementers (phase 1 `frontend/`, phase 2 theme partials)  
**Date**: 2026-08-10

## Surface

Single document home (phase 1: `frontend/index.html`; phase 2: `front-page.php` + `template-parts/*`).

## Global chrome

| Element | Requirement |
|---------|-------------|
| Persistent nav | Compact; section anchors; MUST include Projetos and Contato; MUST NOT overpower brand in first viewport |
| Language | pt-BR |
| Theme | Light default; no dark-mode default |
| Career labels | Forbidden in copy/meta |

## Section order & IDs

Suggested anchor IDs (stable for nav/CTAs):

| Order | Section | Anchor | Phase 1 | Phase 2 |
|-------|---------|--------|---------|---------|
| 1 | Hero | `#topo` or `#hero` | Required | Required |
| 2 | Sobre | `#sobre` | Required | Required |
| 3 | Projetos | `#projetos` | Required | Required |
| 4 | Experiência/Formação | `#experiencia` | Required | Required |
| 5 | Now | `#now` | Omit or non-blocking mock | Conditional render |
| 6 | Escritos | `#escritos` | Omit | Conditional (posts > 0) |
| 7 | Contato | `#contato` | Required | Required |

## Hero

- Full-bleed first composition; dominant text **Pedro Ribeiro**.
- One positioning line; CTAs **Ver projetos** → `#projetos`, **Contato** → `#contato`.
- No cards, badges, or stats.
- Typographic + atmospheric background acceptable; photo not required.

## Sobre

- 2–3 professional sentences; no hobby-primary content.

## Projetos

- 2–4 stacked cases (not card grid).
- Include Correnem + Index Digital work (Index may be anonymized).
- Skills only inside each case.
- Optional outbound links (live / repo / write-up): render control only when URL present.

## Experiência / Formação

- Compact timeline: Index Digital; UNIFOR (CC + pós).

## Now

- Secondary title conveying live integrations (e.g. “Integrações ao vivo”).
- Parts: **Ouvindo**, **Jogando** — each at most one current item.
- Omit failed/empty parts; omit whole `#now` if nothing to show.
- No error / unavailable messaging.
- Full behavior: [now-integrations.md](./now-integrations.md).

## Escritos

- List up to 3 recent published posts with links to single view.
- If zero posts: do not output section wrapper/heading.
- Archive + single templates exist in theme regardless.

## Contato

- Links only: email, WhatsApp, LinkedIn, GitHub, curriculum PDF.
- No contact form.
- Omit individual link if field empty.

## Motion (acceptance)

- At least: section entrance, case hover; optional now-playing cue when Now visible.
- Motion supports hierarchy; not decorative noise.

## Visual ban list

Must not ship: purple/indigo gradient defaults; cream + terracotta + serif pairing; broadsheet-dense columns; hero cards.
