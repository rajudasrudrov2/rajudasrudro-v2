# RDR-V2-WORK-01 — Staging Handoff

## Package purpose

This package implements the approved `/work/` portfolio milestone on top of the accepted `RDR-V2-HOME-01` source. It is intended only for the existing GitHub → Vercel staging workflow.

## Work implementation

- Approved Work introduction and filter composition
- Featured project development-preview state
- Typed, CMS-replaceable local project data
- Four-column desktop / compact mobile project-card system
- Accessible category filters with `aria-pressed`
- Responsive initial result limits (`4 / 8 / 12`)
- Functional Load More in increments of `4`
- Controlled zero-results state
- No fake case-study detail routes
- No fake video playback
- Existing Header, Inter typography, CTA system and Footer reused

## Important content state

Portfolio entries in this milestone are development-preview records for layout and interaction certification only. They must be replaced by verified project/client/media data before production. No fabricated business outcome is presented as verified fact.

## Staging contract

- Install: `npm ci`
- Build: `npm run build`
- Output: `dist`
- Environment: `PUBLIC_SITE_ENV=staging`
- Staging URL: `https://staging-dun.vercel.app/`

Do not connect `rajudasrudro.com` to Vercel.

## Review URLs after deployment

- `https://staging-dun.vercel.app/work/`
- `https://staging-dun.vercel.app/`
- `https://staging-dun.vercel.app/robots.txt`
- staging sitemap endpoint from the accepted Foundation
