# Raju Das Rudro V2 — Staging Environment Contract

## Environment switch

`PUBLIC_SITE_ENV` is the authoritative indexing switch.

Accepted values:

- `production` — indexable public behavior.
- `staging` — non-indexable staging behavior.
- `preview` — non-indexable preview behavior.
- `development` — non-indexable local/development behavior.

Missing or unrecognized values fail closed to non-indexable behavior. Only the exact value `production` enables indexing.

## Search-engine behavior

Non-production builds emit `<meta name="robots" content="noindex, nofollow">` on rendered pages. Their generated `robots.txt` disallows all crawling and their generated sitemap is an empty URL set.

Production builds emit normal `index, follow` page metadata unless a page explicitly requests `noindex`. Production `robots.txt` allows crawling and publishes the production sitemap.

Canonicals remain owned by `https://rajudasrudro.com/` in every environment. A staging or Vercel preview hostname is never promoted to permanent canonical ownership.

## Vercel staging setup

Create a separate Vercel project or isolated preview project for this repository. Do not attach `rajudasrudro.com` or modify production DNS.

Required staging environment variable:

`PUBLIC_SITE_ENV=staging`

Optional informational deployment origin:

`PUBLIC_SITE_URL=<isolated staging URL>`

Install command: `npm ci`

Build command: `npm run build`

Output directory: `dist`

The project remains a static Astro build; no production WordPress hosting or database is modified by this staging workflow.
