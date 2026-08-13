# Feature Specification: Pré-publish — menus, dark mode e i18n

**Feature Branch**: `002-pre-publish-readiness`

**Created**: 2026-08-13

**Status**: Draft

**Input**: User description: "Nova feature pré-publish do portfolio Pedro Ribeiro (após 001-portfolio-one-pager A–F). Três capacidades antes de publicar: (1) header/footer gerenciáveis via Aparência → Menus (locations primary + footer) com fallback se vazio; (2) dark mode: first visit follows prefers-color-scheme; compact public Claro ↔ Escuro control; visitor choice in localStorage overrides the system; anti-FOUC; light authored default; (3) PT default + EN em /en/ com Polylang free. Sem Multisite, sem custo extra de plugins, sem quebrar one-pager/Now/Escritos/omit-empty, sem labels júnior/pleno/sênior. User stories em fases A Menus → B Dark → C i18n."

## Clarifications

### Session 2026-08-13

- Q: When there is no published English home yet, what should a visitor see if they try to use English? → A: Hide English in the language control until an English home exists; `/en/` must not present Portuguese copy as English (treat as not found).
- Q: Should the site send visitors to English automatically based on the browser language? → A: No auto-redirect. Site root is always Portuguese. English only via `/en/` or the language control.
- Q: Should the home section anchors (`#projetos`, `#contato`, and the others) stay the same in English, or should English use translated IDs? → A: Same anchors in PT and EN (`#projetos` stays `#projetos`). Labels translate; IDs do not.
- Q: When a visitor switches language while viewing a home section, should they stay on that section or go to the top of the other-language home? → A: Preserve the hash: `/#projetos` ↔ `/en/#projetos` (and the same for other home sections).
- Q: For dark appearance, is “readable in visual review” enough, or must contrast meet a specific accessibility target? → A: Meet WCAG 2.2 AA contrast for text and essential controls in both light and dark.

### Session 2026-08-13 (appearance control)

- Q: Should appearance follow the system only, with no on-page control? → A: **Supersedes FR-009 “no toggle”.** Offer a compact public **Claro ↔ Escuro** control (exactly two options — not System / Light / Dark). No saved preference → follow `prefers-color-scheme` (first visit). Visitor choice → `localStorage` and apply light or dark explicitly (overrides the system). Minimal anti-FOUC script in `<head>`; CSS tokens via `data-theme="light"|"dark"` (or equivalent class) on `html`. No cookie; no admin “force dark”. Light remains the authored default when the system is `no-preference` and nothing is saved.

## User Scenarios & Testing *(mandatory)*

User stories are ordered for later task generation and **separate implementation chats**: Phase A (Menus) → Phase B (Dark) → Phase C (i18n). Each story is independently testable and can ship without the later phases.

### User Story 1 - Owner-managed header and footer menus (Priority: P1)

The site owner changes header and footer links from the WordPress admin (Aparência → Menus) without editing theme code. Two locations exist: **primary** (persistent in-page header nav) and **footer**. If a location has no menu assigned or the menu has no items, the public site keeps today’s hardcoded fallback so the chrome never looks broken.

**Why this priority**: Nav is currently hardcoded in the theme. Making it owner-editable is the smallest, safest pre-publish fix and does not depend on appearance or language work. **Phase A** of implementation.

**Independent Test**: Assign a primary menu with different labels/targets, refresh the public site, confirm the header updates; remove the assignment and confirm the original four section links return. Repeat for footer (empty = copyright only). Dark mode and English are not required.

**Acceptance Scenarios**:

