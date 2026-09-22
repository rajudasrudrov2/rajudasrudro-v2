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
  const image = input.image || site.defaultSocialImage?.src;
  return {
    ...input,
    canonical,
    image: image ? new URL(image, site.url).toString() : undefined,
  };
}
