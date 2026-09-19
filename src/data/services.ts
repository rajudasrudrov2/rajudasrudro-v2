export type ServiceIconName =
  | 'star'
  | 'users'
  | 'briefcase'
  | 'globe'
  | 'video'
  | 'spark'
  | 'monitor'
  | 'arrow'
  | 'check';

export type ServiceMedia = {
  src: string;
  alt: string;
  width: number;
  height: number;
};

export type ServiceFormat = {
  title: string;
  description: string;
  media: ServiceMedia;
};

export type ServiceDetail = {
  kind: 'ai-ugc';
  eyebrow: string;
  heroTitle: string;
  heroDescription: string;
  seoTitle: string;
  metaDescription: string;
  heroMedia: ServiceMedia[];
  explanation: {
    title: string;
    description: string;
    comparisons: Array<{
      title: string;
      description: string;
      media: ServiceMedia;
    }>;
  };
  buyerNeeds: Array<{
    title: string;
    description: string;
    icon: ServiceIconName;
  }>;
  deliverables: string[];
  formats: ServiceFormat[];
  variations: Array<{
    title: string;
    description: string;
    media: ServiceMedia;
  }>;
  process: Array<{
    number: string;
    title: string;
    description: string;
  }>;
  whyRaju: Array<{
    title: string;
    description: string;
    icon: ServiceIconName;
  }>;
  faqs: Array<{
    question: string;
    answer: string;
  }>;
  customRequirement: {
    title: string;
    description: string;
  };
};

export type ServiceDefinition = {
  slug: string;
  title: string;
  short: string;
  href: string;
  bullets: string[];
  ctaLabel: string;
  flagship: boolean;
  media: ServiceMedia;
  chooser: string;
  detail?: ServiceDetail;
};