1. **Given** the owner opens Aparência → Menus, **When** they assign a menu to the **primary** location and publish, **Then** the public header nav lists those items (labels and destinations) instead of the hardcoded list, while the compact persistent chrome and brand mark **PR** remain.
2. **Given** the primary location has no menu or an empty menu, **When** a visitor views any public page, **Then** the header shows the current fallback links: Sobre, Projetos, Experiência, Contato (section anchors on the home page), and the brand still links to the home top. On the English home, those fallback **labels** are English; the **hash targets** stay the same (`#sobre`, `#projetos`, `#experiencia`, `#contato`).
3. **Given** the owner assigns a menu to the **footer** location, **When** a visitor views the footer, **Then** those links appear in addition to the copyright line (“© {year} Pedro Ribeiro”).
4. **Given** the footer location has no menu or an empty menu, **When** a visitor views the footer, **Then** only the copyright line is shown (no empty navigation region).
5. **Given** a menu item points to a home section (e.g. Projetos or Contato), **When** it is activated from the home page or from an inner page (single/archive), **Then** the visitor reaches that section on the home page of the current language, using the same section hash in Portuguese and English.
6. **Given** menus are in use, **When** a recruiter uses the persistent header, **Then** it remains compact and MUST NOT overpower the brand in the first viewport (same constraint as the one-pager spec).

---

### User Story 2 - Appearance follows the system until the visitor chooses (Priority: P2)

A first-time visitor sees light or dark according to their device (`prefers-color-scheme`). They can override that with a compact **Claro ↔ Escuro** control in the header; the choice is stored in `localStorage` and then applied explicitly until they toggle again. Light remains the site’s authored default when the system does not request dark and nothing is saved. Dark is never the site-wide default.

**Why this priority**: Recruiters often browse at night; matching the system on first visit avoids a glaring light page, and a two-state control lets them keep a choice without a third “System” option. Independent of menus and language. **Phase B** of implementation.

**Independent Test**: Load with system light and no saved preference (current look, WCAG 2.2 AA); load with system dark and no saved preference (token-based dark, AA, first paint already dark); use the header control to override and confirm the choice survives reload; confirm OS changes only apply when nothing is saved. English and menu admin changes are not required.

**Acceptance Scenarios**:

1. **Given** the visitor’s system appearance is **light** and they have **no** saved appearance preference, **When** they open any public page, **Then** they see the existing cool off-white editorial look (light canvas, dark ink, petrol accent) with no dark overlay.
2. **Given** the visitor’s system appearance is **dark** and they have **no** saved appearance preference, **When** they open any public page, **Then** the page uses a dark mapping of the same tokens (dark canvas, light ink, petrol accent retained) and text plus essential controls (header, footer, nav, contact links) meet WCAG 2.2 AA contrast. First paint is already dark (no full-page light flash).
3. **Given** any public page, **When** the visitor looks at the header, **Then** a compact **Claro ↔ Escuro** control is present (exactly two options; not System / Light / Dark), does not overpower the brand or replace section nav, and is not a WordPress menu item.
4. **Given** no saved appearance preference, **When** the system appearance changes while the page is open, **Then** the page appearance updates to match without requiring navigation to another URL.
5. **Given** the visitor activates the appearance control, **When** they choose the opposite of the current palette, **Then** that light or dark mapping is applied immediately, saved in `localStorage` (not a cookie), and still applies after reload even if the system preference differs.
6. **Given** a saved appearance preference, **When** the system appearance changes while the page is open, **Then** the page keeps the saved light or dark mapping (system does not override the visitor’s choice).
7. **Given** dark appearance is active (from system or from a saved choice), **When** a visual review is done, **Then** the page still reads as editorial-technical (petrol accent, not generic “AI dark”: purple/indigo glow, neon gradients, or cream/terracotta inversion). Dark is **not** the authored default: system `no-preference` with nothing saved stays light; light-system visitors without a saved dark choice are not forced into dark.
8. **Given** dark appearance, **When** the visitor uses header, footer, Now, Escritos (if present), and contact links, **Then** those surfaces meet WCAG 2.2 AA contrast and no section becomes an unreadable ink-on-ink block.

---

### User Story 3 - Portuguese default and English at `/en/` (Priority: P3)

