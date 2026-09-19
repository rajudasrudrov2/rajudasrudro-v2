# RDR-V2-CASE-01 — QA Evidence

## Status

- Source/package implementation: `PASS`
- Updated Vercel certification: `PENDING`
- Overall milestone: `PARTIAL`

## Baseline

`RAJU_DAS_RUDRO_V2_RDR-V2-WORK-01_READY.zip`

Baseline package SHA-256 from accepted WORK-01 handoff:

`f937b36d55df7e157b05d7be7f6cb2edcd8db2c57db1ac01eaf9b3e858de2e7c`

## Type/data QA

Selected TypeScript modules compiled with global TypeScript 5.8.3 using an isolated QA tsconfig:

- `src/types/content.ts`
- `src/data/work.ts`
- `src/data/services.ts`
- `src/lib/work-projects.ts`

Result: `PASS`

## Route-state QA

Using emitted QA-only JavaScript from the actual source helper:

- development + available + development-preview environment: route eligible
- production + development-preview: route excluded
- known + pending: route excluded
- known + draft/unpublished: route excluded
- unknown slug: no route record
- development generated routes: `ai-ugc-campaign-preview`
- production generated development-preview routes: none

Result: `PASS`

## Data-integrity QA

- Project registry reused/extended: `PASS`
- Parallel project registry introduced: `No`
- Featured preview detail state is explicit: `PASS`
- Existing pending Work projects remain pending: `PASS`
- Related Work excludes current project: `PASS`
- Duplicate related IDs removed by helper: `PASS`
- Current Case Study project review mapping: none
- General review attached to Case Study: `No`
- Fabricated numerical business-performance metrics: `0`
- Current generated Case Study video URLs: `0`
- Fake rendered Play controls for current development record: `0`

## Functional-source QA

- functional source `href="#"` count: `0`
- one Case Study H1 in template: `PASS`
- primary media Play control only exists when `video.url` exists: `PASS`
- Related Service uses accepted service registry: `PASS`
- Related Work uses accepted Work registry: `PASS`
- final CTA uses `/contact/`: `PASS`
- Work active state remains inherited from accepted Header logic: `PASS`

## Media QA

All Work media referenced by the Case Study development record exist under `public/images/work/`.

- hero poster: present
- creative-variation posters: present
- highlight media: present
- explicit intrinsic dimensions: present
- below-fold gallery images: lazy-loaded through Case Study components
- autoplay: none
- third-party player/gallery library: none

## Visual QA

The Case Study composition was rendered in an isolated static QA harness derived from the implemented layout/data at:

- 1440px desktop: reviewed against the approved Case Study reference
- 390px mobile: reviewed against the approved Case Study reference
- intermediate/tablet: source responsive rules reviewed; the temporary WeasyPrint grid harness was not treated as authoritative browser certification because of CSS Grid limitations

Actual Vercel/browser visual certification remains pending.

## SEO/environment QA

Source-level:

- project-specific title: supported
- meta description: supported
- canonical pattern: `/work/[slug]/`
- OG image: supported
- development-preview page passes `noindex` to layout
- global non-production `noindex, nofollow`: preserved
- restrictive staging `robots.txt`: preserved
- sitemap now includes only production-eligible Case Studies and is still production-environment gated
- production canonical owner remains `https://rajudasrudro.com/`

Actual updated Vercel output: `PENDING`

## Regression QA

Baseline diff confirms the functional scope is limited to Case Study/data/routing/sitemap support and documentation. Shared Header, Footer, global tokens, Homepage source and Work page template were not rewritten.

`package-lock.json` remains byte-identical to WORK-01.

Lockfile SHA-256:

`942d470a4adc24692d1d312dd314c49e5e4b0b5c81e0a8b84cdb7b036e4066f6`
