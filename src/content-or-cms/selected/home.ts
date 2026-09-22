import {
  homeServiceGroups as localHomeServiceGroups,
  homeTrustMetrics,
  homeWorkPreviews as localHomeWorkPreviews,
  productionSteps,
  reviewMigrationPlaceholder as localReviewMigrationPlaceholder,
} from '../../data/home';
import { getContentSource } from '../source';
import { services } from './services';
import { allWorkProjects } from './work';

export { homeTrustMetrics, productionSteps };

const source = getContentSource();
export const homeUsesCms = source === 'wordpress';

export const homeWorkPreviews = source === 'wordpress'
  ? allWorkProjects.slice(0, 4).map((project) => ({
      title: project.title,
      context: project.shortSummary,
      href: project.detailHref || '/work/',
      media: project.poster?.src || '',
      alt: project.poster?.alt || '',
      width: project.poster?.width,
      height: project.poster?.height,
      developmentPreview: false,
    }))
  : localHomeWorkPreviews;

function service(slug: string) {
  return services.find((item) => item.slug === slug);
}

export const homeServiceGroups = source === 'wordpress'
  ? [
      {
        ...localHomeServiceGroups[0],
        title: service('ai-ugc-video-ads')?.title || localHomeServiceGroups[0].title,
        description: service('ai-ugc-video-ads')?.short || '',
        links: ['ai-ugc-video-ads', 'ai-spokesperson-videos'].map((slug) => service(slug)).filter(Boolean).map((item) => ({ label: item!.title, href: item!.href })),
      },
      {
        ...localHomeServiceGroups[1],
        title: service('ai-video-production')?.title || localHomeServiceGroups[1].title,
        description: service('ai-video-production')?.short || '',
        links: ['ai-video-production'].map((slug) => service(slug)).filter(Boolean).map((item) => ({ label: item!.title, href: item!.href })),
      },
      {
        ...localHomeServiceGroups[2],
        title: service('web-design-development')?.title || localHomeServiceGroups[2].title,
        description: service('web-design-development')?.short || '',
        links: ['web-design-development'].map((slug) => service(slug)).filter(Boolean).map((item) => ({ label: item!.title, href: item!.href })),
      },
    ]
  : localHomeServiceGroups;

export const reviewMigrationPlaceholder = source === 'wordpress'
  ? { title: '', text: '', hidden: true }
  : localReviewMigrationPlaceholder;

export const homeFeaturedCaseStudyProject = source === 'wordpress'
  ? (allWorkProjects.find((project) => project.featured && project.caseStudyStatus === 'available' && Boolean(project.caseStudy))
    ?? allWorkProjects.find((project) => project.caseStudyStatus === 'available' && Boolean(project.caseStudy)))
  : undefined;
