import { publicReviewSummaries as localReviewSummaries } from '../../data/reviews';
import type { Review, WorkProject } from '@/types/content';
import { getContentSource } from '../source';
import { getWordPressReviews } from '../wordpress';

export type PublicReviewSummary = Review & { name: string; order?: number; relatedServiceId?: string; relatedProjectId?: string };

async function loadWordPressReviews(): Promise<PublicReviewSummary[]> {
  const reviews = await getWordPressReviews();
  return reviews.map((review) => ({
    id: String(review.id),
    name: review.reviewer,
    publicBuyerIdentity: review.reviewer,
    text: review.text,
    ...(review.rating === null ? {} : { rating: review.rating }),
    source: review.source || 'Public review',
    sourceUrl: review.sourceUrl || undefined,
    context: review.context || undefined,
    country: review.country || undefined,
    reviewDate: review.reviewDate || undefined,
    featured: review.featured,
    order: review.order,
    relatedServiceId: review.relatedService ? String(review.relatedService.id) : undefined,
    relatedProjectId: review.relatedProject ? String(review.relatedProject.id) : undefined,
  }));
}

const localNormalized: PublicReviewSummary[] = localReviewSummaries.map((review, index) => ({
  id: `local-review-${index + 1}`,
  name: review.name,
  publicBuyerIdentity: review.name,
  rating: review.rating,
  text: review.text,
  context: review.context,
  source: 'Fiverr',
}));

export const reviewsUseCms = getContentSource() === 'wordpress';

export const publicReviewSummaries: PublicReviewSummary[] = reviewsUseCms
  ? await loadWordPressReviews()
  : localNormalized;

export function resolveProjectReview(project: WorkProject): Review | undefined {
  if (project.caseStudyStatus !== 'available' || !project.caseStudy) return undefined;
  const reviewId = project.caseStudy.projectReviewId;
  if (!reviewId) return undefined;
  return publicReviewSummaries.find((review) => review.id === reviewId);
}
