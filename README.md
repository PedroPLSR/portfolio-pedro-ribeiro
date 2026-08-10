# Portfólio Pedro Ribeiro

Presença profissional one-page (recrutadores / remoto).

## Quickstart

Validação e cenários: [specs/001-portfolio-one-pager/quickstart.md](specs/001-portfolio-one-pager/quickstart.md)

### Front estático (Phase B)

```bash
cd frontend
npm install
npm run dev
```

### WordPress local (Phase C+)

Detalhes: [docker/README.md](docker/README.md). phpMyAdmin é **local-only** (`--profile local`); não usar em produção.

```bash
cd docker
cp .env.example .env
docker compose up -d
# opcional: docker compose --profile local up -d

cd ../wp-content/themes/pedro-ribeiro
npm install && npm run build
```

Ativar o tema **Pedro Ribeiro** em Appearance → Themes.

## Estrutura

- `frontend/` — one-pager estático (Vite + Tailwind)
- `docker/` — Compose local (WordPress + MySQL + phpMyAdmin local-only)
- `wp-content/themes/pedro-ribeiro/` — tema Underscores + Vite/Tailwind

WordPress core e dados MySQL vivem em volumes Docker, não neste repositório.