An English-speaking recruiter can read the same home narrative in English at `/en/`, while Brazilian Portuguese remains the default at the existing URLs. The owner translates the same home content (flexible sections already used on the one-pager), not a second unrelated page. Language switching is available without a second WordPress site and without a paid translation product.

**Why this priority**: Remote hiring is often English-first; this is the largest pre-publish slice and should land after chrome (menus) and appearance (dark) are stable. **Phase C** of implementation.

**Independent Test**: With Portuguese as default, open the root home and confirm PT copy. With no English home, confirm English is hidden in the language control and `/en/` is not found. With a published English home, open `/en/` (or the language control) and confirm the same section story in English; switch back. Menus and dark mode may already be present but this story is proven by language behavior alone.

**Acceptance Scenarios**:

1. **Given** a visitor opens the site root (no English prefix), **When** the home page loads, **Then** the default language is Brazilian Portuguese and the one-pager section order is unchanged—even if the browser prefers English. The site MUST NOT auto-redirect to `/en/`.
2. **Given** a published English home exists and a visitor opens the English home at `/en/` (or the English equivalent of the front page), **When** the page loads, **Then** they see the same professional story (hero, about, projects, experience, contact — plus Now/Escritos when those sections would show) in English, from translated home content, not a different information architecture.
3. **Given** a published English home exists and a visitor is on a Portuguese home section (e.g. `/#projetos`), **When** they use the language control, **Then** they reach `/en/#projetos` (same hash) in one activation; the reverse path returns them to the Portuguese home with that hash. If there is no hash, they reach the top of the equivalent home.
4. **Given** no published English home exists, **When** a visitor views the language control, **Then** English is not offered.
5. **Given** no published English home exists, **When** a visitor requests `/en/` (or the English home URL) directly, **Then** the response is not found and Portuguese body copy is not presented as English.
6. **Given** the owner edits the English translation of a home flexible section, **When** a visitor opens `/en/`, **Then** that updated English copy appears without a theme code deploy.
7. **Given** a translated field or optional block is empty in English, **When** the English home renders, **Then** that empty block is omitted (same omit-empty rule as Portuguese); the rest of the page still works.
8. **Given** Now sources succeed, **When** the English home shows Now, **Then** chrome labels are in English while live item names (track, game) may remain as provided by the source; failures still omit the part or the whole Now block with no error UI.
9. **Given** zero published posts in the current language, **When** the home page of that language loads, **Then** Escritos is omitted; posts that exist only in the other language MUST NOT populate an empty Escritos in this language.
10. **Given** public copy, metadata, and admin labels in either language, **When** content is reviewed, **Then** no career-level labels (júnior, pleno, sênior, junior, senior, or equivalents) appear.

---

### Edge Cases

- Primary menu empty or unassigned: restore hardcoded header links; do not render an empty `<nav>` list.
- Footer menu empty or unassigned: copyright only; do not render an empty footer nav.
- Menu item with a home hash target used from a post/archive URL: still lands on the home section, not a missing hash on the inner page. The same hashes work on `/en/` (IDs are not translated).
- Nested/sub-menus: out of scope for v1; owner is expected to keep a flat list matching the compact nav.
- System light and no saved preference: current light design unchanged (dark MUST NOT become the default look).
- System dark and no saved preference: first paint should already be dark (no obvious full-page light flash).
- Visitor has no preference / unknown (`no-preference`) and nothing saved: treat as light (authored default).
- Visitor saved light or dark in `localStorage`: apply that mapping even when the system differs; OS changes do not override until they toggle again. There is no third “System” control to return to follow-system (clearing site data restores follow-system).
- Appearance control MUST be compact in the header, MUST NOT overpower the brand, MUST NOT replace primary section nav, and MUST NOT be a menu item. Exactly Claro ↔ Escuro. No cookie. No admin “force dark”.
- Dark (or light) text/control contrast below WCAG 2.2 AA: treat as a defect, not a visual preference.
- Dark + English + assigned menus at once: all three compose; none disables another.
- English home not published: hide English in the language control; a direct `/en/` request is not found and MUST NOT show Portuguese body copy as English.
- Partial English translation: omit empty flexible blocks; show only filled English sections.
- Language plugin inactive (Phase A/B only, or plugin disabled): Portuguese one-pager, menus, and dark mode still work.
- Now API failure in either language: omit failed part or whole Now; never break the page.
- Escritos: unpublished or other-language-only posts do not create a false Escritos section.
- Language control MUST NOT overpower the brand or replace primary section nav.
- Language switch on home with a section hash: keep that hash on the equivalent-language home (`/#projetos` ↔ `/en/#projetos`). No hash: equivalent home top.
- Career-level wording in a translation: treat as a content defect in that language.
- Browser prefers English (or any other language) on a first visit to the site root: stay on Portuguese; do not redirect.

