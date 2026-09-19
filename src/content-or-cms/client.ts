import type { Article, Project, Review } from '@/types/content';
import { isProductionEnvironment } from '@/config/environment';

/**
 * Controlled staging/development fixtures for the Insights listing milestone.
 * These are NOT migrated production articles. They are deliberately returned
 * only outside production so the CMS/listing UI can be exercised safely.
 * Future Headless WordPress integration replaces this temporary array inside
 * the same adapter boundary; public templates do not need a new content system.
 */
const developmentPreviewArticles: Article[] = [
  {
    id: 'insights-preview-web-framework',
    title: 'Planning a High-Converting Website: A Practical Framework',
    slug: 'planning-a-high-converting-website-preview',
    excerpt: 'A development-preview editorial record for testing how practical website planning, structure and conversion-focused content appear in the Insights system.',
    category: 'Web Design & Development',
    featureImage: {
      src: '/images/insights/featured-web-framework-preview.svg',
      alt: 'Development preview artwork showing a responsive website planning layout',
      width: 1200,
      height: 675,
    },
    featured: true,
    publicationStatus: 'development-preview',
    developmentPreview: true,
  },
  {
    id: 'insights-preview-ai-ugc-testing',
    title: 'How AI UGC Can Support Faster Creative Testing',
    slug: 'ai-ugc-creative-testing-preview',
    excerpt: 'A layout-preview article concept about creator-style AI UGC, hook variations and structured creative testing without asserting campaign performance.',
    category: 'AI UGC',
    featureImage: {
      src: '/images/insights/ai-ugc-testing-preview.svg',
      alt: 'Development preview artwork for an AI UGC creative-testing article',
      width: 800,
      height: 450,
    },
    publicationStatus: 'development-preview',
    developmentPreview: true,
  },
  {
    id: 'insights-preview-ai-video-production',
    title: 'AI Video Production: From Concept to Multi-Scene Creative',
    slug: 'ai-video-production-workflow-preview',
    excerpt: 'A development-preview article concept showing how broader AI-assisted video production can move from concept and scene planning to finished creative.',
    category: 'AI Video',
    featureImage: {
      src: '/images/insights/ai-video-production-preview.svg',
      alt: 'Development preview artwork representing an AI video production workflow',
      width: 800,
      height: 450,
    },
    publicationStatus: 'development-preview',
    developmentPreview: true,
  },
  {
    id: 'insights-preview-web-performance',
    title: 'Website Speed: Practical Performance Foundations',
    slug: 'website-performance-foundations-preview',
    excerpt: 'A development-preview guide concept about image sizing, lean interfaces and performance-conscious implementation for modern business websites.',
    category: 'Web Design & Development',
    featureImage: {
      src: '/images/insights/web-performance-preview.svg',
      alt: 'Development preview artwork with a website performance gauge',
      width: 800,
      height: 450,
    },
    publicationStatus: 'development-preview',
    developmentPreview: true,
  },
  {
    id: 'insights-preview-ai-creative-workflow',
    title: 'A Practical AI Creative Workflow from Brief to Final Output',
    slug: 'ai-creative-workflow-preview',
    excerpt: 'A controlled preview record for an editorial workflow covering brief clarity, concept development, iteration and production-minded delivery.',
    category: 'AI Creative',
    featureImage: {
      src: '/images/insights/ai-creative-workflow-preview.svg',
      alt: 'Development preview artwork representing an AI creative workflow',
      width: 800,
      height: 450,
    },
    publicationStatus: 'development-preview',
    developmentPreview: true,
  },
  {
    id: 'insights-preview-essential-pages',
    title: 'Essential Pages for a Clear Business Website',
    slug: 'essential-business-website-pages-preview',
    excerpt: 'A development-preview article concept for testing practical web-structure guidance without implying that legacy content has already been migrated.',
    category: 'Web Design & Development',
    featureImage: {
      src: '/images/insights/essential-pages-preview.svg',
      alt: 'Development preview artwork showing a simple business website page structure',
      width: 800,
      height: 450,
    },
    publicationStatus: 'development-preview',
    developmentPreview: true,
  },
  {
    id: 'insights-preview-script-writing',
    title: 'Writing Clearer Scripts for AI Video Production',
    slug: 'ai-video-script-writing-preview',
    excerpt: 'A controlled preview record demonstrating a practical script-focused topic for AI video without making delivery, results or platform claims.',
    category: 'AI Video',
    featureImage: {
      src: '/images/insights/ai-video-script-preview.svg',
      alt: 'Development preview artwork representing script planning for AI video',
      width: 800,
      height: 450,
    },
    publicationStatus: 'development-preview',
    developmentPreview: true,
  },
];

/**
 * CMS boundary.
 * Public page templates never depend directly on WordPress rendering or plugin APIs.
 * Replace these empty/preview adapters with validated Headless WordPress API queries in the CMS phase.
 */
export async function getProjects(): Promise<Project[]> { return []; }
export async function getProjectBySlug(_slug: string): Promise<Project | null> { return null; }
export async function getReviews(): Promise<Review[]> { return []; }
export async function getArticles(): Promise<Article[]> {
  return isProductionEnvironment ? [] : developmentPreviewArticles;
}
export async function getArticleBySlug(slug: string): Promise<Article | null> {
  if (isProductionEnvironment) return null;
  return developmentPreviewArticles.find((article) => article.slug === slug) ?? null;
}
