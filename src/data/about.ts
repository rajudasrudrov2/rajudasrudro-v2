export type AboutIconName = 'star' | 'users' | 'briefcase' | 'globe' | 'video' | 'spark' | 'monitor' | 'check';

export const aboutContent = {
  eyebrow: 'About Raju',
  title: 'Raju Das Rudro',
  role: 'AI Creative, UGC & Web Specialist',
  intro:
    'I help modern brands create creator-led AI UGC, broader AI video content and responsive web experiences that are clear, useful and built for real business needs.',
  storyTitle: 'From Digital Marketing to AI-Driven Creative Work',
  story: [
    'My background grew through digital and video marketing, content strategy, YouTube-focused work and video production. That foundation taught me to look beyond visuals and think about the audience, the message and how creative work is actually used.',
    'Today, I combine that experience with AI-assisted creative workflows and Web Design & Development—bringing strategy, production and practical implementation together without turning every project into a complicated process.',
  ],
  personalNote:
    'I enjoy work that combines creativity, AI and web technology. The goal is simple: create work that looks considered, communicates clearly and is useful in the real context where a client needs it.',
  seo: {
    title: 'About Raju Das Rudro — AI Creative, UGC & Web Specialist',
    description:
      'Learn about Raju Das Rudro, his background in digital and video marketing, and his current focus on AI UGC, AI video production and responsive web design and development.',
  },
} as const;

export const aboutPillars = [
  {
    number: '01',
    title: 'AI UGC & AI Creative',
    description: 'Creator-style UGC video ads and social-first creative, with presenter-led work available when the message needs a spokesperson.',
    serviceSlugs: ['ai-ugc-video-ads', 'ai-spokesperson-videos'],
  },
  {
    number: '02',
    title: 'AI Video Production',
    description: 'Broader AI-assisted product, brand and multi-scene video production for marketing and communication.',
    serviceSlugs: ['ai-video-production'],
  },
  {
    number: '03',
    title: 'Web Design & Development',
    description: 'Responsive web experiences built around clear UX, performance-conscious implementation and maintainable frontend structure.',
    serviceSlugs: ['web-design-development'],
  },
] as const;

export const aboutPrinciples: Array<{
  title: string;
  description: string;
  icon: AboutIconName;
}> = [
  {
    title: 'Understand the Goal',
    description: 'Start with the audience, the business need and what useful success should look like.',
    icon: 'spark',
  },
  {
    title: 'Keep It Clear',
    description: 'Communicate directly, define the scope and avoid adding complexity that does not help the work.',
    icon: 'check',
  },
  {
    title: 'Build for the Audience',
    description: 'Shape creative and web experiences around how real people will view, understand and use them.',
    icon: 'users',
  },
  {
    title: 'Deliver Useful Output',
    description: 'Focus on polished, practical assets that are ready for their intended marketing or web context.',
    icon: 'briefcase',
  },
];

export const aboutClientExpectations: Array<{
  title: string;
  description: string;
  icon: AboutIconName;
}> = [
  { title: 'Direct Communication', description: 'Clear, practical project communication.', icon: 'users' },
  { title: 'Clear Scope & Plan', description: 'A defined direction before production begins.', icon: 'briefcase' },
  { title: 'Practical Creative Direction', description: 'Decisions connected to the actual project need.', icon: 'spark' },
  { title: 'Iterative Refinement', description: 'Focused refinement within the agreed scope.', icon: 'check' },
  { title: 'Production-Minded Delivery', description: 'Usable outputs prepared for the intended context.', icon: 'globe' },
];

export const aboutSelectedWorkIds = [
  'work-featured-ai-ugc-preview',
  'work-spokesperson-preview',
  'work-ecommerce-web-preview',
] as const;
