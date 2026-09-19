import type { APIRoute } from 'astro';
import { site } from '@/data/site';
import { isProductionEnvironment } from '@/config/environment';

export const GET: APIRoute = () => {
  const body = isProductionEnvironment
    ? `User-agent: *\nAllow: /\nSitemap: ${site.url}/sitemap.xml\n`
    : 'User-agent: *\nDisallow: /\n';

  return new Response(body, { headers: { 'Content-Type': 'text/plain; charset=utf-8' } });
};
