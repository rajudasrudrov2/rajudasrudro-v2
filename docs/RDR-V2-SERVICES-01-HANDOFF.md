# RDR-V2-SERVICES-01 — Staging Handoff

## অবস্থা

Source/package: `PASS`

Online Vercel certification: `PENDING`

Overall: `PARTIAL`

## কী পরিবর্তন হয়েছে

1. Shared typography readability system corrected first.
2. `/services/` approved composition implemented using the corrected shared type system.
3. Existing Homepage, Work and Case Study interaction architecture preserved.
4. Existing `package-lock.json`, environment controls and Vercel contract preserved.

## Staging workflow

Existing GitHub staging repository-তে final package-এর source replace/update করতে হবে। `.env` upload করা যাবে না; `.env.example` এবং `package-lock.json` রাখতে হবে। Connected Vercel project নতুন commit build করবে।

Current staging URL:

`https://staging-dun.vercel.app/`

Review routes:

- `/`
- `/work/`
- `/services/`
- `/work/ai-ugc-campaign-preview/`

Staging environment must remain:

`PUBLIC_SITE_ENV=staging`

Production domain must remain untouched.
