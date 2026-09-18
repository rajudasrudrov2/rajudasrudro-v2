# Raju Das Rudro V2 — Development Status

## Current milestone

**RDR-V2-HOME-01 — Approved Homepage Visual Implementation + Global Typography Update**

**Source/package implementation: `PASS`**

**Updated online Vercel certification: `PENDING`**

Overall task status remains **`PARTIAL`** until the updated GitHub commit is built and reviewed on the existing Vercel staging project.

## Authoritative baseline

This milestone continues directly from the accepted `RDR-V2-STAGING-01` clean source package, which preserves approved `RDR-V2-FOUNDATION-02A` work. No older Foundation package was substituted.

Current staging review URL remains:

`https://staging-dun.vercel.app/`

## Implemented in this milestone

- Homepage composition based on the approved `IMG-WEB-HOME-001 Minimalist-Homepage` reference
- Reusable styling aligned to `IMG-WEB-SYSTEM-001 Global-UI-Kit-System-States`
- Inter-first global typography tokens for normal UI/content
- Approved Header and Mobile Navigation structure preserved
- Hero, trust metrics, Selected Work preview, Core Services, Featured Case Study placeholder state, Production Process, Client Reviews migration state, About preview, Final CTA and Footer
- Controlled development placeholder media/data where verified project/review/case-study content is not yet available
- No fake project results, reviewer identities, client metrics or working video playback were introduced
- Genuine existing `package-lock.json` preserved byte-for-byte
- Staging environment/indexing architecture preserved

## Typography delivery

Normal UI/content CSS now uses:

`Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif`

A local-first `@font-face` declaration resolves installed Inter variants with `font-display: swap`, then the centralized fallback stack applies. No Google Fonts runtime request or external typography runtime dependency is used. No font binary is included in the source package; therefore clients without a locally available Inter installation use the documented system fallback stack. This avoids font-network blocking and CLS from webfont fetching, while keeping Inter as the primary declared family.

## Placeholder/content dependencies

The following Homepage areas intentionally use controlled development states pending verified content migration:

- Hero AI UGC/product creative media
- Selected Work project media/titles
- Featured Case Study content/results
- Additional Client Review copy/buyer identities beyond the two accepted Foundation summaries

All are visibly marked as development/migration states and are structurally isolated in `src/data/home.ts` and `/public/images/home/`.

## Staging workflow

Authoritative review path remains:

`GitHub source → Vercel staging project → Vercel install/build logs → rendered staging verification`

Vercel contract remains:

- Install: `npm ci`
- Build: `npm run build`
- Output: `dist`
- Staging environment: `PUBLIC_SITE_ENV=staging`

## Production safety

**DO NOT DEPLOY LIVE.**

`https://rajudasrudro.com/`, production WordPress, DNS, database, redirects and production Vercel domain mapping remain untouched.

## Next certification gate

After uploading this milestone to the existing GitHub staging repository, review:

- Vercel deployment reaches `Ready`
- updated Homepage loads at `https://staging-dun.vercel.app/`
- no application-level console/missing-asset errors
- rendered `noindex, nofollow`
- restrictive staging `/robots.txt`
- safe staging sitemap behavior
- production domain remains unattached

No next-page visual implementation is authorized by this status file.
