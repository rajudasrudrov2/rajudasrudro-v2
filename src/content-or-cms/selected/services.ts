import {
  services as localServices,
  type ServiceDefinition,
  type ServiceDetail,
  type ServiceFaqItem,
  type ServiceFormat,
  type ServiceIconName,
  type ServiceProcessStep,
  type ServiceValuePoint,
} from '../../data/services';
import type { CmsServiceDetail, CmsServiceListItem } from '../cms-types';
import { stripHtmlToText } from '../html';
import { getContentSource } from '../source';
import { getWordPressServiceBySlug, getWordPressServices, normalizeCmsMedia } from '../wordpress';

export type {
  AIUGCServiceDetail,
  AISpokespersonProductionPhase,
  AISpokespersonServiceDetail,
  AIVideoProductionPhase,
  AIVideoServiceDetail,
  ServiceCustomRequirement,
  ServiceDefinition,
  ServiceDetail,
  ServiceFaqItem,
  ServiceFormat,
  ServiceIconName,
  ServiceMedia,
  ServiceProcessStep,
  ServiceValuePoint,
  WebDesignServiceDetail,
} from '../../data/services';

export const CANONICAL_SERVICE_SLUGS = [
  'ai-ugc-video-ads',
  'ai-video-production',
  'ai-spokesperson-videos',
  'web-design-development',
] as const;

type CanonicalServiceSlug = (typeof CANONICAL_SERVICE_SLUGS)[number];

function asStrings(value: unknown[]): string[] {
  return value
    .map((item) => {
      if (typeof item === 'string') return item.trim();
      if (item && typeof item === 'object') {
        const record = item as Record<string, unknown>;
        const candidate = record.title ?? record.label ?? record.value ?? record.body;
        return typeof candidate === 'string' ? candidate.trim() : '';
      }
      return '';
    })
    .filter(Boolean);
}

const icons: ServiceIconName[] = ['spark', 'video', 'briefcase', 'users', 'monitor', 'globe'];
function valuePoints(items: unknown[], fallbackDescription = ''): ServiceValuePoint[] {
  return asStrings(items).map((title, index) => ({ title, description: fallbackDescription, icon: icons[index % icons.length] }));
}

function processRows(rows: CmsServiceDetail['process']): ServiceProcessStep[] {
  return rows.map((row, index) => ({ number: String(index + 1).padStart(2, '0'), title: row.title, description: row.body }));
}

function faqRows(rows: CmsServiceDetail['faq']): ServiceFaqItem[] {
  return rows.map((row) => ({ question: row.title, answer: row.body }));
}

function firstNonBlank(...values: Array<string | null | undefined>): string {
  for (const value of values) {
    const normalized = String(value ?? '').trim();
    if (normalized) return normalized;
  }
  return '';
}

function serviceMedia(cms: CmsServiceListItem, local: ServiceDefinition) {
  const media = normalizeCmsMedia(cms.heroMedia);
  return media
    ? {
        src: media.src,
        alt: media.alt,
        width: media.width || local.media.width,
        height: media.height || local.media.height,
      }
    : local.media;
}

