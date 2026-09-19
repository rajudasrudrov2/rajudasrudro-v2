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

export type WorkProjectCategory =
  | 'AI UGC'
  | 'AI Video'
  | 'AI Spokesperson'
  | 'Web Design'
  | 'Web Development';

export type WorkProjectStatus = 'verified' | 'development-preview';
export type WorkPublicationStatus = 'published' | 'draft' | 'development-preview';
export type WorkMediaStatus = 'poster' | 'video' | 'pending';
export type CaseStudyStatus = 'available' | 'pending';
export type ProjectTagTone = 'brand' | 'blue' | 'neutral';

export type CaseStudyDeliverable = {
  value?: string;
  label: string;
  detail?: string;
};

export type CaseStudyMediaItem = {
  id: string;
  title: string;
  subtitle?: string;
  poster: MediaAsset;
  video?: VideoAsset;
};

export type CaseStudyHighlight = {
  title: string;
  media?: MediaAsset;
};

export type CaseStudyOutcome = {
  label: string;
  value?: string;
  context?: string;
  verification: 'verified' | 'development-preview';
};

export type CaseStudyContent = {
  heroMedia?: MediaAsset;
  heroVideo?: VideoAsset;
  formatLabel?: string;
  overview: string;
  challenge?: {
    intro?: string;
    points?: string[];
  };
  approach?: {
    intro?: string;
    points?: string[];
  };
  deliverables?: CaseStudyDeliverable[];
  creativeVariations?: CaseStudyMediaItem[];
  highlights?: CaseStudyHighlight[];
  outcomes?: CaseStudyOutcome[];
  projectReviewId?: string;
  relatedProjectIds?: string[];
};

export type WorkProject = Project & {
  categories: WorkProjectCategory[];
  projectStatus: WorkProjectStatus;
  publicationStatus: WorkPublicationStatus;
  mediaStatus: WorkMediaStatus;
  caseStudyStatus: CaseStudyStatus;
  developmentPreview?: boolean;
  detailHref?: string;
  sortOrder: number;
  tags?: Array<{ label: string; tone?: ProjectTagTone }>;
  previewHighlights?: string[];
  caseStudy?: CaseStudyContent;
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

export type ArticlePublicationStatus = 'published' | 'draft' | 'development-preview';

export type ArticleBodyBlock =
  | {
      type: 'paragraph';
      text: string;
    }
  | {
      type: 'subheading';
      title: string;
    }
  | {
      type: 'unordered-list' | 'ordered-list';
      items: string[];
    }
  | {
      type: 'callout';
      title?: string;
      text: string;
    }
  | {
      type: 'steps';
      items: Array<{
        title: string;
        description: string;
      }>;
    }
  | {
      type: 'comparison';
      items: Array<{
        title: string;
        points: string[];
      }>;
    };

export type ArticleSection = {
  title: string;
  blocks: ArticleBodyBlock[];
};

export type Article = {
  id: string;
  title: string;
  slug: string;
  excerpt: string;
  bodyHtml?: string;
  body?: ArticleSection[];
  category?: string;
  publishedDate?: string;
  updatedDate?: string;
  author?: string;
  readingTime?: string;
  canonical?: string;
  seoTitle?: string;
  metaDescription?: string;
  featureImage?: MediaAsset;
  ogImage?: MediaAsset;
  featured?: boolean;
  publicationStatus?: ArticlePublicationStatus;
  developmentPreview?: boolean;
  previewDetailEnabled?: boolean;
  relatedServiceSlugs?: string[];
  relatedWorkIds?: string[];
};
