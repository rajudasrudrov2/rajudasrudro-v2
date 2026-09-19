# RDR-V2-CASE-01 — Staging Handoff

## What changed

A reusable data-driven `/work/[slug]/` Case Study architecture was implemented on top of the accepted WORK-01 source.

The current staging-only development Case Study route is:

`/work/ai-ugc-campaign-preview/`

It is explicitly development-preview content and is not a verified client Case Study.

## Route rules

- `caseStudyStatus = available` is required.
- A Case Study data object is required.
- `publicationStatus = published` is eligible in production.
- `publicationStatus = development-preview` is eligible only outside production.
- `pending`, `draft` and unknown records do not create normal public detail routes.

## Online review URLs after deployment

- Case Study: `https://staging-dun.vercel.app/work/ai-ugc-campaign-preview/`
- Work regression: `https://staging-dun.vercel.app/work/`
- Homepage smoke: `https://staging-dun.vercel.app/`

## Required online checks

- Vercel deployment = `Ready`
- Case Study route opens
- pending/unknown detail URLs remain unavailable
- Work filters and Load More still operate
- no obvious application-level console errors
- staging HTML remains `noindex, nofollow`
- `/robots.txt` remains restrictive
- staging sitemap remains safe
- `rajudasrudro.com` remains unattached

No local npm command is required from the user.
