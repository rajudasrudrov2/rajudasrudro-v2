import type { APIRoute } from 'astro';
import { services } from '@/data/services';
import { site } from '@/data/site';
import { shouldExposeProductionSitemap } from '@/config/environment';
import { getArticles } from '@/content-or-cms/client';
import { isArticleRouteEligible } from '@/lib/articles';
import { getCaseStudyProjects } from '@/lib/work-projects';

const caseStudyRoutes = getCaseStudyProjects(true).map((project) => `/work/${project.slug}/`);
const baseRoutes = ['/', '/work/', ...caseStudyRoutes, '/services/', ...services.map((service) => service.href), '/about/', '/reviews/', '/insights/', '/contact/', '/privacy-policy/', '/terms/'];
const isSitemapSafeArticleSlug = (slug: string) => /^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(slug);

export const GET: APIRoute = async () => {
  const articleRoutes = shouldExposeProductionSitemap
    ? (await getArticles())
      .filter((article) => isArticleRouteEligible(article, true) && isSitemapSafeArticleSlug(article.slug))
      .map((article) => `/insights/${article.slug}/`)
    : [];
  const routes = [...new Set([...baseRoutes, ...articleRoutes])];
  const entries = shouldExposeProductionSitemap
    ? routes.map((path) => `\n  <url><loc>${new URL(path, site.url)}</loc></url>`).join('')
    : '';

  const body = `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">${entries}\n</urlset>`;

  return new Response(body, { headers: { 'Content-Type': 'application/xml; charset=utf-8' } });
};
