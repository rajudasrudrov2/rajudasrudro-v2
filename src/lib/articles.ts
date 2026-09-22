import type { Article, ArticleSection } from '@/types/content';

export type ArticleOutlineItem = {
  key: string;
  id: string;
  label: string;
  level: 2 | 3;
};

function slugifyHeading(value: string): string {
  const normalized = value
    .toLowerCase()
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
  return normalized || 'section';
}

export function buildArticleOutline(sections: ArticleSection[] = []): ArticleOutlineItem[] {
  const used = new Map<string, number>();
  const outline: ArticleOutlineItem[] = [];

  const add = (key: string, label: string, level: 2 | 3) => {
    const base = slugifyHeading(label);
    const count = (used.get(base) || 0) + 1;
    used.set(base, count);
    outline.push({ key, label, level, id: count === 1 ? base : `${base}-${count}` });
  };

  sections.forEach((section, sectionIndex) => {
    if (section.title.trim()) add(`section-${sectionIndex}`, section.title, 2);
    section.blocks.forEach((block, blockIndex) => {
      if (block.type === 'subheading') add(`section-${sectionIndex}-block-${blockIndex}`, block.title, 3);
    });
  });

  return outline;
}

export function isArticleRouteEligible(article: Article, isProduction: boolean): boolean {
  if (article.publicationStatus === 'published' && article.developmentPreview !== true) return true;
  return (
    !isProduction &&
    article.publicationStatus === 'development-preview' &&
    article.developmentPreview === true &&
    article.previewDetailEnabled === true &&
    Boolean(article.body?.length)
  );
}

export function getRelatedArticles(current: Article, allArticles: Article[], limit = 3): Article[] {
  const candidates = allArticles.filter((article) => article.id !== current.id);
  const sameCategory = candidates.filter((article) => Boolean(current.category) && article.category === current.category);
  const adjacent = candidates.filter((article) => article.category !== current.category);
  return [...sameCategory, ...adjacent].slice(0, Math.max(0, limit));
}
