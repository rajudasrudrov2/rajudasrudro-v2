# Raju Das Rudro V2 — Development Status

## Current milestone

**RDR-V2-STAGING-01 — Clean Staging Package + Vercel Deployment Readiness**

**Package readiness: `PASS`**

**Online Vercel certification: `PENDING`**

এই milestone-এ `RDR-V2-FOUNDATION-02A`-এর approved functional source অপরিবর্তিত রেখে clean GitHub/Vercel-ready staging package প্রস্তুত করা হয়েছে। Genuine existing `package-lock.json` সংরক্ষণ করা হয়েছে এবং local/generated/sensitive files staging package থেকে বাদ দেওয়া হয়েছে।

## Authoritative source

এই staging package-এর functional source `RDR-V2-FOUNDATION-02A` source state-এর সাথে preserved। `package-lock.json`-সহ supplied latest source থেকে package তৈরি করা হয়েছে; কোনো পুরোনো Phase 1/Foundation source substitute করা হয়নি।

## Staging workflow

এই milestone থেকে build certification-এর authoritative path:

`GitHub source → Vercel staging project → Vercel install/build logs → rendered staging verification`

User-এর local PC-তে Node.js/npm/Astro install বা local build certification প্রয়োজন নেই।

## Vercel contract

- Framework: `Astro`
- Install command: `npm ci`
- Build command: `npm run build`
- Output directory: `dist`
- Staging environment: `PUBLIC_SITE_ENV=staging`

## Search-engine protection

`PUBLIC_SITE_ENV=staging` হলে accepted source architecture:

- rendered page robots directive: `noindex, nofollow`
- `robots.txt`: crawling blocked
- staging sitemap: production URL set exposed নয়
- canonical ownership: `https://rajudasrudro.com/`

Only exact `PUBLIC_SITE_ENV=production` indexing enable করতে পারে। Missing/unknown/non-production values fail closed to non-indexable behavior।

## Production safety

**DO NOT DEPLOY LIVE.**

`https://rajudasrudro.com/` এবং current production WordPress website untouched থাকবে। Production DNS/domain mapping/database/redirects এই milestone-এর scope নয়।

## Next certification gate

Separate Vercel staging project-এ deploy করার পর Planning/Coding review-এ আনতে হবে:

- staging URL
- Vercel deployment status (`Ready` হলে উল্লেখ)
- build log error থাকলে screenshot বা copied log

এরপর online certification-এ `npm ci`, `npm run build`, staging `noindex, nofollow`, `robots.txt`, sitemap এবং production-domain isolation verify করতে হবে।

## Scope lock

কোনো Homepage visual implementation, page-by-page visual implementation, CMS migration, unrelated architecture refactor বা production deployment শুরু করা হয়নি।
