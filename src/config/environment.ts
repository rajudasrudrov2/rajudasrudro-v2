export type SiteEnvironment = 'production' | 'staging' | 'preview' | 'development';

const rawSiteEnvironment = (import.meta.env.PUBLIC_SITE_ENV || 'development').trim().toLowerCase();

const recognizedEnvironments: readonly SiteEnvironment[] = [
  'production',
  'staging',
  'preview',
  'development',
];

export const siteEnvironment: SiteEnvironment = recognizedEnvironments.includes(rawSiteEnvironment as SiteEnvironment)
  ? (rawSiteEnvironment as SiteEnvironment)
  : 'development';

/**
 * Indexing is an explicit production-only capability.
 * Unknown or missing values fail closed to non-production behavior.
 */
export const isProductionEnvironment = rawSiteEnvironment === 'production';
export const isIndexableEnvironment = isProductionEnvironment;

export const environmentRobotsDirective = isIndexableEnvironment
  ? 'index, follow'
  : 'noindex, nofollow';

export const shouldExposeProductionSitemap = isProductionEnvironment;
