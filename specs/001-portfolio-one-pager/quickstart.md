# Quickstart: Portfólio One-Page Pedro Ribeiro

**Feature**: `001-portfolio-one-pager` | **Date**: 2026-08-10

Validation guide for phase 1 (static) and phase 2 (WordPress). See [contracts/ui-home.md](./contracts/ui-home.md) and [contracts/now-integrations.md](./contracts/now-integrations.md) for acceptance detail; [data-model.md](./data-model.md) for content entities.

## Prerequisites

- Node.js LTS (Vite)
- Docker Desktop (phase 2)
- Last.fm API key (phase 2 Now)
- Backloggd public username (phase 2 Now)
- ACF plugin + owner-exported JSON (phase 2 content wiring)

---

## Phase 1 — Static front

### Setup

```bash
cd frontend
npm install
npm run dev
```

Open the local Vite URL.

### Validation scenarios

| ID | Scenario | Expected |
|----|----------|----------|
| P1-01 | First viewport | “Pedro Ribeiro” dominant; positioning line; CTAs Ver projetos / Contato; no cards/badges/stats; typographic/atmospheric OK |
| P1-02 | Persistent nav | Anchors reach at least Projetos and Contato from mid-page |
| P1-03 | Section order | Hero → Sobre → Projetos → Experiência → Contato; Escritos absent; Now absent or clearly mock/non-blocking |
| P1-04 | Projetos | 2–4 stacked cases; skills inside cases; optional links only when URL set |
| P1-05 | Contato | email, WhatsApp, LinkedIn, GitHub, CV PDF links; no form |
| P1-06 | Copy audit | No júnior/pleno/sênior labels |
| P1-07 | Responsive | Mobile + desktop; no horizontal scroll on core content; light motion present |
| P1-08 | Visual ban | Not purple/indigo default, not cream+terracotta+serif, not broadsheet |

### Gate

Phase 1 **visual stability** accepted → only then start phase 2.

---

## Phase 2 — WordPress (local)

### Setup (outline)

1. Copy `docker/.env.example` → `docker/.env`; set DB + `LASTFM_API_KEY`.
2. From `docker/`: `docker compose up -d` (ensure phpMyAdmin is local-only service/profile).
3. Install WordPress in the browser; install/activate ACF; activate theme `pedro-ribeiro`.
4. Owner: create ACF groups from [data-model.md](./data-model.md); sync JSON to `acf-json/`.
5. Theme: `npm install && npm run build` (or `dev`) inside the theme for Vite assets.
6. Fill content in admin; set Now usernames/toggles; keep API key out of ACF.

### Validation scenarios

| ID | Scenario | Expected |
|----|----------|----------|
| P2-01 | Markup port | Same section order/visual language as accepted phase 1 |
| P2-02 | ACF edit | Change hero text + project image in admin → public page updates on refresh |
| P2-03 | Now both live | Ouvindo + Jogando each show one current item when sources have current activity |
| P2-04 | Now partial | Disable or break one source → only the other part shows; no error UI |
| P2-05 | Now empty | Both fail/empty → `#now` not rendered; rest of page OK |
| P2-06 | Escritos empty | Zero published posts → Escritos section absent |
| P2-07 | Escritos with posts | Publish ≥1 post → section lists up to 3; single/archive usable |
| P2-08 | Secrets | API key not visible in admin content fields or front HTML source as a dedicated field |
| P2-09 | Git hygiene | Repo has no WordPress core tree; `.env` not committed; phpMyAdmin not required for prod docs |

### Production note

Deploy theme + plugins + DB content only. Do not run phpMyAdmin in production.

---

## Related artifacts

- [plan.md](./plan.md)
- [research.md](./research.md)
- [data-model.md](./data-model.md)
- [spec.md](./spec.md)
