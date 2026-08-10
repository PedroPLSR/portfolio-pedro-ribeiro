# Feature Specification: Portfólio One-Page Pedro Ribeiro

**Feature Branch**: `001-portfolio-one-pager`

**Created**: 2026-08-10

**Status**: Draft

**Input**: User description: "Portfólio one-page de Pedro Ribeiro (presença profissional para recrutadores / remoto), duas fases (front estático depois CMS), seções Hero→Sobre→Projetos→Experiência→Now→Escritos→Contato, sem rótulos de nível, alinhado à constitution."

## Clarifications

### Session 2026-08-10

- Q: When only one live activity source fails, should Now show only the working part or hide entirely until both work? → A: Show Now with only successful source(s); omit failed part(s) with no error UI
- Q: For each successful Now source, show only the current item or a short recent list? → A: Current item only per source (now playing / currently playing)
- Q: Should project cases include outbound links or stay text-and-media only? → A: Optional outbound links per case (live site / repo / write-up); omit link controls when empty
- Q: Besides hero CTAs, should the one-pager include persistent in-page navigation? → A: Compact persistent in-page nav (section anchors) in addition to hero CTAs
- Q: Must the hero full-bleed plane be real photography, or is typographic/atmospheric acceptable in v1? → A: Typographic + atmospheric background acceptable in v1; photo optional later

## User Scenarios & Testing *(mandatory)*

### User Story 1 - First impression in the hero (Priority: P1)

A recruiter opens the home page and, within the first viewport, understands who Pedro Ribeiro is and what he does (full-stack presence for remote work), then can jump to projects or contact.

**Why this priority**: Without a clear first impression, the portfolio fails its primary audience before any other section matters.

**Independent Test**: Open the home page on desktop and mobile; verify brand name dominance, positioning line, the two primary CTAs, and persistent in-page nav without requiring hero photography.

**Acceptance Scenarios**:

1. **Given** a visitor lands on the home page, **When** they view the first viewport, **Then** they see the name "Pedro Ribeiro" as the dominant brand signal, a single positioning line (full-stack · WordPress & Laravel · Fortaleza / remoto), and CTAs to view projects and contact—with no cards, badges, or stats in the hero—and a full-bleed composition that may be typographic/atmospheric without requiring photography.
2. **Given** a visitor anywhere on the home page, **When** they use the persistent in-page navigation, **Then** they can jump to major sections (at least Projetos and Contato) via section anchors without leaving the page.
3. **Given** a visitor in the hero, **When** they activate "Ver projetos", **Then** they are taken to the projects section on the same page.
4. **Given** a visitor in the hero, **When** they activate "Contato", **Then** they are taken to the contact section on the same page.
5. **Given** any public page copy, meta text, or owner-facing labels, **When** content is reviewed, **Then** no career-level labels (júnior, pleno, sênior, or equivalents) appear.

---

### User Story 2 - Evaluate professional work (Priority: P1)

A recruiter scrolls to projects and experience to assess fit: stacked case studies with context and skills inside each case, plus a compact experience/education timeline.

**Why this priority**: Hireability depends on concrete work evidence and career path, immediately after identity.

**Independent Test**: With only projects and experience content available, a recruiter can name at least two cases and summarize role/education without needing Now or Escritos.

**Acceptance Scenarios**:

1. **Given** the projects section, **When** a recruiter reviews it, **Then** they see 2–4 stacked case studies (not a card grid), including Correnem and Index Digital work (Index cases may be anonymized), each with context and skills listed only inside that case, and optional outbound links only when a URL is provided for that case.
2. **Given** the experience/education section, **When** a recruiter reviews it, **Then** they see a compact timeline covering Index Digital and UNIFOR (Computer Science + postgraduate study).
3. **Given** the about section, **When** a visitor reads it, **Then** they see 2–3 professional sentences with no hobby-focused content.

---

### User Story 3 - Reach contact and curriculum (Priority: P1)

A visitor finds how to reach Pedro and download the curriculum without filling a form.

**Why this priority**: Conversion for recruiters is contact and CV access; friction here loses opportunities.

**Independent Test**: From the contact section alone, verify all five link types work and that no contact form is present.

**Acceptance Scenarios**:

1. **Given** the contact section, **When** a visitor views it, **Then** they see links for email, WhatsApp, LinkedIn, GitHub, and a PDF curriculum—and no contact form.
2. **Given** each contact link, **When** activated, **Then** it opens the expected channel or file (mail, chat, profile, or PDF).

---

### User Story 4 - Live "Now" without risking the site (Priority: P2)

A visitor may see a secondary "Integrações ao vivo" block showing current listening and gaming activity when external sources respond; if they fail, the rest of the site remains intact.

**Why this priority**: Demonstrates integration craft but must never undermine the hireability narrative.

**Independent Test**: Load the page with sources available and with sources failing; confirm graceful behavior and that primary sections always remain usable.

**Acceptance Scenarios**:

