# Raju Das Rudro V2 — Development Status

## Current milestone

**RDR-V2-CASE-01 — Single Project / Case Study Page Implementation**

**Source/package implementation: `PASS`**

**Updated online Vercel certification: `PENDING`**

Overall task status remains **`PARTIAL`** until the updated GitHub commit is built and reviewed on the existing Vercel staging project.

## Authoritative baseline

This milestone uses only:

`RAJU_DAS_RUDRO_V2_RDR-V2-WORK-01_READY.zip`

That baseline preserves:

`RDR-V2-FOUNDATION-02A → RDR-V2-STAGING-01 → RDR-V2-HOME-01 → RDR-V2-WORK-01`

The deployed `/work/` milestone was accepted for progression before this Case Study implementation.

Current staging review URL:

`https://staging-dun.vercel.app/`

## Implemented in this milestone

- Reusable static `/work/[slug]/` Case Study route architecture
- Extended existing `WorkProject` model; no parallel project registry
- Explicit `publicationStatus`, `caseStudyStatus` and `developmentPreview` route rules
- Non-production development-preview Case Study route for template validation
- Production exclusion of development-preview Case Study routes
- Approved Case Study composition: hero, metadata, overview, challenge, approach, deliverables, creative gallery, outcome, testimonial state, related work and final CTA
- Poster-first optional-video component with no Play control when no source exists
- Honest project-specific review omission state
- Related-service resolution from the accepted service registry
- Related-work resolution from the accepted Work dataset
- Production sitemap architecture extended for future published/available Case Study routes only
- Case Study-specific SEO title, description, canonical and OG-image support

## Data safety

The implemented Case Study record is explicitly `development-preview`. It does not assert a real client identity, campaign result, date, testimonial or performance metric. Numerical business-success metrics are not used.

## Regression preservation

- Homepage was not redesigned.
- Work filters/Load More architecture was not rewritten.
- Existing ProjectCard system remains in use for related work.
- Header/Footer/navigation files were not modified.
- Global Inter typography remains unchanged.
- Staging indexing architecture remains intact.
- `package-lock.json` remains byte-identical to the accepted WORK-01 baseline.

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
- `/work/ai-ugc-campaign-preview/` loads on staging
- pending/unknown Case Study URLs remain unavailable
- `/work/` filters and Load More remain healthy
- Homepage bounded smoke check remains healthy
- no application-level console/missing-asset errors
- rendered staging `noindex, nofollow`
- restrictive staging `/robots.txt`
- safe staging sitemap behavior
- production domain remains unattached

Do not proceed automatically to the next page milestone before Planning review.