export const services: ServiceDefinition[] = [
  {
    slug: 'ai-ugc-video-ads',
    title: 'AI UGC Video Ads',
    short: 'Professional AI-powered UGC-style videos built for brands, campaigns and social platforms.',
    href: '/services/ai-ugc-video-ads/',
    bullets: ['UGC-style video ads', 'Hook variations', 'Script adaptation', 'Captions & editing'],
    ctaLabel: 'Explore AI UGC',
    flagship: true,
    media: {
      src: '/images/work/featured-ai-ugc-preview.svg',
      alt: 'Development preview artwork representing an AI UGC service',
      width: 1200,
      height: 675,
    },
    chooser: 'Need social-first creative?',
    detail: {
      kind: 'ai-ugc',
      eyebrow: 'AI UGC Video Ads',
      heroTitle: 'AI UGC Video Ads Built for Modern Brands.',
      heroDescription: 'Creator-style AI UGC video ads for product promotion, paid social, ecommerce, campaign testing and branded creative without requiring a traditional filming workflow for every asset.',
      seoTitle: 'AI UGC Video Ads for Modern Brands — Raju Das Rudro',
      metaDescription: 'Creator-style AI UGC video ad production for ecommerce, paid social and brand campaigns, with hook variations, editing, captions and platform-ready delivery.',
      heroMedia: [
        {
          src: '/images/home/hero-ugc-preview.svg',
          alt: 'Development preview artwork representing creator-style AI UGC',
          width: 800,
          height: 1000,
        },
        {
          src: '/images/work/spokesperson-preview.svg',
          alt: 'Development preview artwork representing presenter-led creative',
          width: 800,
          height: 450,
        },
        {
          src: '/images/work/lifestyle-ugc-preview.svg',
          alt: 'Development preview artwork representing lifestyle UGC creative',
          width: 800,
          height: 450,
        },
      ],
      explanation: {
        title: 'What is AI UGC Video Ads?',
        description: 'AI UGC combines creator-style advertising with AI-assisted production workflows. The goal is natural, social-first creative that can be planned, adapted and delivered in multiple versions for marketing use.',
        comparisons: [
          {
            title: 'Traditional UGC workflow',
            description: 'Creator-led filming can work well, but availability, reshoots and logistics can make iteration slower.',
            media: {
              src: '/images/work/lifestyle-ugc-preview.svg',
              alt: 'Development preview artwork illustrating a creator-style filming concept',
              width: 800,
              height: 450,
            },
          },
          {
            title: 'AI UGC workflow',
            description: 'AI-assisted production can support faster creative iteration, multiple hooks and more flexible versioning.',
            media: {
              src: '/images/work/featured-ai-ugc-preview.svg',
              alt: 'Development preview artwork illustrating AI UGC creative production',
              width: 1200,
              height: 675,
            },
          },
        ],
      },
      buyerNeeds: [
        { title: 'More ad creative', description: 'Create more usable concepts for campaigns and content testing.', icon: 'video' },
        { title: 'Faster iteration', description: 'Move from brief to new creative directions without a full reshoot cycle.', icon: 'spark' },
        { title: 'Multiple hooks', description: 'Develop different openings and angles around the same offer or product.', icon: 'briefcase' },
        { title: 'Social-first content', description: 'Build creator-style assets that feel native to modern feeds.', icon: 'users' },
        { title: 'Less filming dependence', description: 'Reduce reliance on traditional production logistics for every version.', icon: 'monitor' },
        { title: 'More testable assets', description: 'Prepare variations that marketing teams can evaluate and refine.', icon: 'globe' },
      ],
      deliverables: [
        'Creative direction and concept development',
        'Script writing or script adaptation',
        'Hook development and alternate angles',
        'AI talent / presenter selection where applicable',
        'AI video generation and production',
        'Professional editing and motion',
        'Captions, text overlays and brand styling',
        'Multiple creative variations and social-ready exports',
      ],
      formats: [
        {
          title: 'Testimonial Style',
          description: 'Creator-led social proof structure without inventing customer testimony.',
          media: { src: '/images/work/lifestyle-ugc-preview.svg', alt: 'Development preview artwork for testimonial-style UGC formatting', width: 800, height: 450 },
        },
        {
          title: 'Problem / Solution',
          description: 'Lead with a recognizable problem, then move into the product or offer.',
          media: { src: '/images/work/ugc-product-preview.svg', alt: 'Development preview artwork for a problem and solution UGC format', width: 800, height: 450 },
        },
        {
          title: 'Product Demo',
          description: 'Show how a product works through a clear creator-style demonstration.',
          media: { src: '/images/work/clean-product-preview.svg', alt: 'Development preview artwork for a product demonstration format', width: 800, height: 450 },
        },
        {
          title: 'Listicle Style',
          description: 'Present multiple reasons, features or benefits in a fast social format.',
          media: { src: '/images/work/ugc-social-preview.svg', alt: 'Development preview artwork for listicle-style UGC formatting', width: 800, height: 450 },
        },
        {
          title: 'Founder / Expert Style',
          description: 'Presenter-led explanation for educational, product or authority-focused creative.',
          media: { src: '/images/work/spokesperson-preview.svg', alt: 'Development preview artwork for founder or expert-style video', width: 800, height: 450 },
        },
        {
          title: 'Direct Response Ad',
          description: 'Structured creative with a direct marketing message and clear next action.',
          media: { src: '/images/work/product-showcase-preview.svg', alt: 'Development preview artwork for direct-response creative', width: 800, height: 450 },
        },
        {
          title: 'Social-native Video',
          description: 'Short, feed-friendly creative designed around a natural social presentation.',
          media: { src: '/images/work/travel-ugc-preview.svg', alt: 'Development preview artwork for social-native video formatting', width: 800, height: 450 },
        },
      ],
      variations: [
        {
          title: 'Core Concept',
          description: 'Primary creative direction and message structure.',
          media: { src: '/images/work/featured-ai-ugc-preview.svg', alt: 'Development preview artwork for the core AI UGC creative concept', width: 1200, height: 675 },
        },
        {
          title: 'Hook A',
          description: 'A product-led opening angle.',
          media: { src: '/images/work/ugc-product-preview.svg', alt: 'Development preview artwork for hook variation A', width: 800, height: 450 },
        },
        {
          title: 'Hook B',
          description: 'A lifestyle-led opening angle.',
          media: { src: '/images/work/lifestyle-ugc-preview.svg', alt: 'Development preview artwork for hook variation B', width: 800, height: 450 },
        },
        {
          title: 'Hook C',
          description: 'A clean product-focused opening angle.',
          media: { src: '/images/work/clean-product-preview.svg', alt: 'Development preview artwork for hook variation C', width: 800, height: 450 },
        },
      ],
      process: [
        { number: '01', title: 'Share the Brief', description: 'Send the product, audience, campaign goal and any useful references.' },
        { number: '02', title: 'Creative Direction', description: 'Define the concept, hooks, script direction and visual style.' },
        { number: '03', title: 'Production', description: 'Produce and edit the approved creator-style AI UGC variations.' },
        { number: '04', title: 'Delivery', description: 'Receive the final assets in the agreed formats with revisions handled clearly.' },
      ],
      whyRaju: [
        { title: 'AI UGC focus', description: 'AI UGC is the flagship creative service.', icon: 'video' },
        { title: 'End-to-end workflow', description: 'Concept, scripting, production and editing can stay in one workflow.', icon: 'briefcase' },
        { title: 'Creative variations', description: 'Hooks and alternate versions can be planned from the same core idea.', icon: 'spark' },
        { title: 'International clients', description: 'The service is positioned for brands and teams working internationally.', icon: 'globe' },
        { title: 'Verified public reviews', description: 'Accepted Fiverr review summaries remain separated from project-specific claims.', icon: 'star' },
        { title: 'Clear delivery', description: 'Scope, formats and review expectations are agreed before production.', icon: 'check' },
      ],
      faqs: [
        { question: 'What do you need from me to start?', answer: 'A clear brief is the best starting point: product or service details, target audience, campaign goal, key messages, preferred format and any references you want considered.' },
        { question: 'Do you write or adapt the script?', answer: 'Yes. Script writing or adaptation can be included when it is part of the agreed project scope.' },
        { question: 'Can you create multiple hooks?', answer: 'Yes. Multiple hooks and creative angles can be planned when the brief calls for variation testing.' },
        { question: 'Can you create different AI presenters?', answer: 'Different presenter directions can be considered where the production method and available tools support the requested look and usage.' },
        { question: 'What video formats do you deliver?', answer: 'Formats are agreed per project. Social-first vertical delivery is common, and other aspect ratios can be prepared when included in the approved scope.' },
        { question: 'Can you match my brand style?', answer: 'Yes. Brand references, visual guidelines, tone and examples can be used to shape the approved creative direction.' },
        { question: 'How do revisions work?', answer: 'Revision scope is agreed before production so the feedback cycle is clear. Final revision terms depend on the specific project brief.' },
        { question: 'Can you help with a custom AI creative format?', answer: 'Yes. If your requirement fits the broader AI creative/video service scope, send the details through the project inquiry form and I can review the best approach.' },
      ],
      customRequirement: {
        title: 'Need a custom AI UGC setup or creative format?',
        description: 'Share a specific idea, industry or style in mind and I can review whether it fits the AI creative/video production scope.',
      },
    },
  },
  {
    slug: 'ai-video-production',
    title: 'AI Video Production',
    short: 'High-quality AI video production for marketing, product promotion and branded content.',
    href: '/services/ai-video-production/',
    bullets: ['AI commercial videos', 'Product videos', 'Social media creative', 'Branded video content'],
    ctaLabel: 'Explore AI Video Production',
    flagship: false,
    media: {
      src: '/images/work/food-ugc-preview.svg',
      alt: 'Development preview artwork representing AI video production',
      width: 800,
      height: 450,
    },
    chooser: 'Need broader marketing video?',
  },
  {
    slug: 'ai-spokesperson-videos',
    title: 'AI Spokesperson Videos',
    short: 'Professional presenter-style videos without traditional filming.',
    href: '/services/ai-spokesperson-videos/',
    bullets: ['AI spokesperson videos', 'Product explainers', 'Educational content', 'Social ads'],
    ctaLabel: 'Explore AI Spokesperson',
    flagship: false,
    media: {
      src: '/images/work/spokesperson-preview.svg',
      alt: 'Development preview artwork representing AI spokesperson videos',
      width: 800,
      height: 450,
    },
    chooser: 'Need a presenter-style video?',
  },
  {
    slug: 'web-design-development',
    title: 'Web Design & Development',
    short: 'Fast, modern and conversion-focused websites built with strong UX and technical performance.',
    href: '/services/web-design-development/',
    bullets: ['Custom website design', 'Responsive development', 'Website redesign', 'SEO-ready foundation'],
    ctaLabel: 'Explore Web Design',
    flagship: false,
    media: {
      src: '/images/work/web-ecommerce-preview.svg',
      alt: 'Development preview artwork representing web design and development',
      width: 800,
      height: 450,
    },
    chooser: 'Need a modern website or redesign?',
  },
];

export function getServiceBySlug(slug: string) {
  return services.find((service) => service.slug === slug);
}
