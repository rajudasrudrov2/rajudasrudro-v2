export type MediaAsset = {
  src: string;
  alt: string;
  width?: number;
  height?: number;
  mimeType?: string;
};

export type VideoAsset = {
  provider?: string;
  assetId?: string;
  url?: string;
  poster?: MediaAsset;
  duration?: string;
  captionsUrl?: string;
  aspectRatio?: '9:16' | '16:9' | string;
};

export type Project = {
  id: string;
  title: string;
  slug: string;
  shortSummary: string;
  projectType?: string;
  serviceSlug?: string;
  category?: string;
  industry?: string;
  year?: number;
  featured?: boolean;
  poster?: MediaAsset;
  video?: VideoAsset;
  overview?: string;
  challenge?: string;
  approach?: string[];
  deliverables?: string[];
  verifiedResults?: Array<{ label: string; value: string; verified: true }>;
  seoTitle?: string;
  metaDescription?: string;
  ogImage?: MediaAsset;
};

export type Review = {
  id: string;
  rating: number;
  text: string;
  publicBuyerIdentity?: string;
  country?: string;
  context?: string;
  source: 'Fiverr' | string;
  sourceUrl?: string;
  reviewDate?: string;
  featured?: boolean;
};

export type Article = {
  id: string;
  title: string;
  slug: string;
  excerpt: string;
  bodyHtml?: string;
  category?: string;
  publishedDate?: string;
  updatedDate?: string;
  author?: string;
  readingTime?: string;
  canonical?: string;
  seoTitle?: string;
  metaDescription?: string;
  featureImage?: MediaAsset;
};