## Requirements *(mandatory)*

### Functional Requirements

**Phase A — Menus**

- **FR-001**: The owner MUST be able to manage header links via Aparência → Menus using a **primary** location, without editing theme files.
- **FR-002**: The owner MUST be able to manage footer links via Aparência → Menus using a **footer** location, without editing theme files.
- **FR-003**: When primary is unassigned or empty, the public header MUST fall back to the current hardcoded links (Sobre, Projetos, Experiência, Contato) and brand **PR** → home top. Fallback **href hashes** MUST stay the existing IDs in every language; only visible labels are translated.
- **FR-004**: When footer is unassigned or empty, the public footer MUST show only the copyright line; when assigned, footer links appear **with** the copyright line.
- **FR-005**: Assigned menus MUST keep the existing compact persistent header chrome; the nav MUST NOT overpower the brand in the first viewport.
- **FR-006**: Header/footer menu management MUST use the native WordPress menu UI (no extra paid menu plugin).

**Phase B — Dark appearance**

- **FR-007**: When the visitor has **no** saved appearance preference, public pages MUST follow the visitor’s system appearance: light system → current light editorial palette; dark system → dark mapping of the existing visual tokens (canvas, ink, accent and related token family).
- **FR-008**: Light MUST remain the authored default when the system does not request dark **and** nothing is saved (`no-preference` or unknown). Dark MUST NOT be the site default and MUST NOT be forced on light-system visitors who have not chosen dark.
- **FR-009**: Public pages MUST offer a compact header **Claro ↔ Escuro** control (exactly two options — not System / Light / Dark). The visitor’s choice MUST be stored in `localStorage` and applied as an explicit light or dark override of the system. The control MUST NOT overpower the brand. This version MUST NOT use a cookie, a query param, or an admin “force dark”.
- **FR-010**: Dark appearance MUST preserve editorial-technical identity (petrol accent; no generic “AI dark” aesthetics). Text and essential controls (header, footer, nav, contact links, and body copy) MUST meet WCAG 2.2 AA contrast in **both** light and dark appearance.
- **FR-011**: When nothing is saved, appearance MUST update when the system preference changes without requiring the visitor to open a different URL. When a preference is saved, that explicit light or dark mapping MUST persist across reloads and MUST NOT be overwritten by a later system change. First paint MUST already match the resolved appearance (anti-FOUC: minimal script in `<head>` plus CSS tokens keyed off `html` `data-theme` or equivalent class).

**Phase C — Languages**

