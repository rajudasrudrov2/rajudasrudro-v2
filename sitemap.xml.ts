import type { APIRoute } from 'astro';
import { services } from '@/data/services';
const base = 'https://rajudasrudro.com';
const routes = ['/', '/work/', '/services/', ...services.map(s => s.href), '/about/', '/reviews/', '/insights/', '/contact/', '/privacy-policy/', '/terms/'];
export const GET: APIRoute = () => {
  const body = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">${routes.map(path => `
  <url><loc>${new URL(path, base)}</loc></url>`).join('')}
</urlset>`;
  return new Response(body, { headers: { 'Content-Type': 'application/xml' } });
};
