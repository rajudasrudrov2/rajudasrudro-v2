# RDR-V2-FOUNDATION-02 — QA Record

## Status

PARTIAL. Source hotfix work is complete. Dependency/install/build certification is blocked by npm registry DNS/network access in the execution environment.

## Dependency / lockfile attempt

Executed:

```sh
npm install --package-lock-only --ignore-scripts --fetch-retries=0 --fetch-timeout=5000
```

Result: FAIL.

Observed error:

```text
npm error code EAI_AGAIN
npm error syscall getaddrinfo
npm error request to https://registry.npmjs.org/@astrojs%2fcheck failed, reason: getaddrinfo EAI_AGAIN registry.npmjs.org
```

No `package-lock.json` was manually fabricated.

## Clean install

Executed:

```sh
npm ci
```

Result: FAIL because no valid lockfile could be generated first.

## Astro/type check

Executed:

```sh
npm run check
```

Result: BLOCKED / exit 127 because Astro dependencies are unavailable (`astro: not found`).

## Environment builds

Executed:

```sh
PUBLIC_SITE_ENV=development npm run build
PUBLIC_SITE_ENV=staging npm run build
PUBLIC_SITE_ENV=production npm run build
```

Result: BLOCKED / exit 127 for each build because Astro dependencies are unavailable (`astro: not found`).

Therefore generated HTML robots verification has not been claimed.

## Lint

No lint script is configured in the accepted Phase 1 `package.json`. No new lint stack was introduced for this hotfix.

## Source-level verification

PASS:

- Exact approved global navigation destination data is present.
- Brand remains the Home destination and Start a Project remains the Contact CTA.
- Header exposes semantic desktop and mobile navigation labels.
- Active links expose `aria-current="page"`.
- Mobile toggle exposes and updates `aria-expanded`.
- Mobile primary navigation remains available when JavaScript is unavailable.
- Exact authoritative Design Reference Manifest names are present.
- Public email equals `contactwithrudro@gmail.com`.
- Response commitment equals `Usually within 24 hours`.
- Contact fields use explicit visible labels and `for`/`id` associations.
- Required controls use native `required` semantics and visible indicators.
- Field errors have stable IDs and are associated with affected controls.
- Invalid controls can receive `aria-invalid` and first-invalid focus.
- Submitting uses a polite live status; success/failure have semantic status/alert handling and focus targets.
- Environment logic only enables indexing for explicit production.
- Non-production source logic emits `noindex, nofollow` and restrictive robots behavior.
- Non-production sitemap source logic returns no URL entries.
- Canonical ownership remains `https://rajudasrudro.com/`.
- No unapproved form provider was added.
- No production domain/DNS/WordPress deployment configuration was introduced.
- Header and contact inline JavaScript pass `node --check` syntax validation.
- No accidental repository file over 5 MB was introduced.

## Required follow-up in a network-enabled CI/staging environment

1. Run `npm install --package-lock-only` or `npm install` once to generate the genuine npm lockfile from the current graph.
2. Commit `package-lock.json`.
3. Remove `node_modules` and run `npm ci`.
4. Run `npm run check`.
5. Run development, staging and production builds.
6. Inspect generated staging HTML for `<meta name="robots" content="noindex, nofollow">`.
7. Inspect generated production HTML for normal indexable robots metadata.
8. Inspect staging and production `robots.txt` / `sitemap.xml` outputs.
9. Only after those checks pass, connect the repository to an isolated staging Vercel project with `PUBLIC_SITE_ENV=staging`.