- **FR-012**: Brazilian Portuguese MUST be the default public language at existing (unprefixed) URLs. The site MUST NOT auto-redirect visitors to English (or any other language) based on browser language. English is reached only via `/en/` or the language control.
- **FR-013**: English MUST be available under the `/en/` URL prefix for the equivalent public pages, including the home one-pager.
- **FR-014**: The English home MUST present the **same** home content model (flexible sections already used on the one-pager), translated, not a separate IA or a second site.
- **FR-015**: Visitors MUST be able to switch Portuguese ↔ English and land on the equivalent page via a compact language control that does not replace section navigation. The control MUST offer English only when a published English equivalent of the current page (for home: a published English home) exists. On the home page, the control MUST preserve a home section hash when present (`/#projetos` ↔ `/en/#projetos`, and the same for other home section IDs).
- **FR-015a**: When no published English home exists, a request for `/en/` (or the English home URL) MUST be treated as not found and MUST NOT render Portuguese body copy as English.
- **FR-016**: Bilingual delivery MUST use the free Polylang plugin on the single existing site. The product MUST NOT use WPML, TranslatePress Pro, WordPress Multisite, or any additional paid plugin for this feature.
- **FR-017**: Omit-empty rules MUST apply per language: empty translated fields/blocks are omitted; Escritos appears only when that language has published posts; Now chrome is translated; Now still degrades silently on source failure.
- **FR-018**: Theme chrome strings that visitors see (fallback nav labels, Now headings, language control, and other UI chrome) MUST be available in both languages.
- **FR-018a**: Home section element IDs MUST be identical in Portuguese and English (existing IDs, including at least `#topo`, `#sobre`, `#projetos`, `#experiencia`, `#contato`). Visible labels MAY differ; IDs MUST NOT be translated.

**Cross-cutting (all phases)**

- **FR-019**: This feature MUST NOT change one-pager section order, MUST NOT break Now (Last.fm / Backloggd behavior from 001), MUST NOT break conditional Escritos, and MUST NOT weaken omit-empty behavior for empty content fields.
- **FR-020**: Public copy, metadata, and admin labels in every language MUST NOT use career-level terms (júnior, pleno, sênior, or equivalents).
- **FR-021**: Work MUST be delivered on the existing custom theme and local Docker WordPress stack; it MUST NOT introduce a second CMS, headless front, contact form, or default-dark design.

### Key Entities

- **Menu location**: Named placement on the public chrome (`primary` header, `footer`). Has an assigned menu or is empty; empty triggers fallback.
- **Menu**: Owner-defined ordered list of labels and destinations (typically home section anchors plus optional extra links). Flat list in this version. Section hashes are language-stable (not translated).
- **Fallback chrome**: The current hardcoded header links and copyright-only footer used when a location is empty.
- **Appearance palette**: Light (authored) and dark mappings of the same token set: canvas, canvas-deep, ink, ink-muted, accent, accent-soft, line. Resolved from system preference unless the visitor has saved an explicit light or dark choice.
- **Locale**: Portuguese (default) or English (`/en/`). Owns translated home flexible content, chrome strings, menus per language, and which posts count for Escritos.
- **Translated home**: The same flexible home content as 001, with a Portuguese original and an English translation the owner maintains.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: An owner can change one header label or destination in the menu admin and see it on the public site within one refresh, without a code deploy, in under 2 minutes; clearing the primary assignment restores the four fallback links on the next public load (100% of such tests).
- **SC-002**: An owner can add a footer link the same way and see it next to the copyright line within one refresh; an empty footer assignment shows copyright only, with no blank link row, in 100% of tests.
- **SC-003**: With the device set to light appearance and no saved preference, visual review confirms the current light look is unchanged and text/essential controls meet WCAG 2.2 AA contrast. With the device set to dark and no saved preference, the same AA bar holds, first paint is already dark, at least 9 of 10 reviewers can still read body text, nav, and contact links without strain, and they recognize the same brand (name, petrol accent) within 10 seconds. The same AA bar holds when the visitor overrides via Claro ↔ Escuro.
- **SC-004**: On a walkthrough of header, footer, and home, testers find **exactly one** compact Claro ↔ Escuro appearance control in the header (two options only), can override the system, and see that choice survive a reload. Testers find **zero** three-way System/Light/Dark controls, cookies used for appearance, or admin “force dark”.
- **SC-005**: When a published English home exists, an English-speaking visitor can move from the Portuguese home to the English home in one activation and, within 2 minutes, identify name, focus, at least one project case, and a contact path—all in English. When it does not exist, testers find no English option in the language control, and a direct `/en/` request is not found (not Portuguese copy labeled as English).
- **SC-006**: Visiting the site root without an English prefix shows Portuguese in 100% of cold loads, including when the browser prefers English (no auto-redirect).
- **SC-007**: After this feature is on, regression checks still pass 001 behavior: Now omits failed parts (or the whole block); Escritos is absent when that language has zero published posts; empty optional content fields stay omitted; section order Hero → Sobre → Projetos → Experiência/Formação → Now → Escritos → Contato is unchanged.
- **SC-008**: Content review of Portuguese and English public copy, metadata, and admin labels finds zero career-level terms.
- **SC-009**: Pre-publish delivery adds no extra paid product and no second website: one public site, owner-managed menus, system-following appearance with an optional visitor override, and two languages (Portuguese default, English available).

