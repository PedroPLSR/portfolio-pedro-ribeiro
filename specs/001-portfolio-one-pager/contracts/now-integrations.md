# Contract: Now integrations

**Feature**: `001-portfolio-one-pager`  
**Phase**: 2 (phase 1 may omit or mock without live calls)  
**Date**: 2026-08-10

## Purpose

Define how the theme obtains and exposes current listening and gaming activity for the home Now section.

## Shared rules

| Rule | Requirement |
|------|-------------|
| Transport | `wp_remote_get` from PHP (theme `inc/`) |
| Cache | WordPress transients; suggested TTL 300s (tunable) |
| Timeout | Short connect/response timeout (e.g. 3–5s); failure ≡ empty |
| Secrets | `LASTFM_API_KEY` only in env / `wp-config`; never ACF/content |
| Config | Usernames + visibility toggles via ACF/config after owner sync |
| Output | At most one current item per source |
| UI | No error strings; omit part or whole section |

## Normalized internal shape

Theme helpers SHOULD return a common shape (names illustrative—not ACF keys):

```text
NowResult {
  listening: null | { title, artist, url?, image_url? }
  gaming:    null | { title, url? }
}
```

Render logic:

1. If toggle off or username missing → that side `null`.
2. If fetch/parse fails → that side `null`.
3. If source online but no *current* item → that side `null`.
4. If both `null` → do not render Now section markup.
5. If one non-null → render only that block inside Now.

## Last.fm — Ouvindo

| Item | Contract |
|------|----------|
| Endpoint | `https://ws.audioscrobbler.com/2.0/` |
| Method | `user.getRecentTracks` |
| Params | `user`, `api_key`, `format=json`, `limit=1` (or small limit) |
| Current? | First track has nowplaying attr true |
| Map | `name` → title; artist `#text` or `name` → artist; optional `url` / image |
| Else | `listening = null` (do not fall back to last scrobble) |

## Backloggd — Jogando

| Item | Contract |
|------|----------|
| Nature | Unofficial; public HTML of user’s playing list |
| Fetch | `wp_remote_get` to the profile playing URL for configured username |
| Current? | First game in the playing list |
| Map | Game title + optional absolute game URL |
| Else | `gaming = null` on empty list, HTTP error, or parse failure |

Parser selectors are implementation details; isolate in `inc/now-backloggd.php` so markup churn is localized.

## Transient keys (illustrative)

Use distinct keys per source, e.g. theme-prefixed `pr_now_lastfm`, `pr_now_backloggd`. Do not store API keys in transient values.

## Phase 1 mock (optional)

If a mock is shown in `frontend/`, it MUST be clearly non-authoritative and MUST NOT call real APIs with embedded secrets. Prefer omitting `#now` until phase 2.