function applyCmsDetail(local: ServiceDefinition, cms: CmsServiceDetail): ServiceDetail | undefined {
  const detail = local.detail;
  if (!detail) return undefined;

  const heroMedia = normalizeCmsMedia(cms.heroMedia);
  const visual = heroMedia
    ? { src: heroMedia.src, alt: heroMedia.alt, width: heroMedia.width || local.media.width, height: heroMedia.height || local.media.height }
    : local.media;
  const titleText = firstNonBlank(cms.heroTitle, stripHtmlToText(cms.heroHtml), cms.title);
  const description = firstNonBlank(stripHtmlToText(cms.heroContent), cms.positioning, cms.shortDescription);
  const process = processRows(cms.process);
  const faqs = faqRows(cms.faq);
  const why = valuePoints(cms.capabilities.length ? cms.capabilities : cms.useCases, stripHtmlToText(cms.whyRajuHtml));
  const formats: ServiceFormat[] = asStrings(cms.formats).map((title) => ({ title, description: '', media: visual }));

  if (detail.kind === 'ai-ugc') {
    return {
      ...detail,
      eyebrow: cms.title,
      heroTitle: titleText,
      heroDescription: description,
      seoTitle: cms.seo.title || cms.title,
      metaDescription: cms.seo.description || cms.shortDescription,
      heroMedia: [visual],
      buyerNeeds: valuePoints(cms.useCases),
      deliverables: asStrings(cms.deliverables),
      formats,
      process,
      whyRaju: why,
      faqs,
    };
  }
  if (detail.kind === 'ai-video') {
    return {
      ...detail,
      eyebrow: cms.title,
      heroTitle: titleText,
      heroDescription: description,
      seoTitle: cms.seo.title || cms.title,
      metaDescription: cms.seo.description || cms.shortDescription,
      heroMedia: [visual],
      explanation: { ...detail.explanation, title: cms.title, description, capabilities: valuePoints(cms.capabilities) },
      creationTypes: formats,
      buyerNeeds: valuePoints(cms.useCases),
      process,
      whyRaju: why,
      faqs,
    };
  }
  if (detail.kind === 'ai-spokesperson') {
    return {
      ...detail,
      eyebrow: cms.title,
      heroTitle: titleText,
      heroDescription: description,
      seoTitle: cms.seo.title || cms.title,
      metaDescription: cms.seo.description || cms.shortDescription,
      heroMedia: [visual],
      explanation: { ...detail.explanation, title: cms.title, description },
      useCases: valuePoints(cms.useCases),
      presenterOptions: formats,
      process,
      whyRaju: why,
      faqs,
    };
  }
  return {
    ...detail,
    eyebrow: cms.title,
    heroTitle: titleText,
    heroDescription: description,
    seoTitle: cms.seo.title || cms.title,
    metaDescription: cms.seo.description || cms.shortDescription,
    heroMedia: [visual],
    projectTypes: valuePoints(cms.useCases),
    foundations: valuePoints(cms.capabilities),
    process,
    clientReceives: asStrings(cms.deliverables),
    whyRaju: why,
    faqs,
  };
}

export function assertCanonicalServices(items: CmsServiceListItem[]): void {
  const slugs = items.map((item) => item.slug);
  const duplicates = slugs.filter((slug, index) => slugs.indexOf(slug) !== index);
  if (duplicates.length) throw new Error(`Duplicate canonical Service slug(s): ${[...new Set(duplicates)].join(', ')}`);

  const missing = CANONICAL_SERVICE_SLUGS.filter((slug) => !slugs.includes(slug));
  if (missing.length) throw new Error(`Missing canonical Service(s): ${missing.join(', ')}`);

  const unexpected = slugs.filter((slug) => !(CANONICAL_SERVICE_SLUGS as readonly string[]).includes(slug));
  if (unexpected.length) throw new Error(`Unexpected primary Service(s): ${unexpected.join(', ')}`);

  if (items.length !== CANONICAL_SERVICE_SLUGS.length) {
    throw new Error(`Expected exactly ${CANONICAL_SERVICE_SLUGS.length} primary Services; received ${items.length}.`);
  }
}

async function loadWordPressServices(): Promise<ServiceDefinition[]> {
  const collection = await getWordPressServices();
  assertCanonicalServices(collection);
  const details = await Promise.all(
    CANONICAL_SERVICE_SLUGS.map(async (slug) => {
      const detail = await getWordPressServiceBySlug(slug);
      if (!detail) throw new Error(`Canonical Service detail missing: ${slug}`);
      return detail;
    }),
  );

  return details.map((cms) => {
    const local = localServices.find((item) => item.slug === cms.slug);
    if (!local) throw new Error(`No approved dedicated Service presentation exists for ${cms.slug}.`);
    return {
      ...local,
      slug: cms.slug,
      title: cms.title,
      short: cms.shortDescription,
      href: `/services/${cms.slug}/`,
      bullets: asStrings(cms.deliverables).slice(0, 4),
      flagship: cms.featured,
      media: serviceMedia(cms, local),
      detail: applyCmsDetail(local, cms),
    } satisfies ServiceDefinition;
  });
}

export const servicesUseCms = getContentSource() === 'wordpress';

export const services: ServiceDefinition[] = servicesUseCms
  ? await loadWordPressServices()
  : localServices;

export function isCanonicalServiceSlug(slug: string): slug is CanonicalServiceSlug {
  return (CANONICAL_SERVICE_SLUGS as readonly string[]).includes(slug);
}
