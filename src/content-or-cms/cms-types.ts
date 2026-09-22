export type CmsIdentityType = 'service' | 'project' | 'review' | 'article';

export type CmsIdentity = {
  id: number;
  slug: string;
  type: CmsIdentityType;
};

export type CmsMedia = {
  id: number;
  url: string;
  alt: string;
  width: number;
  height: number;
  mimeType: string;
};

export type CmsRelation = {
  id: number;
  slug: string;
  title: string;
};

export type CmsReviewRelation = {
  id: number;
  slug: string;
  reviewer: string;
};

export type CmsTerm = {
  id: number;
  slug: string;
  name: string;
};

export type CmsSeo = {
  title: string;
  description: string;
  ogImage: CmsMedia | null;
};

export type CmsSite = {
  type: 'site';
  publicEmail: string;
  fiverrUrl: string;
  responseExpectation: string;
  social: { linkedin: string };
  defaultSocialImage: CmsMedia | null;
};

export type CmsServiceListItem = CmsIdentity & {
  type: 'service';
  title: string;
  shortDescription: string;
  featured: boolean;
  order: number;
  heroMedia: CmsMedia | null;
};

export type CmsRow = {
  title: string;
  body: string;
};

export type CmsServiceDetail = CmsServiceListItem & {
  positioning: string;
  heroHtml: string;
  capabilities: unknown[];
  deliverables: unknown[];
  useCases: unknown[];
  formats: unknown[];
  process: CmsRow[];
  faq: CmsRow[];
  whyRajuHtml: string;
  seo: CmsSeo;
};

export type CmsCaseStudySummary = {
  available: boolean;
};

export type CmsCaseStudyDetail = {
  overviewHtml: string;
  challengeHtml: string;
  approachHtml: string;
  deliverables: unknown[];
  creativeHtml: string;
  outcomesHtml: string;
  relatedReview: CmsRelation | null;
  relatedProjects: CmsRelation[];
};

export type CmsProjectListItem = CmsIdentity & {
  type: 'project';
  title: string;
  shortSummary: string;
  featureMedia: CmsMedia | null;
  categories: CmsTerm[];
  relatedService: CmsRelation | null;
  featured: boolean;
  caseStudy: CmsCaseStudySummary;
  order: number;
};

export type CmsProjectDetail = Omit<CmsProjectListItem, 'caseStudy'> & {
  overviewHtml: string;
  gallery: CmsMedia[];
  caseStudy: CmsCaseStudyDetail | null;
  seo: CmsSeo;
};

export type CmsReview = CmsIdentity & {
  type: 'review';
  reviewer: string;
  text: string;
  rating: number | null;
  source: string;
  sourceUrl: string;
  context: string;
  country: string;
  reviewDate: string | null;
  featured: boolean;
  relatedService: CmsRelation | null;
  relatedProject: CmsRelation | null;
  order: number;
};

export type CmsAuthor = {
  id: number;
  name: string;
};

export type CmsArticleListItem = CmsIdentity & {
  type: 'article';
  title: string;
  excerpt: string;
  categories: CmsTerm[];
  featureImage: CmsMedia | null;
  featured: boolean;
  publishedAt: string;
  updatedAt: string;
  author: CmsAuthor | null;
  readingTime: number;
};

export type CmsArticleDetail = CmsArticleListItem & {
  bodyHtml: string;
  relatedServices: CmsRelation[];
  relatedProjects: CmsRelation[];
  seo: CmsSeo;
};

export type CmsAbout = {
  type: 'about';
  intro: string;
  storyHtml: string;
  pillars: unknown[];
  principles: unknown[];
  expectations: unknown[];
  personalNote: string;
  portrait: CmsMedia | null;
  featuredProjects: CmsRelation[];
  featuredServices: CmsRelation[];
  featuredReviews: CmsReviewRelation[];
};
