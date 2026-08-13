# Docker — WordPress local stack

Local-only Compose: **WordPress** + **MySQL** + **phpMyAdmin** (optional profile).

WordPress core and DB data live in Docker volumes. This repo mounts `../wp-content` (theme source). **Do not commit WordPress core.**

## Setup

```bash
cd docker
cp .env.example .env
# Edit .env: DB creds, ports, LASTFM_API_KEY (when needed)
```

## Up / down

WordPress + MySQL only:

```bash
docker compose up -d
docker compose down
```

With phpMyAdmin (local development):

```bash
docker compose --profile local up -d
docker compose --profile local down
```

Defaults (override in `.env`):

| Service     | URL / port                          |
|-------------|-------------------------------------|
| WordPress   | http://localhost:8080               |
| phpMyAdmin  | http://localhost:8081 (profile `local`) |
| MySQL       | localhost:3306                      |

## First run

1. Open WordPress URL → complete install wizard.
2. In theme folder: `npm install && npm run build`.
3. Appearance → Themes → activate **Pedro Ribeiro**.
4. Install/activate ACF; field group JSON lives in the theme `acf-json/` (synced by owner).
5. Set a static front page (Reading settings) and fill `flex_content` layouts in admin.
6. `LASTFM_API_KEY` belongs in `docker/.env` (or `wp-config`); never in ACF.

## Production note

**phpMyAdmin is local-only.** Deploy theme + plugins + DB content on the host. Do not run the `phpmyadmin` service (or Compose `local` profile) in production.
