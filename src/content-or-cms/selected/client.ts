import * as localClient from '../client';
import type { Article, Project, Review } from '@/types/content';
import { cmsHtmlToArticleSections, sanitizeCmsHtml, stripHtmlToText } from '../html';
import { getContentSource } from '../source';
import {
  getWordPressArticleBySlug,
  getWordPressArticles,
  normalizeCmsMedia,
} from '../wordpress';
import { allWorkProjects } from './work';
import { publicReviewSummaries } from './reviews';

function normalizeArticle(detail: Awaited<ReturnType<typeof getWordPressArticleBySlug>>): Article | null {
  if (!detail) return null;
  return {
    id: String(detail.id),
    title: detail.title,
    slug: detail.slug,
    excerpt: detail.excerpt,
    bodyHtml: sanitizeCmsHtml(detail.bodyHtml),
    body: cmsHtmlToArticleSections(detail.bodyHtml),
    category: detail.categories[0]?.name ? stripHtmlToText(detail.categories[0].name) : undefined,
    publishedDate: detail.publishedAt,
    updatedDate: detail.updatedAt,
    author: detail.author?.name,
    readingTime: detail.readingTime ? `${detail.readingTime} min read` : undefined,
    seoTitle: detail.seo.title || undefined,
    metaDescription: detail.seo.description || undefined,
    featureImage: normalizeCmsMedia(detail.featureImage),
    ogImage: normalizeCmsMedia(detail.seo.ogImage),
    featured: detail.featured,
    publicationStatus: 'published',
    developmentPreview: false,
    relatedServiceSlugs: detail.relatedServices.map((service) => service.slug),
    relatedWorkIds: detail.relatedProjects.map((project) => String(project.id)),
  };
}

async function getCmsArticles(): Promise<Article[]> {
  const collection = await getWordPressArticles();
  const details = await Promise.all(collection.map((item) => getWordPressArticleBySlug(item.slug)));
  return details.map(normalizeArticle).filter((article): article is Article => Boolean(article));
}

export async function getProjects(): Promise<Project[]> {
  return getContentSource() === 'wordpress' ? allWorkProjects : localClient.getProjects();
}

export async function getProjectBySlug(slug: string): Promise<Project | null> {
  if (getContentSource() !== 'wordpress') return localClient.getProjectBySlug(slug);
  return allWorkProjects.find((project) => project.slug === slug) ?? null;
}

export async function getReviews(): Promise<Review[]> {
  return getContentSource() === 'wordpress' ? publicReviewSummaries : localClient.getReviews();
}

export async function getArticles(): Promise<Article[]> {
  return getContentSource() === 'wordpress' ? getCmsArticles() : localClient.getArticles();
}

export async function getArticleBySlug(slug: string): Promise<Article | null> {
  if (getContentSource() !== 'wordpress') return localClient.getArticleBySlug(slug);
  return normalizeArticle(await getWordPressArticleBySlug(slug));
}
