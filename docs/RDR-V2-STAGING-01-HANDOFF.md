# RDR-V2-STAGING-01 — GitHub + Vercel Staging Handoff

## Status

- Package readiness: `PASS`
- Online Vercel certification: `PENDING`

এই source package browser-based GitHub → Vercel staging workflow-এর জন্য প্রস্তুত। User-এর local PC-তে Node.js/npm install বা build চালানোর প্রয়োজন নেই।

## GitHub — browser-based upload

1. GitHub-এ sign in করুন।
2. **New repository** নির্বাচন করুন।
3. একটি repository name দিন, যেমন `raju-das-rudro-v2-staging`।
4. Visibility হিসেবে **Private** recommended, কারণ এটি staging/source repository। Public repository-ও technically কাজ করবে, তবে source private রাখতে চাইলে Private ব্যবহার করুন।
5. Repository creation-এর সময় README, `.gitignore`, License auto-initialize করবেন না; existing project files upload করা হবে।
6. এই clean ZIP File Explorer দিয়ে Extract করুন।
7. Extracted `raju-das-rudro-v2` folder খুলুন। GitHub repository-এর **Add file → Upload files** ব্যবহার করুন।
8. Project root-এর contents upload করুন—`package.json`, `package-lock.json`, `.env.example`, `src/`, `public/`, `docs/`, config files ইত্যাদি।
9. GitHub web UI থেকে commit করুন।
10. Repository root-এ `package.json` এবং `package-lock.json` visible কিনা নিশ্চিত করুন।
11. কোনো real `.env` upload করবেন না। `.env.example` visible থাকবে।
12. Repository URL রেখে দিন; Vercel import-এ এটি ব্যবহার হবে।

## Vercel — separate staging project

1. Vercel-এ sign in করুন।
2. **Add New → Project** নির্বাচন করুন।
3. GitHub repository connect/import করুন।
4. Framework Preset `Astro` confirm করুন।
5. Root Directory project root-ই থাকবে—যেখানে `package.json` আছে।
6. Install Command: `npm ci`
7. Build Command: `npm run build`
8. Output Directory: `dist`
9. Environment Variables-এ যোগ করুন:
   - Name: `PUBLIC_SITE_ENV`
   - Value: `staging`
10. এটি staging-only Vercel project। Vercel UI-তে main deployment environment-কে “Production” বলা হলেও real production domain connect করার অনুমতি নয়। ওই staging project-এর relevant environment scopes-এ `PUBLIC_SITE_ENV=staging` রাখুন।
11. `rajudasrudro.com` custom domain হিসেবে add করবেন না। Production DNS পরিবর্তন করবেন না।
12. Deploy করুন এবং Vercel-provided `.vercel.app` staging URL ব্যবহার করুন।
13. Deployment page-এর **Logs / Build Logs** থেকে install/build result দেখুন।
14. Successful deployment হলে status সাধারণত `Ready` দেখাবে।
15. Staging URL copy করে Planning/Coding review-এ দিন।

## Online certification evidence

পরবর্তী review-এর জন্য শুধু আনুন:

- staging URL
- Vercel deployment `Ready` কিনা
- error হলে build-log screenshot বা copied build log

কোনো password, token, GitHub credential বা Vercel secret share করবেন না।

## Online certification checklist

- [ ] Vercel `npm ci` PASS
- [ ] Vercel `npm run build` PASS
- [ ] Deployment Ready
- [ ] Staging URL opens
- [ ] `noindex, nofollow` confirmed
- [ ] `robots.txt` blocks crawling
- [ ] Staging sitemap safe
- [ ] `rajudasrudro.com` not attached
