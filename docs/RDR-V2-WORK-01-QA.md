# RDR-V2-WORK-01 — QA Evidence

## Status

- Source/package implementation: `PASS`
- Updated Vercel staging certification: `PENDING`
- Overall milestone: `PARTIAL` until the updated GitHub commit is deployed and reviewed on Vercel.

## Authoritative baseline

- `RAJU_DAS_RUDRO_V2_RDR-V2-HOME-01_READY.zip`
- Current staging review URL: `https://staging-dun.vercel.app/`

## Source checks

- Work page H1 count: `1`
- Unique Work SEO title/description/path: `PASS`
- Desktop navigation source preserved: `PASS`
- `href="#"` in Work implementation: `0`
- Fake case-study detail links in development dataset: `0`
- Structured featured state: `PASS`
- Development-preview project status: `PASS`
- Typed Work project model: `PASS`
- Work inline JavaScript syntax (`node --check`): `PASS`
- Header inline JavaScript syntax (`node --check`): `PASS`
- Isolated TypeScript check for `src/types/content.ts`, `src/data/site.ts`, and `src/data/work.ts`: `PASS`

## Rendered QA harness

A browser-rendered QA harness was produced from the same Work markup, component styles, data states and interaction script to catch layout and interaction defects before packaging. This is supplementary source QA; the authoritative deployment certification remains the updated Vercel build.

Measured widths:

- `390px`: document width `390px`, horizontal overflow `0px`
- `768px`: document width `768px`, horizontal overflow `0px`
- `1440px`: document width `1440px`, horizontal overflow `0px`

Initial visible Work-card counts:

- `390px`: `4`
- `768px`: `8`
- `1440px`: `12`

Load More progression at mobile width:

`4 → 8 → 12 → 16`

Filter QA at `390px`:

- All: `PASS`
- AI UGC: `PASS`
- AI Video: `PASS`
- AI Spokesperson: `PASS`
- Web Design: `PASS`
- Web Development: `PASS`
- `aria-pressed` active state: `PASS`
- hidden cards removed from rendered layout: `PASS`
- page JavaScript errors during harness test: `0`

A defect found during QA—mobile CSS overriding the HTML `hidden` state—was corrected with `.project-card[hidden]{display:none!important}` before packaging.

## Data / integrity

- Project dataset: controlled development-preview data only
- Fabricated client names: `0`
- Fabricated business-result metrics: `0`
- Fabricated case-study routes: `0`
- Real video URLs in Work development dataset: `0`
- Visible fake Play controls: `0`
- `package-lock.json` preserved byte-for-byte from HOME-01 baseline: `PASS`
- lockfile SHA-256: `942d470a4adc24692d1d312dd314c49e5e4b0b5c81e0a8b84cdb7b036e4066f6`

## Online gates still pending

After GitHub upload and Vercel deployment, verify:

- Vercel deployment `Ready`
- `/work/` opens successfully
- Work filters and Load More on deployed page
- no application console errors
- `PUBLIC_SITE_ENV=staging`
- rendered `noindex, nofollow`
- restrictive `/robots.txt`
- staging sitemap isolation
- Homepage regression smoke check on `/`
- `rajudasrudro.com` remains unattached
