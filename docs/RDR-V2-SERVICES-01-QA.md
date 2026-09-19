# RDR-V2-SERVICES-01 — QA Report

## সামগ্রিক অবস্থা

- Source/package implementation: `PASS`
- Updated Vercel certification: `PENDING`
- Overall milestone: `PARTIAL`

## Authoritative baseline

`RAJU_DAS_RUDRO_V2_RDR-V2-CASE-01_READY_ICON-HOTFIX.zip`

Baseline SHA-256:

`9ba467b6ed89fa762dc652cc1c5333ebadfd5c3900c9542704448137fbbe574c`

Accepted chain preserved:

`FOUNDATION-02A → STAGING-01 → HOME-01 → WORK-01 → CASE-01 → CASE-01_ICON-HOTFIX`

## PART A — Root-cause audit

### Actual cause

Root `html` font size বা viewport scale defect ছিল না। `body` আগে `1rem` ছিল এবং browser default root scale 16px ছিল। আসল সমস্যা ছিল component/page-level micro typography override: baseline source-এ 164টি numeric `font-size` declaration 13px-এর নিচে ছিল, এবং minimum ছিল 7.2px-equivalent (`.45rem`)। Header, Footer, Work cards, Case Study metadata/body, Homepage previews এবং control text-এর বহু অংশ `.45rem`–`.83rem` range-এ ছিল।

### Contributing factors

- Body line-height ছিল `1.5`, যা ছোট type-এর সঙ্গে dense অনুভূতি তৈরি করছিল।
- Heading line-height/letter-spacing খুব compact ছিল (`1.04`, `-.035em`)।
- Shared components semantic type tokens-এর বদলে বহু literal micro-size ব্যবহার করছিল।

### Not a cause

- Viewport: `width=device-width,initial-scale=1` — correct.
- CSS `zoom`: none.
- Root/page `transform: scale(...)`: none.
- Container max-width 1140px: approved UI system-এর সঙ্গে consistent; এটি primary cause নয়।
- Inter family direction: preserved; font-family swap cause হিসেবে ধরা হয়নি।

## Shared typography tokens — before / after

| Role | Before | After |
|---|---:|---:|
| Hero | `clamp(2.35rem, 4.45vw, 4rem)` | `clamp(2.4rem, 4.6vw, 4.25rem)` |
| H1 | `clamp(2rem, 3.4vw, 2.75rem)` | `clamp(2.25rem, 3.6vw, 3.5rem)` |
| H2 | `clamp(1.55rem, 2.35vw, 2rem)` | `clamp(1.625rem, 2.35vw, 2.25rem)` |
| H3 | `clamp(1.05rem, 1.6vw, 1.25rem)` | `clamp(1.125rem, 1.5vw, 1.375rem)` |
| Body large | `clamp(1rem, 1.45vw, 1.12rem)` | `clamp(1.0625rem, 1.2vw, 1.1875rem)` |
| Body | `1rem` | `clamp(1rem, calc(.94rem + .15vw), 1.0625rem)` |
| Body small | `.875rem` | `.9375rem` |
| Card title | literal per component | `1.125rem` token |
| Navigation | literal `.72rem` | `.9375rem` token |
| Button | literal `.78rem` | `.9375rem` token |
| Metadata | literal micro sizes | `.875rem` token |
| Eyebrow | literal `.69rem` | `.8125rem` token |
| Caption | `.75rem` | `.8125rem` |
| Body line-height | `1.5` | `1.62` |
| Heading line-height | `1.04` | `1.08` |

After correction, numeric declarations below 13px dropped from 164 to 3; the three remaining values are decorative glyph/status-scale items rather than substantive reading copy.

## Font delivery

- Primary family: `Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif`
- Verified bundled Inter WOFF2: none in baseline and none fabricated.
- Runtime Google Fonts requests: `0`
- `@font-face` remains local-first with `font-display: swap`.

## Regression checks

- Header inline JS unchanged from baseline: `PASS`
- Work filter/Load More inline JS unchanged from baseline: `PASS`
- Case Study media inline JS unchanged from baseline: `PASS`
- Header/Work/Case inline JS `node --check`: `PASS`
- Package lock byte-identical to baseline: `PASS`
- Source QA invariants: `PASS` (post-correction source audit)
- Selected TypeScript data modules using isolated `types: []` QA config: `PASS`
- `href="#"` in source functional links: `0`
- Google Fonts source references: `0`
- Legacy serif family references: `0`

## PART B — Services page

Implemented `/services/` with:

- accepted global Header/Footer
- Services hero + honest development-preview media collage
- four approved primary services
- AI UGC flagship state
- custom AI project secondary note
- client-fit grid
- existing Work-registry Recent Work bridge
- three-step process reused from accepted Homepage data
- accepted public-review summaries + accepted trust metrics
- service-selection guidance
- final CTA
- unique SEO title/description/canonical input

No fifth primary service was added.

## Data integrity

Development-preview media/project records are visibly and structurally identified. No new fabricated client, testimonial or numerical performance claim was introduced.

## Responsive / visual certification

Source responsive rules were implemented for 390px, tablet and desktop. A QA-only static renderer was also attempted, but it does not reproduce Astro/browser layout reliably enough to count as authoritative visual certification. Therefore final 390/768/1440 browser certification remains tied to the updated Vercel deployment.

## Online certification still required

After GitHub update and Vercel deployment:

- Vercel build reaches `Ready`
- `/`, `/work/`, `/services/` and current Case Study route load
- normal browser zoom readability is accepted
- `/services/` visual comparison at 390px / 768px / 1440px
- Work filters / Load More smoke test
- Case Study dynamic route smoke test
- rendered `noindex, nofollow`
- restrictive `/robots.txt`
- staging sitemap isolation
- production domain remains unattached
