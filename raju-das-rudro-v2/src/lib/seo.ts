import { site } from '@/data/site';

export type SeoInput = {
  title: string;
  description: string;
  pathname: string;
  image?: string;
  noindex?: boolean;
  type?: 'website' | 'article';
};

export function seo(input: SeoInput) {
  const canonical = new URL(input.pathname, site.url).toString();
  return {
    ...input,
    canonical,
    image: input.image ? new URL(input.image, site.url).toString() : undefined,
  };
}