## Assumptions

- Feature **001-portfolio-one-pager** (phases A–F) is already delivered on `wp-content/themes/pedro-ribeiro/`; this spec stacks on that one-pager rather than replacing it.
- Local Docker WordPress already exists; no new local stack is required.
- Header fallback items stay: Sobre, Projetos, Experiência, Contato; brand remains the text mark **PR** (not a menu item). English fallback uses translated labels with the same hashes (`#sobre`, `#projetos`, `#experiencia`, `#contato`).
- Footer fallback stays copyright-only; the owner may later add links (e.g. GitHub, LinkedIn) via the footer location.
- Menus are single-level. Per-language menus (Portuguese vs English) are expected once i18n ships; each language uses its own assignment with the same empty-location fallback rules.
- Dark appearance is CSS token remapping of the existing family (`canvas`, `ink`, `accent`, and related), applied via `html` `data-theme="light"|"dark"` (or equivalent class). First visit with nothing saved follows `prefers-color-scheme`. Visitor override is `localStorage` only (values `light` or `dark`). No cookie, no query param, no admin “force dark”. No third “System” option in the UI. Contrast for text and essential controls MUST meet WCAG 2.2 AA in light and dark.
- Constitution rule “dark mode MUST NOT be the default” is honored: light is the authored default when the system is `no-preference` (or unknown) and nothing is saved; dark applies when the system requests it **or** the visitor has saved dark.
- Default unknown/`no-preference` appearance is light when nothing is saved.
- Language switcher lives in the header as a compact control (e.g. PT | EN), does not replace section links, and does not overpower the brand. English appears in that control only after a published English home exists. Switching language on home preserves the current section hash when one is present.
- English URL prefix is `/en/` as requested; Portuguese has no prefix. No Accept-Language (or similar) auto-redirect; language is URL- and control-driven only.
- Home translation is the same ACF flexible home content, translated—not duplicated unrelated pages. Inner post templates from 001 remain and can be translated when posts exist; publishing a body of articles is still optional.
- Polylang **free** is the bilingual tool. WPML, TranslatePress Pro, other paid translation products, and WordPress Multisite are out of scope. No extra paid plugins for menus or dark mode.
- If Polylang is not active, the site stays Portuguese-only; menus and dark mode MUST still function (phases A and B do not depend on C).
- Now item titles (track, artist, game) may stay in the source language; UI labels (“Ouvindo” / English equivalent, “Última review” / English equivalent) are translated.
- 001 assumption “site language for v1 is Brazilian Portuguese only” is **superseded** for public chrome and home content: Portuguese remains default; English is in-scope for this pre-publish feature.
- Implementation and later `/speckit-tasks` grouping: **Phase A** = User Story 1 (menus), **Phase B** = User Story 2 (dark), **Phase C** = User Story 3 (i18n), in separate implement chats.
- This specification MUST remain consistent with `.specify/memory/constitution.md` (v1.1.0); conflicts favor the constitution unless amended. This feature does not require a constitution amendment if light stays the authored default (dark is not the site default) and YAGNI stays respected (no Multisite, no paid plugin suite, two-state appearance control rather than a preference-center).
