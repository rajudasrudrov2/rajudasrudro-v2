import { siteRoutes } from './site';

export const homeTrustMetrics = [
  { value: '4.9', label: 'Fiverr Rating', icon: 'star' },
  { value: '470', label: 'Fiverr Reviews', icon: 'users' },
  { value: 'AI UGC', label: 'Flagship Service', icon: 'briefcase' },
  { value: 'Global', label: 'International Clients', icon: 'globe' },
] as const;

export const homeWorkPreviews = [
  {
    title: 'AI UGC Project Preview',
    context: 'Development media placeholder',
    href: siteRoutes.work,
    media: '/images/home/work-ugc-preview.svg',
    alt: 'Abstract development preview for an AI UGC project card',
  },
  {
    title: 'AI Spokesperson Project Preview',
    context: 'Development media placeholder',
    href: siteRoutes.work,
    media: '/images/home/work-spokesperson-preview.svg',
    alt: 'Abstract development preview for an AI spokesperson project card',
  },
  {
    title: 'AI Product Video Preview',
    context: 'Development media placeholder',
    href: siteRoutes.work,
    media: '/images/home/work-product-preview.svg',
    alt: 'Abstract development preview for an AI product video project card',
  },
  {
    title: 'Web Design Project Preview',
    context: 'Development media placeholder',
    href: siteRoutes.work,
    media: '/images/home/work-web-preview.svg',
    alt: 'Abstract development preview for a web design project card',
  },
] as const;

export const homeServiceGroups = [
  {
    icon: 'video',
    title: 'AI UGC & Video Creative',
    description: 'UGC-style and presenter-led creative built for modern marketing workflows.',
    primaryLabel: 'Explore AI UGC',
    primaryHref: '/services/ai-ugc-video-ads/',
    links: [
      { label: 'AI UGC Video Ads', href: '/services/ai-ugc-video-ads/' },
      { label: 'AI Spokesperson Videos', href: '/services/ai-spokesperson-videos/' },
    ],
  },
  {
    icon: 'spark',
    title: 'AI-Powered Creative Services',
    description: 'AI video production for product, campaign and branded-content needs.',
    primaryLabel: 'Explore AI Video',
    primaryHref: '/services/ai-video-production/',
    links: [
      { label: 'AI Video Production', href: '/services/ai-video-production/' },
      { label: 'Product & campaign creative', href: '/services/ai-video-production/' },
    ],
  },
  {
    icon: 'monitor',
    title: 'Web Design & Development',
    description: 'Fast, modern and conversion-focused websites with strong UX foundations.',
    primaryLabel: 'Explore Web Design',
    primaryHref: '/services/web-design-development/',
    links: [
      { label: 'Web Design & Development', href: '/services/web-design-development/' },
      { label: 'Responsive development', href: '/services/web-design-development/' },
    ],
  },
] as const;

export const productionSteps = [
  { number: '01', title: 'Brief', description: 'Share your goals, audience and creative direction.' },
  { number: '02', title: 'Create', description: 'I produce the approved creative direction and refine the assets.' },
  { number: '03', title: 'Deliver & Refine', description: 'Receive the final assets with revisions handled clearly.' },
] as const;

export const reviewMigrationPlaceholder = {
  title: 'More verified reviews pending migration',
  text: 'Additional review copy, buyer identity and service context will appear only after source verification.',
} as const;
