# RDR-V2-HOME-01 — Staging Update Handoff

## Package purpose

This package updates the existing staging Foundation with the first approved full Homepage visual milestone and the Inter-first global UI/content typography system.

It does not deploy or modify production.

## Browser-only update workflow

1. Download and extract the `RDR-V2-HOME-01` ready ZIP.
2. Open the existing GitHub staging repository in the browser.
3. Upload/replace the extracted source using the same repository-root structure.
4. Keep `package.json`, `package-lock.json`, `.env.example`, `src/`, `public/`, `astro.config.mjs`, `tsconfig.json` and `vercel.json` at the project root.
5. Do not upload any real `.env` file.
6. Commit the update in GitHub web UI.
7. Open the already connected Vercel staging project.
8. Confirm the new deployment starts from the new GitHub commit.
9. Vercel must retain `PUBLIC_SITE_ENV=staging`.
10. Wait for deployment status `Ready`.
11. Open `https://staging-dun.vercel.app/` and hard refresh.
12. Review mobile and desktop Homepage layouts.

Vercel continues to run `npm ci` and `npm run build` online. No user-side local npm command is required.

## Important source additions

New Homepage-specific source/data/components:

- `src/data/home.ts`
- `src/components/Icon.astro`
- `src/components/HomeHeroMedia.astro`
- `src/components/HomeWorkCard.astro`
- `src/components/HomeServiceGroupCard.astro`
- `src/components/HomeReviewPlaceholder.astro`
- `public/images/home/*`

## Deleted source files

None.

## Post-deployment review evidence

Bring back:

- updated staging URL if Vercel changes it, otherwise confirm `https://staging-dun.vercel.app/`
- whether deployment status is `Ready`
- screenshot/copied Vercel build error only if the build fails

No credentials/tokens are required.
