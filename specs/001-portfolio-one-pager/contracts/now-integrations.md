# Contract: Now integrations

**Feature**: `001-portfolio-one-pager`  
**Phase**: 2 (phase 1 may omit or mock without live calls)  
**Date**: 2026-08-10 (updated 2026-08-13)

## Purpose

Define how the theme obtains and exposes live listening and the latest public Backloggd review for the home Now section.

## Shared rules

| Rule | Requirement |
|------|-------------|
| Transport | `wp_remote_get` from PHP (theme `inc/`) |
| Cache | WordPress transients; suggested TTL 300s (tunable) |
| Timeout | Short connect/response timeout (e.g. 3–5s); failure ≡ empty |
| Secrets | `LASTFM_API_KEY` only in env / `wp-config`; never ACF/content |
| Config | Usernames + hide toggles via ACF/config after owner sync (checked “Esconder…” = hide source; unchecked = expose) |
| Output | At most one item per source |
| UI | No error strings; omit part or whole section; listening label always "Ouvindo"; Backloggd label always "Última review" |

## Normalized internal shape

Theme helpers SHOULD return a common shape (names illustrative—not ACF keys):

```text
NowResult {
  listening: null | { title, artist, url?, image_url? }
  gaming:    null | { title, image_url?, review, rating?, url? }
}
```

Notes:

- Key `gaming` is kept for continuity with ACF `show_gaming` / aggregator; the payload is a **latest review**, not a currently-playing game.
- `review` = plain review body text (theme truncates to ~100 characters + “…” in UI).
- `rating` = optional 0–5 (or equivalent) derived from `.stars-top` width percentage when present.
- `url` = optional absolute URL to the review or game page.

Render logic:

1. If hide toggle checked or username missing → that side `null`.
2. If fetch/parse fails → that side `null`.
3. Listening: prefer nowplaying; else most recent scrobble; empty track list → `null`. Backloggd: no parseable first `.review-card` → `null`.
4. If both `null` → do not render Now section markup.
5. If one non-null → render only that block inside Now.

## Last.fm — Ouvindo

| Item | Contract |
|------|----------|
| Endpoint | `https://ws.audioscrobbler.com/2.0/` |
| Method | `user.getRecentTracks` |
| Params | `user`, `api_key`, `format=json`, `limit=1` (or small limit) |
| Prefer | First track has nowplaying attr true |
| Fallback | If no nowplaying, use the first recent track (last scrobble) |
| Map | `name` → title; artist `#text` or `name` → artist; optional `url` / image |
| Else | `listening = null` only on failure or empty track list |
| Label | Always "Ouvindo" (no alternate for fallback) |

## Backloggd — Última review

| Item | Contract |
|------|----------|
| Nature | Unofficial; public HTML of user’s reviews list |
| Fetch | `wp_remote_get` to `https://www.backloggd.com/u/{username}/reviews/` |
| Select | First `.review-card` on the page |
| Map | Game name → `title`; cover → `image_url?`; review body → `review`; `.stars-top` width% → `rating?`; optional absolute review/game link → `url?` |
| Else | `gaming = null` on empty list, HTTP error, bot wall, or parse failure |
| Label | Always "Última review" (MUST NOT use "Jogando") |
| UI text | Truncate `review` to about 100 characters with an ellipsis |

Parser selectors beyond `.review-card` / `.stars-top` are implementation details; isolate in `inc/now-backloggd.php` so markup churn is localized. Do **not** use `/playing/`.

## Transient keys (illustrative)

Use distinct keys per source, e.g. theme-prefixed `pr_now_lastfm`, `pr_now_backloggd`. Do not store API keys in transient values.

## Phase 1 mock (optional)

If a mock is shown in `frontend/`, it MUST be clearly non-authoritative and MUST NOT call real APIs with embedded secrets. Prefer omitting `#now` until phase 2.
