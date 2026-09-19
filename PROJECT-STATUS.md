# Raju Das Rudro V2 — Development Status

## Current milestone

**RDR-V2-SERVICES-01 — Global Typography Readability Correction + Services Page Implementation**

Source/package implementation: **`PASS`**

Updated online Vercel certification: **`PENDING`**

Overall milestone: **`PARTIAL`** until the updated GitHub commit is built and reviewed on the existing Vercel staging project.

## Authoritative baseline

Only this baseline was used:

`RAJU_DAS_RUDRO_V2_RDR-V2-CASE-01_READY_ICON-HOTFIX.zip`

Baseline SHA-256:

`9ba467b6ed89fa762dc652cc1c5333ebadfd5c3900c9542704448137fbbe574c`

Accepted chain preserved:

`RDR-V2-FOUNDATION-02A → RDR-V2-STAGING-01 → RDR-V2-HOME-01 → RDR-V2-WORK-01 → RDR-V2-CASE-01 → RDR-V2-CASE-01_ICON-HOTFIX`

Current staging:

`https://staging-dun.vercel.app/`

## PART A — Typography correction

Root cause was not viewport scale or root zoom. The source contained pervasive component-level micro typography, including many `.45rem`–`.83rem` rules. Shared semantic type tokens were expanded and components/pages were migrated away from those micro reading sizes.

Inter-first family direction remains unchanged. No verified WOFF2 existed in the baseline, so none was fabricated. No Google Fonts runtime loading was introduced.

## PART B — Services

`/services/` now implements the approved page composition with:

- four approved primary services
- AI UGC flagship presentation
- structured service data
- custom-project secondary note
- client-fit section
- Work-registry Recent Work bridge
- accepted three-step process
- accepted review/trust data
- service-selection guidance
- final CTA

Development preview media/project content remains explicitly identified and does not assert unverified business results.

## Regression preservation

- Header navigation IA preserved.
- Work filters/Load More JavaScript preserved byte-for-byte.
- Case Study interaction JavaScript preserved byte-for-byte.
- Dynamic Case Study route architecture preserved.
- Homepage/Work/Case layouts were not independently redesigned.
- Staging environment/SEO architecture unchanged.
- `package-lock.json` remains byte-identical to the baseline.

## Online certification gate

After GitHub update, verify Vercel `Ready`, route availability, normal-zoom readability, Services responsive layout, Work/Case functionality smoke tests, and staging SEO isolation.

## Production safety

**DO NOT DEPLOY LIVE.**

`https://rajudasrudro.com/`, production WordPress, DNS, database, redirects and production-domain mapping remain untouched.
