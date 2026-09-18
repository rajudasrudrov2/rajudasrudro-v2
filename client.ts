import type { Article, Project, Review } from '@/types/content';

/**
 * CMS boundary.
 * Public page templates never depend directly on WordPress rendering or plugin APIs.
 * Replace these empty adapters with validated Headless WordPress API queries in the CMS phase.
 */
export async function getProjects(): Promise<Project[]> { return []; }
export async function getProjectBySlug(_slug: string): Promise<Project | null> { return null; }
export async function getReviews(): Promise<Review[]> { return []; }
export async function getArticles(): Promise<Article[]> { return []; }
export async function getArticleBySlug(_slug: string): Promise<Article | null> { return null; }
