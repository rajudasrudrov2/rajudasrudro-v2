# RDR-V2-FOUNDATION-02A — QA Record

## সামগ্রিক অবস্থা

`PARTIAL`

Navigation correction, accepted hotfix regression preservation, `.env.example` restoration এবং source-level QA সম্পন্ন হয়েছে। তবে npm registry DNS/network access অনুপলব্ধ থাকায় genuine `package-lock.json`, `npm ci`, Astro/type check, build matrix এবং generated-output staging/production indexing certification সম্পন্ন করা যায়নি।

## ব্যবহৃত বেসলাইন

শুধুমাত্র:

`RAJU_DAS_RUDRO_V2_RDR-V2-FOUNDATION-02_PARTIAL.zip`

কোনো পুরোনো Phase 1 package ব্যবহার করা হয়নি।

## Navigation correction

Desktop primary navigation:

```text
Work | Services | About | Insights
```

Desktop primary navigation-এ `Home`, `Reviews`, `Contact` নেই।

Brand/Home:

```text
Brand → /
```

Primary CTA:

```text
Start a Project → /contact/
```

Mobile textual navigation:

```text
Work | Services | About | Insights | Reviews | Contact
```

Header এখন general route list filter না করে explicit `headerNavigation.desktopPrimary` এবং `headerNavigation.mobile` configuration ব্যবহার করে।

## Navigation source QA

`PASS`

Programmatic source assertions: `39/39 PASS` (navigation, contact regression, environment source behavior, `.env.example`, manifest invariants সহ)।

Header inline JavaScript syntax:

```text
node --check /tmp/header-inline.js
HEADER_JS=PASS
```

Contact inline JavaScript regression syntax:

```text
node --check /tmp/contact-inline.js
CONTACT_JS=PASS
```

## Accepted hotfix regression preservation

নিচের accepted files baseline SHA-256-এর সঙ্গে byte-identical রয়েছে:

- `src/pages/contact.astro`
- `docs/DESIGN-REFERENCE-MANIFEST.md`
- `src/config/environment.ts`
- `src/layouts/BaseLayout.astro`
- `src/pages/robots.txt.ts`
- `src/pages/sitemap.xml.ts`
- `src/lib/seo.ts`
- `vercel.json`
- `.gitignore`
- `package.json`

অর্থাৎ Contact accessibility, manifest normalization, environment fail-closed logic, robots/sitemap/canonical architecture এবং Vercel foundation নতুন করে পরিবর্তন করা হয়নি।

## `.env.example`

Continuation baseline ZIP-এ `.env.example` উপস্থিত ছিল না। এই task-এ তৈরি করা হয়েছে।

```env
PUBLIC_SITE_ENV=development
PUBLIC_SITE_URL=http://localhost:4321
PUBLIC_CONTACT_ENDPOINT=
```

কোনো secret যোগ করা হয়নি। `.gitignore` real `.env` files ignore করে এবং `.env.example` allow করে।

## Dependency / lockfile gate

Package manager: `npm`

`package.json` baseline থেকে অপরিবর্তিত; dependency intent/graph declaration পরিবর্তন করা হয়নি।

প্রথম normal attempt:

```sh
npm install
```

Execution environment registry access না পাওয়ায় command timeout হয়েছে এবং কোনো lockfile তৈরি হয়নি।

তারপর bounded diagnostic/generation attempt:

```sh
npm install --package-lock-only --ignore-scripts --fetch-retries=0 --fetch-timeout=5000
```

Result: `BLOCKED`

Raw error:

```text
npm error code EAI_AGAIN
npm error syscall getaddrinfo
npm error errno EAI_AGAIN
npm error request to https://registry.npmjs.org/@astrojs%2fcheck failed, reason: getaddrinfo EAI_AGAIN registry.npmjs.org
```

`package-lock.json` manually তৈরি করা হয়নি।

## Install QA

Command:

```sh
npm ci
```

Result: `BLOCKED`

Upstream lockfile generation network block-এর কারণে lockfile অনুপস্থিত। Raw npm result:

```text
npm error code EUSAGE
npm error The `npm ci` command can only install with an existing package-lock.json or
npm error npm-shrinkwrap.json with lockfileVersion >= 1.
```

## Astro / Type QA

Command:

```sh
npm run check
```

Result: `BLOCKED`, exit code `127`.

```text
> astro check
sh: 1: astro: not found
```

কারণ dependencies install করা যায়নি।

## Normal build QA

Command:

```sh
npm run build
```

Result: `BLOCKED`, exit code `127`.

```text
> astro check && astro build
sh: 1: astro: not found
```

## Environment build matrix

```sh
PUBLIC_SITE_ENV=development npm run build
PUBLIC_SITE_ENV=staging npm run build
PUBLIC_SITE_ENV=production npm run build
```

তিনটিই `BLOCKED`, exit code `127`, কারণ `astro` dependency install করা যায়নি।

Generated HTML, `robots.txt`, sitemap output build artifact থেকে inspect করা সম্ভব হয়নি। তাই generated-output indexing certification `PASS` দাবি করা হয়নি।

## Source-level environment regression

`PASS`

Source-level verification অনুযায়ী:

- কেবল exact `PUBLIC_SITE_ENV=production` indexing enable করে।
- missing/unknown/development/staging/preview fail-closed non-indexable থাকে।
- non-production robots directive source `noindex, nofollow`।
- non-production `robots.txt` source `Disallow: /`।
- non-production sitemap source empty URL set দেয়।
- canonical owner `https://rajudasrudro.com/`-ই থাকে।

এগুলো generated build verification-এর বিকল্প নয়।

## Repository / secret QA

`PASS` for source hygiene, with lockfile exception remaining `BLOCKED`.

- real `.env`: absent
- `.env.example`: present
- common secret/token/private-key markers: not found
- `node_modules/`: absent
- `dist/`: absent
- `.astro/`: absent
- `.vercel/`: absent
- files larger than 5 MB: none
- `package-lock.json`: absent (`BLOCKED` by npm registry DNS/network)
- `vercel.json`: valid JSON; `npm ci` / `npm run build` / `dist`

## Production safety

`PASS`

এই task-এ কোনো live deployment করা হয়নি। `rajudasrudro.com`, production DNS, WordPress, database, redirects বা hosting পরিবর্তন করা হয়নি।

## Remaining mandatory gates

1. npm registry access সহ environment-এ genuine `package-lock.json` generate করা।
2. `npm ci` PASS করা।
3. `npm run check` PASS করা।
4. normal `npm run build` PASS করা।
5. development/staging/production-config build matrix PASS করা।
6. generated staging HTML-এ `noindex, nofollow` verify করা।
7. generated staging `robots.txt` restrictive verify করা।
8. generated staging sitemap production URL set expose না করে তা verify করা।
9. generated production-config output globally noindexed নয় তা verify করা।
10. উপরোক্ত সব PASS হওয়ার পরেই isolated Vercel staging project connect করা।
