<!--
Sync Impact Report
- Version change: 1.0.0 → 1.1.0
- Modified principles:
  - V. One-Pager Focado → section order includes Escritos (Posts);
    conditional render; minimal single/archive templates required
  - VIII. Simplicidade (YAGNI) → no longer bans future-ready posts slot;
    clarifies publishing articles is out of v1 obligation
- Added sections: none
- Removed sections: none
- Other changes:
  - Explicitly out of scope: "Blog" → "Conteúdo de blog/posts no v1
    (slot e templates são in-scope; publicar artigos é depois)"
- Follow-up TODOs: none
-->

# Portfólio Pedro Ribeiro Constitution

## Core Principles

### I. Conteúdo no CMS

Editorial copy and media MUST be editable in the WordPress admin via ACF
after the WordPress integration phase. Theme PHP MUST NOT hardcode final
editorial text or image URLs once ACF fields exist. During the front-first
phase only, hardcoded copy is allowed as a temporary stand-in.

**Rationale**: Recruiters and the owner update positioning without
redeploying code; the theme stays a presentation shell.

### II. Tema como Produto

The custom Underscores (_s) theme MUST use clean PHP templates, escape all
output with `esc_*` helpers, version ACF field groups as JSON in the repo,
and structure markup in partials per page section.

**Rationale**: The theme is the product surface. Sanitization, structure,
and versioned field schemas keep reviews and deploys predictable.

### III. Front Honesto

The UI MUST be real HTML, CSS, and JavaScript. Styling MUST use Tailwind
utilities with design tokens via `@theme`. Page builders are forbidden.
Vite MUST build theme assets during the WordPress phase.

**Rationale**: The portfolio demonstrates craft. Generated builder markup
and opaque stacks contradict the professional signal.

### IV. Design Editorial Técnico

The first viewport MUST read as one composition. Brand name **Pedro Ribeiro**
MUST be the dominant hero signal—not an eyebrow or nav-only label. No cards
in the hero. Design MUST avoid generic AI-default looks (purple/indigo
gradients, cream + terracotta + serif, broadsheet dense columns). Dark mode
MUST NOT be the default.

**Rationale**: A recruiter-facing one-pager needs a clear visual identity and
a brand-first first impression, not a template aesthetic.

### V. One-Pager Focado

The page section order MUST be: Hero → Sobre → Projetos → Experiência/Formação
→ Now → Escritos (Posts) → Contato. Skills MUST appear inside project cases,
not as a standalone skills grid. There MUST be no hobby section in primary
navigation or above the fold. The Now block is secondary and exists only to
demonstrate live API integration. Escritos MUST use native WordPress posts.
If there are zero published posts, the Escritos section MUST NOT render on
the one-pager. Minimal `single` and `archive` templates MUST exist in the
theme with an editorial style; publishing posts in v1 is optional. v1 MUST
NOT include a contact form—contact is links and curriculum PDF fields only.

**Rationale**: One job per scroll path keeps attention on hireability.
Now proves integration skill without competing with core narrative.
Escritos stays future-ready without forcing empty content on the page.

### VI. Integrações Resilientes

Now integrations (Last.fm official API; Backloggd unofficial API) MUST use
PHP (`wp_remote_get`) with WordPress transients for caching. API keys MUST
live only in environment / `wp-config` (never committed). When an API fails,
the Now section MUST degrade silently and MUST NOT break the rest of the
page.

**Rationale**: Third-party APIs are unreliable. Portfolio uptime and trust
matter more than live widgets.

### VII. Dev Reproduzível

Local development MUST use Docker Compose with WordPress, MySQL, and
phpMyAdmin. Setup MUST be documented in the repository. Secrets MUST stay
out of Git. phpMyAdmin MUST remain local-only and MUST NOT ship to
production.

**Rationale**: Any machine should reproduce the stack; production attack
surface stays minimal.

### VIII. Simplicidade (YAGNI)

v1 MUST NOT introduce headless frontends (Next/Astro), parallel CMS stacks
(Filament/Laravel as CMS), contact forms, or other scope beyond the agreed
one-pager + Now proxies + future-ready Escritos slot. The one-pager posts
slot and minimal editorial `single`/`archive` templates are in-scope and
MUST NOT be treated as prohibited "blog features." Publishing a body of
articles is not a v1 obligation. New complexity beyond this MUST be
justified against this constitution before implementation.

**Rationale**: Shipping a sharp one-pager beats a half-finished platform;
a reserved Escritos path avoids rework without requiring content now.

## Stack & Product Constraints

### Non-negotiable stack (v1)

- WordPress + custom theme from Underscores (_s)
- PHP, HTML, CSS, JavaScript
- Tailwind (deliberate learning) with Vite in the theme
- Content (text and images) administered via ACF after WP integration
- Docker Compose: WordPress + MySQL + phpMyAdmin (phpMyAdmin local only)
- Now: Last.fm + Backloggd via PHP proxies and transients
- Escritos: native WordPress posts; conditional one-pager section; minimal
  editorial single/archive templates

### Explicitly out of scope (v1)

- Headless (Next.js, Astro, or similar) as the public site
- Filament / Laravel as CMS
- Conteúdo de blog/posts no v1 (slot e templates são in-scope; publicar
  artigos é depois)
- Dark mode as default theme
- Contact form (links + CV PDF fields only)

### Product identity

- Display name: **Pedro Ribeiro**
- Positioning: Full-stack · WordPress & Laravel · Fortaleza / remoto
- Contact fields (WordPress): email, WhatsApp, LinkedIn, GitHub, CV PDF
- Copy, meta, and admin labels MUST NOT use career-level terms such as
  "júnior", "pleno", or "sênior" (any language/spelling variant)

## Development Order

This order is mandatory and overrides convenience-driven backend-first work.

1. **Front first**: Deliver a stable static one-pager with HTML, CSS, JS,
   and Tailwind. Temporary hardcoded copy is allowed until visuals and
   section structure are approved.
2. **WordPress second**: Only after the front is visually stable, integrate
   Underscores theme, ACF, Docker, and PHP API proxies for Now.

Starting with CMS/backend before the front is visually stable is a
constitution violation.

## Governance

This constitution supersedes informal preferences and ad-hoc shortcuts when
they conflict. Specs, plans, and tasks MUST align with these principles.

### Amendments

1. Propose the change with rationale (what principle/section, why).
2. Update `.specify/memory/constitution.md` with semantic version bump:
   - **MAJOR**: Remove or redefine a principle incompatibly
   - **MINOR**: Add a principle/section or materially expand guidance
   - **PATCH**: Clarifications, wording, non-semantic refinements
3. Set **Last Amended** to the amendment date (ISO `YYYY-MM-DD`).
4. Record a Sync Impact Report comment at the top of this file.
5. Communicate the amendment in the PR description when governance changes
   ship with code.

### Compliance

- Every PR and code review MUST check compliance with Core Principles,
  Stack & Product Constraints, and Development Order.
- Reviewers MUST reject changes that hardcode secrets, introduce out-of-scope
  v1 tech, add career-level labels in copy/meta/admin, or start WP/CMS work
  before front visual stability without an explicit constitution amendment.
- Justified exceptions MUST be documented in the PR and, if lasting, amended
  into this constitution.

**Version**: 1.1.0 | **Ratified**: 2026-08-10 | **Last Amended**: 2026-08-10
