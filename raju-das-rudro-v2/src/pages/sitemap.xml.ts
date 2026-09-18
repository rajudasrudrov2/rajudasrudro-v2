import type { APIRoute } from 'astro';
import { services } from '@/data/services';
import { site } from '@/data/site';
import { shouldExposeProductionSitemap } from '@/config/environment';

const routes = ['/', '/work/', '/services/', ...services.map((service) => service.href), '/about/', '/reviews/', '/insights/', '/contact/', '/privacy-policy/', '/terms/'];

export const GET: APIRoute = () => {
  const entries = shouldExposeProductionSitemap
    ? routes.map((path) => `\n  <url><loc>${new URL(path, site.url)}</loc></url>`).join('')
    : '';

  const body = `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">${entries}\n</urlset>`;

  return new Response(body, { headers: { 'Content-Type': 'application/xml; charset=utf-8' } });
};
