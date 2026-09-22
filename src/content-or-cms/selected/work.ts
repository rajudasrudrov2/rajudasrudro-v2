import {
  featuredWorkProject as localFeaturedWorkProject,
  workFilterCategories as localWorkFilterCategories,
  workProjects as localWorkProjects,
  workTrustMetrics as localWorkTrustMetrics,
} from '../../data/work';
import type {
  CaseStudyContent,
  CaseStudyDeliverable,
  CaseStudyMediaItem,
  CaseStudyOutcome,
  WorkProject,
  WorkProjectCategory,
} from '@/types/content';
import type { CmsProjectDetail, CmsProjectListItem } from '../cms-types';
import { stripHtmlToText } from '../html';
import { getContentSource } from '../source';
import { getWordPressProjectBySlug, getWordPressProjects, normalizeCmsMedia } from '../wordpress';

export const workFilterCategories = localWorkFilterCategories;
export const workTrustMetrics = localWorkTrustMetrics;
export const workUsesCms = getContentSource() === 'wordpress';

const allowedCategories = new Set<WorkProjectCategory>(['AI UGC', 'AI Video', 'AI Spokesperson', 'Web Design', 'Web Development']);

function normalizeCategories(project: CmsProjectListItem): WorkProjectCategory[] {
  return project.categories
    .map((category) => category.name)
    .filter((name): name is WorkProjectCategory => allowedCategories.has(name as WorkProjectCategory));
}

function stringList(items: unknown[]): string[] {
  return items.map((item) => {
    if (typeof item === 'string') return item.trim();
    if (item && typeof item === 'object') {
      const record = item as Record<string, unknown>;
      const value = record.label ?? record.title ?? record.value ?? record.body;
      return typeof value === 'string' ? value.trim() : '';
    }
    return '';
  }).filter(Boolean);
}

function detailCaseStudy(detail: CmsProjectDetail): CaseStudyContent | undefined {
  if (!detail.caseStudy) return undefined;
  const cms = detail.caseStudy;
  const gallery = detail.gallery.map(normalizeCmsMedia).filter(Boolean);
  const variations: CaseStudyMediaItem[] = gallery.map((media, index) => ({
    id: `${detail.id}-gallery-${index + 1}`,
    title: `Project media ${index + 1}`,
    poster: media!,
  }));
  const deliverables: CaseStudyDeliverable[] = stringList(cms.deliverables).map((label) => ({ label }));
  const outcomesText = stripHtmlToText(cms.outcomesHtml);
  const outcomes: CaseStudyOutcome[] = outcomesText ? [{ label: outcomesText, verification: 'verified' }] : [];
  const challenge = stripHtmlToText(cms.challengeHtml);
  const approach = stripHtmlToText(cms.approachHtml);
  return {
    heroMedia: normalizeCmsMedia(detail.featureMedia) || gallery[0],
    overview: stripHtmlToText(cms.overviewHtml) || stripHtmlToText(detail.overviewHtml),
    challenge: challenge ? { intro: challenge } : undefined,
    approach: approach ? { intro: approach } : undefined,
    deliverables,
    creativeVariations: variations,
    outcomes,
    projectReviewId: cms.relatedReview ? String(cms.relatedReview.id) : undefined,
    relatedProjectIds: cms.relatedProjects.map((project) => String(project.id)),
  };
}

async function normalizeWordPressProject(item: CmsProjectListItem): Promise<WorkProject> {
  const detail = item.caseStudy.available ? await getWordPressProjectBySlug(item.slug) : null;
  if (item.caseStudy.available && !detail) throw new Error(`Public Project detail missing for ${item.slug}.`);
  const categories = normalizeCategories(item);
  const poster = normalizeCmsMedia(item.featureMedia);
  const caseStudy = detail ? detailCaseStudy(detail) : undefined;
  return {
    id: String(item.id),
    title: item.title,
    slug: item.slug,
    shortSummary: item.shortSummary,
    categories,
    serviceSlug: item.relatedService?.slug,
    featured: item.featured,
    poster,
    mediaStatus: poster ? 'poster' : 'pending',
    caseStudyStatus: item.caseStudy.available ? 'available' : 'pending',
    projectStatus: 'verified',
    publicationStatus: 'published',
    sortOrder: item.order,
    detailHref: item.caseStudy.available ? `/work/${item.slug}/` : undefined,
    tags: categories.map((label) => ({ label, tone: label.startsWith('Web') ? 'blue' : 'brand' })),
    overview: detail ? stripHtmlToText(detail.overviewHtml) : undefined,
    seoTitle: detail?.seo.title || undefined,
    metaDescription: detail?.seo.description || undefined,
    ogImage: detail ? normalizeCmsMedia(detail.seo.ogImage) : undefined,
    caseStudy,
  };
}

async function loadWordPressWork(): Promise<WorkProject[]> {
  const collection = await getWordPressProjects();
  const normalized = await Promise.all(collection.map(normalizeWordPressProject));
  return normalized.sort((a, b) => a.sortOrder - b.sortOrder || a.title.localeCompare(b.title));
}

export const allWorkProjects: WorkProject[] = workUsesCms
  ? await loadWordPressWork()
  : [localFeaturedWorkProject, ...localWorkProjects];

const featured = allWorkProjects.find((project) => project.featured) ?? allWorkProjects[0];
const emptyWorkState: WorkProject = {
  id: '__selected-work-empty__',
  title: 'No public projects are currently available',
  slug: '',
  shortSummary: '',
  categories: [],
  projectStatus: 'verified',
  publicationStatus: 'published',
  mediaStatus: 'pending',
  caseStudyStatus: 'pending',
  sortOrder: 0,
  emptyState: true,
};

// This sentinel is presentation-only: it is never inserted into allWorkProjects,
// never generates a route, and prevents a valid CMS 200 + [] from becoming local/fake Work data.
export const featuredWorkProject: WorkProject = featured ?? emptyWorkState;
export const workProjects = featured ? allWorkProjects.filter((project) => project.id !== featured.id) : [];

export function isCaseStudyRouteEligible(project: WorkProject, isProduction: boolean): boolean {
  if (project.caseStudyStatus !== 'available' || !project.caseStudy) return false;
  if (project.publicationStatus === 'published') return true;
  return !isProduction && project.publicationStatus === 'development-preview';
}

export function getCaseStudyProjects(isProduction: boolean): WorkProject[] {
  return allWorkProjects.filter((project) => isCaseStudyRouteEligible(project, isProduction));
}

export function getWorkProjectBySlug(slug: string): WorkProject | undefined {
  return allWorkProjects.find((project) => project.slug === slug);
}

export function getRelatedWorkProjects(project: WorkProject): WorkProject[] {
  const ids = project.caseStudy?.relatedProjectIds || [];
  const seen = new Set<string>();
  return ids
    .map((id) => allWorkProjects.find((candidate) => candidate.id === id))
    .filter((candidate): candidate is WorkProject => Boolean(candidate))
    .filter((candidate) => candidate.id !== project.id)
    .filter((candidate) => {
      if (seen.has(candidate.id)) return false;
      seen.add(candidate.id);
      return true;
    });
}
