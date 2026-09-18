# RDR-V2-HOME-01 — Source / Package QA

## Status

- Source implementation: `PASS`
- Package readiness: `PASS`
- Updated Vercel build/deployment: `PENDING`
- Updated live staging visual/SEO regression: `PENDING`

## Authoritative references used

Exactly these two approved references were used for this milestone:

1. `IMG-WEB-SYSTEM-001 Global-UI-Kit-System-States`
2. `IMG-WEB-HOME-001 Minimalist-Homepage`

No other page UI reference was required.

## Baseline preservation

Baseline: accepted `RDR-V2-STAGING-01` source preserving `RDR-V2-FOUNDATION-02A`.

The existing genuine `package-lock.json` remained byte-identical.

`package-lock.json` SHA-256:

`942d470a4adc24692d1d312dd314c49e5e4b0b5c81e0a8b84cdb7b036e4066f6`

## Source invariant QA

Programmatic source checks: `26/26 PASS` after final content/data centralization.

Covered:

- exact desktop navigation
- exact mobile navigation
- brand/Home and Contact CTA routes
- Inter-first global typography tokens
- no legacy Playfair/Georgia/Times family in normal source
- no Google Fonts runtime host references
- Homepage required section presence
- single explicit Homepage H1
- no `href="#"` fake links
- approved internal route references
- no deceptive Homepage video Play control without media
- development placeholder labeling
- reuse of the two accepted Foundation public-review summaries
- staging robots meta source logic
- restrictive non-production robots source logic
- staging sitemap guard
- canonical production ownership
- build-script preservation
- lockfile/package identity
- `.env.example`
- referenced Homepage assets present

## Type / script checks

Data modules validated with the available global TypeScript compiler:

`src/data/site.ts`

`src/data/home.ts`

Result: `PASS`.

Header inline JavaScript syntax checked with Node:

Result: `PASS`.

## Responsive composition preview

A QA-only static composition preview was generated from the same source CSS/assets. It is not part of the final package and is not a substitute for the Vercel Astro build.

Measured document widths:

- `390px` viewport → `390px` document width (`PASS`, no horizontal overflow)
- `768px` viewport → `768px` document width (`PASS`, no horizontal overflow)
- `1440px` viewport → `1440px` document width (`PASS`, no horizontal overflow)

Manual comparison against the approved Homepage reference confirmed the intended section order, Hero two-column/stack behavior, metrics grid, work-card geometry, three service groups, case-study split, three-step process, review-preview grid, About strip, dark CTA and compact Footer.

Exact final visual certification remains `PENDING` until the updated Astro source is deployed to Vercel staging.

## Typography QA

- Primary normal UI/content family: `Inter`
- Fallback: `system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif`
- Runtime Google Fonts: none
- External font runtime request: none
- `@font-face`: local-first Inter lookup
- `font-display: swap`
- Bundled webfont binary: none
- Font-network payload: `0 KB`
- Heading/body/nav/button/form inheritance uses the centralized Inter-first tokens

Because no Inter font binary exists in the authoritative baseline, clients without a local Inter installation will use the system fallback. This is documented and must be considered during online visual review.

## Accessibility source checks

- Primary action white-on-red contrast uses `#dc2626`: approximately `4.83:1` (`PASS` for normal text)
- Muted text `#64748b` on white: approximately `4.76:1`
- Standard blue link `#2563eb` on white: approximately `5.17:1`
- Mobile menu controls preserve `aria-expanded`, Escape close and focus restoration
- Homepage has one H1 and section `aria-labelledby` references resolve to real heading IDs
- Mobile interactive targets use practical minimum heights; primary mobile buttons are `44px` high
- Global reduced-motion rule remains intact

## Media / placeholder integrity

Controlled SVG development media is stored in:

`public/images/home/`

The media contains explicit development wording and no real-client claim.

No Homepage Play button is rendered for media without a real video source.

The Homepage review preview uses the two accepted Foundation public-review summaries from `src/data/reviews.ts`; only the additional third slot remains a clearly labeled migration placeholder.

## SEO/staging regression source checks

Source-level: `PASS`.

Preserved:

- `PUBLIC_SITE_ENV=staging` → non-indexable
- page robots → `noindex, nofollow`
- non-production robots → `Disallow: /`
- non-production sitemap protection
- production canonical owner → `https://rajudasrudro.com/`

Actual updated deployment verification: `PENDING`.

## Online gates still required

After GitHub upload/Vercel deployment:

- Vercel install/build success
- deployment `Ready`
- staging Homepage availability
- browser console/missing asset smoke check
- rendered robots meta
- `/robots.txt`
- sitemap isolation
- production-domain isolation
