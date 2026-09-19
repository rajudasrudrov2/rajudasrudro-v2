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
    ogImage: {
      src: '/images/insights/featured-web-framework-preview.svg',
      alt: 'Development preview artwork showing a responsive website planning layout',
      width: 1200,
      height: 675,
    },
    featured: true,
    publicationStatus: 'development-preview',
    developmentPreview: true,
    previewDetailEnabled: true,
    relatedServiceSlugs: ['web-design-development'],
    relatedWorkIds: ['work-ecommerce-web-preview'],
    seoTitle: 'Planning a High-Converting Website — Development Preview | Raju Das Rudro',
    metaDescription: 'Controlled development-preview article used to validate the reusable Single Article experience for Raju Das Rudro V2. Not migrated production content.',
    body: [
      {
        title: 'Start With the Business Goal',
        blocks: [
          {
            type: 'paragraph',
            text: 'A useful business website starts by deciding what a visitor should understand, trust and do. Visual polish matters, but the page structure becomes much clearer when the business goal and visitor goal are defined first.',
          },
          {
            type: 'paragraph',
            text: 'For a service-led site, that may mean explaining the offer clearly, showing relevant proof and creating a straightforward path to contact. For an ecommerce or product site, the priority may be helping visitors evaluate the product and reach the next purchase step without unnecessary friction.',
          },
          {
            type: 'callout',
            title: 'Practical principle',
            text: 'A page should make the next useful decision easier. Design, copy and interaction should support that decision rather than compete for attention.',
          },
        ],
      },
      {
        title: 'Build the Information Structure Before Styling',
        blocks: [
          {
            type: 'paragraph',
            text: 'Before refining colors, imagery or motion, define the information hierarchy. The page needs a clear opening message, enough supporting detail to answer common questions and a visible path to the next action.',
          },
          {
            type: 'steps',
            items: [
              { title: 'Clarify the offer', description: 'State what the business provides and who the page is for.' },
              { title: 'Order the evidence', description: 'Place useful details, proof and examples where they reduce uncertainty.' },
              { title: 'Shape the journey', description: 'Move visitors from understanding to evaluation and then to action.' },
              { title: 'Refine the interface', description: 'Apply the visual system after the content hierarchy is stable.' },
            ],
          },
        ],
      },
      {
        title: 'Make the Offer Easy to Understand',
        blocks: [
          {
            type: 'paragraph',
            text: 'A conversion-focused page does not need to feel aggressive. It needs to reduce ambiguity. Clear headings, specific descriptions and obvious next steps usually do more useful work than adding more decorative sections.',
          },
          {
            type: 'comparison',
            items: [
              {
                title: 'Harder to scan',
                points: ['Generic headlines', 'Several competing actions', 'Dense copy without hierarchy', 'Important proof buried low on the page'],
              },
              {
                title: 'Easier to understand',
                points: ['Specific value proposition', 'Primary action is visually clear', 'Sections answer one question at a time', 'Relevant proof sits near decisions'],
              },
            ],
          },
        ],
      },
      {
        title: 'Design for Responsive Clarity',
        blocks: [
          {
            type: 'paragraph',
            text: 'Responsive design is not only about making desktop sections stack on a smaller screen. The priority, order and reading rhythm should still make sense when the viewport changes.',
          },
          { type: 'subheading', title: 'Keep the mobile path simple' },
          {
            type: 'unordered-list',
            items: [
              'Keep the opening message short enough to scan without losing context.',
              'Make primary actions easy to reach and large enough to use comfortably.',
              'Use predictable card and media ratios so the layout does not jump while loading.',
              'Avoid shrinking substantive copy just to preserve a desktop-style density.',
            ],
          },
        ],
      },
      {
        title: 'Protect Performance and SEO Foundations',
        blocks: [
          {
            type: 'paragraph',
            text: 'A polished interface still needs a clean technical foundation. Performance-conscious media, semantic structure and sensible metadata help the site remain usable and easier for search systems to understand.',
          },
          {
            type: 'unordered-list',
            items: [
              'Reserve media dimensions to reduce layout movement.',
              'Load below-fold imagery only when it is needed.',
              'Use one clear H1 and a logical H2/H3 hierarchy.',
              'Give each important page a unique title, description and canonical path.',
              'Keep interactive behavior lightweight when static HTML and native controls are enough.',
            ],
          },
        ],
      },
      {
        title: 'Review the Path to Action Before Launch',
        blocks: [
          {
            type: 'paragraph',
            text: 'Before launch, review the site from the visitor’s point of view rather than only checking whether every section exists. The strongest final QA asks whether the page communicates clearly, works across devices and makes the next step easy to understand.',
          },
          {
            type: 'ordered-list',
            items: [
              'Read the page from top to bottom at normal browser zoom.',
              'Check the main journey on mobile, tablet and desktop widths.',
              'Verify every visible action has a real destination or function.',
              'Review headings, metadata, media loading and accessibility states.',
              'Remove sections that add density without helping the visitor make a decision.',
            ],
          },
          {
            type: 'callout',
            title: 'Development-preview note',
            text: 'This article record exists to validate the reusable Single Article architecture. It is not a migrated historical post and does not claim rankings, traffic, client results or prior publication history.',
          },
        ],
      },
    ],
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