1. **Given** both live sources respond successfully with a current item each, **When** a visitor reaches Now, **Then** they see "Ouvindo" and "Jogando" each showing a single current item (not a recent history list) in a secondary block titled as live integrations.
2. **Given** one live source fails or times out while the other succeeds, **When** the page loads, **Then** Now still appears with only the successful source(s), omits the failed part with no error UI, and the rest of the page remains intact.
3. **Given** both live sources fail or time out, **When** the page loads, **Then** the entire Now section is omitted (no broken UI), and hero through contact remain fully usable.
4. **Given** the page structure, **When** sections are ordered, **Then** Now appears after experience/education and before Escritos.

---

### User Story 5 - Escritos only when there is writing (Priority: P2)

A visitor sees a writings section with recent published posts only when at least one post exists; otherwise the section is absent. Dedicated reading and listing views exist for when posts are published later.

**Why this priority**: Future-ready presence without empty marketing sections on a recruiter-facing page.

**Independent Test**: With zero published posts, confirm Escritos is absent; with one or more posts, confirm the section lists recent items and individual/list views open correctly.

**Acceptance Scenarios**:

1. **Given** zero published posts, **When** a visitor views the home page, **Then** the Escritos section is not rendered (no empty heading or placeholder).
2. **Given** one or more published posts, **When** a visitor views the home page, **Then** Escritos appears after Now and before Contato, listing recent posts (up to three).
3. **Given** a published post, **When** a visitor opens it from Escritos or a direct link, **Then** they see a minimal editorial single view; an archive/list view is also available.

---

### User Story 6 - Owner updates content without code (Priority: P3)

After the content-management phase is delivered, the site owner updates texts and images for hero, about, projects, experience, and contact through an admin interface without changing theme code. Now only needs account/toggle settings; secrets stay outside the content UI.

**Why this priority**: Ongoing maintenance; depends on the second delivery phase after the visual front is stable.

**Independent Test**: Change a hero sentence and a project image in admin, refresh the public page, and confirm updates appear without a code deploy.

**Acceptance Scenarios**:

1. **Given** the content-management phase is live, **When** the owner edits hero, about, projects, experience, contact, or media fields in admin, **Then** the public one-pager reflects those changes.
2. **Given** Now configuration, **When** the owner sets usernames or toggles visibility in admin, **Then** they cannot enter API secrets in content fields (secrets remain in environment configuration only).

---

### Edge Cases

- Both live activity sources fail simultaneously: omit the entire Now section; primary sections remain fully usable with no broken UI.
- Only one live source succeeds: show Now with only the successful part; omit the failed part with no error or “unavailable” message.
- A source responds successfully but has no current item (nothing playing / in progress): omit that source’s part; if both lack a current item, omit Now entirely.
- Zero project cases temporarily during editing: do not invent filler cases; section may show available cases only (owner responsibility to keep 2–4).
- Zero published posts: Escritos omitted entirely from home.
- Missing curriculum PDF or a contact field: omit that specific link rather than showing a dead control; remaining links still work.
- Project case with no outbound URL: omit project link controls for that case; case still shows context, skills, and media.
- Very long case descriptions on mobile: content remains readable without horizontal overflow; stacked layout preserved.
- Anonymized Index cases: client names may be generalized while role, problem, and skills remain clear.
- Career-level wording accidentally entered in admin: copy guidelines forbid it; reviews treat such labels as defects.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: The product MUST present a single-page professional presence with sections in this order: Hero → Sobre → Projetos → Experiência/Formação → Now → Escritos → Contato.
- **FR-002**: Delivery MUST follow two phases: (1) a visually complete static front with temporary hardcoded copy allowed; (2) only after front visual stability, a content-managed site that ports the approved markup, live Now data, and conditional Escritos.
- **FR-003**: The hero MUST be a full-bleed first composition dominated by the name "Pedro Ribeiro", with one positioning line and CTAs "Ver projetos" and "Contato", and MUST NOT include cards, badges, or stats. For v1, a typographic composition on an atmospheric (non-photo) background is acceptable; photography is optional and MUST NOT be required to declare visual stability.
- **FR-003a**: The home page MUST include a compact persistent in-page navigation with section anchors (including at least Projetos and Contato) in addition to the hero CTAs. The nav MUST NOT overpower the brand in the first viewport.
- **FR-004**: Sobre MUST contain 2–3 professional sentences and MUST NOT feature hobbies as primary content.
- **FR-005**: Projetos MUST present 2–4 stacked case studies (not a card grid), include Correnem and Index Digital work (Index may be anonymized), and place skills only inside each case. Each case MAY include optional outbound links (live site, repository, or write-up); link controls MUST be omitted when no URL is set for that case.
- **FR-006**: Experiência/Formação MUST present a compact timeline including Index Digital and UNIFOR (Computer Science + postgraduate study).
- **FR-007**: Now MUST be a secondary "Integrações ao vivo" block. For each live source that succeeds with a current item, Now MUST show exactly that one current item (listening: now playing; gaming: currently playing)—not a recent-history list. If only some sources have a current item, Now MUST render only those part(s) with no error UI. If no source has a current item (failure or empty), Now MUST be omitted entirely. Failures MUST NOT break the rest of the page.
- **FR-008**: Escritos MUST list recent native posts when at least one is published (up to three on the home section) and MUST NOT render on the home page when there are zero published posts.
- **FR-009**: Minimal editorial single and archive views for posts MUST exist even if no posts are published yet.
- **FR-010**: Contato MUST offer email, WhatsApp, LinkedIn, GitHub, and curriculum PDF links and MUST NOT include a contact form.
- **FR-011**: Public copy, metadata, and admin labels MUST NOT use career-level terms (júnior, pleno, sênior, or equivalents).
- **FR-012**: Visual design MUST read as editorial-technical: expressive typography, cool off-white atmospheric background, a single accent color (e.g. petrol blue), light motion (section entrance, case hover, now-playing cue when applicable), usable on mobile and desktop, and MUST avoid generic AI-default aesthetics (purple/indigo gradients, cream+terracotta+serif pairing, broadsheet-dense columns). Dark mode MUST NOT be the default.
- **FR-013**: After phase 2, the owner MUST be able to edit the majority of texts and images for hero, about, projects, experience, contact, and media through admin without code changes.
- **FR-014**: Now admin MUST expose only configuration such as usernames and visibility toggles; API credentials MUST NOT be stored in content fields.
- **FR-015**: The product MUST NOT include headless public frontends, a parallel CMS stack, a contact form, default dark mode, mandatory blog content in v1, or a hobbies section.

