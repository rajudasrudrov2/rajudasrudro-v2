import type { WorkProject } from '@/types/content';
import { featuredWorkProject, workProjects } from '@/data/work';

export const allWorkProjects: WorkProject[] = [featuredWorkProject, ...workProjects];

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
