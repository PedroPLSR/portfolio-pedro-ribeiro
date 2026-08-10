# Portfólio Pedro Ribeiro

Presença profissional one-page (recrutadores / remoto).

## Quickstart (fase atual)

Validação e cenários manuais: [specs/001-portfolio-one-pager/quickstart.md](specs/001-portfolio-one-pager/quickstart.md)

```bash
cd frontend
npm install
npm run dev
```

## Estrutura

- `frontend/` — one-pager estático (Vite + Tailwind); prioridade até estabilidade visual
- `docker/` — Compose local (WordPress + MySQL + phpMyAdmin) **após** o gate visual
- `wp-content/themes/` — tema custom (WordPress core **não** entra no Git; só via Docker)

WordPress core e dados MySQL vivem em volumes Docker, não neste repositório.