### Key Entities

- **Profile**: Display name, positioning line, about text, contact channels, curriculum file.
- **Project case**: Title, summary/context, role, skills used, optional media, optional anonymization for client naming, optional outbound links (live site / repository / write-up).
- **Experience / education entry**: Organization or school, role or program, period, short note.
- **Now activity**: At most one current listening item and one current gaming item from external sources; optional visibility toggles; omit empty or failed parts; omit whole Now if nothing current to show.
- **Written post**: Title, excerpt/summary, publication date, body; drives Escritos visibility on home.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: In moderated first-impression tests, at least 9 of 10 recruiters can correctly state Pedro’s name and professional focus within 10 seconds of landing on the hero.
- **SC-002**: A recruiter can identify and summarize at least two project cases (problem/context + skills) in under 3 minutes of review.
- **SC-003**: A visitor can open a working contact channel or the curriculum PDF in under 2 clicks from the contact section, with zero contact-form fields present; from mid-page, a visitor can reach Contato via persistent in-page navigation in one activation.
- **SC-004**: When all live activity sources are unavailable, the Now section is omitted and 100% of primary sections remain readable and navigable with no layout-breaking errors; when only some sources succeed, visitors see only those parts with no error messaging.
- **SC-005**: With zero published posts, 100% of home page loads omit the Escritos section; with one or more posts, Escritos appears in the correct order and links to readable post views.
- **SC-006**: After phase 2, an owner can update a hero text and a project image via admin and see both on the public page within one refresh, without deploying code.
- **SC-007**: On common mobile and desktop widths, the first viewport remains a single coherent composition and no section requires horizontal scrolling for core content.
- **SC-008**: Visual acceptance review confirms expressive typography, cool off-white atmosphere, a single accent, light intentional motion, absence of the banned generic aesthetics and default dark mode, and that a typographic/atmospheric hero (without required photography) still reads as one brand-first composition.

## Assumptions

- Primary audience is recruiters and remote-hiring managers; secondary audience is peers and collaborators.
- Site language for v1 is Brazilian Portuguese.
- Positioning line defaults to: Full-stack · WordPress & Laravel · Fortaleza / remoto.
- Escritos on the home page shows at most the three most recent published posts when any exist.
- Phase 1 static front may omit Escritos entirely (equivalent to zero posts) until phase 2 wires native posts.
- Phase 1 may hardcode copy and images; phase 2 replaces editorial fields with admin-managed content per constitution.
- Live listening and gaming sources are the product-named integrations (Last.fm and Backloggd) required by project governance; exact technical wiring is deferred to planning. Each successful source contributes at most one current item to Now.
- Local reproducible environment and secret handling follow project constitution; phpMyAdmin remains local-only.
- Index Digital cases may use generalized client names while keeping truthful role and outcome descriptions.
- Project outbound links are optional per case; absence of a URL is not a defect.
- "Visual stability" of phase 1 means hero-through-contact layout, typography, color, motion, and persistent in-page nav accepted before CMS work starts; hero photography is not a gate for phase 1.
- A typographic + atmospheric hero is an accepted v1 visual path; adding a photo later is optional enhancement.
- Publishing a body of articles is optional in v1; templates and conditional slot are enough.
- This specification MUST remain consistent with `.specify/memory/constitution.md` (v1.1.0); conflicts favor the constitution unless amended.
