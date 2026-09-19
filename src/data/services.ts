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

export type ServiceProcessStep = {
  number: string;
  title: string;
  description: string;
};

export type ServiceValuePoint = {
  title: string;
  description: string;
  icon: ServiceIconName;
};

export type ServiceFaqItem = {
  question: string;
  answer: string;
};

export type ServiceCustomRequirement = {
  title: string;
  description: string;
};

export type AIUGCServiceDetail = {
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
  buyerNeeds: ServiceValuePoint[];
  deliverables: string[];
  formats: ServiceFormat[];
  variations: Array<{
    title: string;
    description: string;
    media: ServiceMedia;
  }>;
  process: ServiceProcessStep[];
  whyRaju: ServiceValuePoint[];
  faqs: ServiceFaqItem[];
  customRequirement: ServiceCustomRequirement;
};

export type AIVideoProductionPhase = {
  title: string;
  description: string;
  icon: ServiceIconName;
  items: string[];
};

export type AIVideoServiceDetail = {
  kind: 'ai-video';
  eyebrow: string;
  heroTitle: string;
  heroDescription: string;
  seoTitle: string;
  metaDescription: string;
  heroMedia: ServiceMedia[];
  explanation: {
    title: string;
    description: string;
    capabilities: ServiceValuePoint[];
  };
  creationTypes: ServiceFormat[];
  buyerNeeds: ServiceValuePoint[];
  productionPhases: AIVideoProductionPhase[];
  process: ServiceProcessStep[];
  whyRaju: ServiceValuePoint[];
  faqs: ServiceFaqItem[];
  customRequirement: ServiceCustomRequirement;
};

export type AISpokespersonProductionPhase = {
  title: string;
  description: string;
  icon: ServiceIconName;
  items: string[];
};

export type AISpokespersonServiceDetail = {
  kind: 'ai-spokesperson';
  eyebrow: string;
  heroTitle: string;
  heroDescription: string;
  seoTitle: string;
  metaDescription: string;
  heroMedia: ServiceMedia[];
  explanation: {
    title: string;
    description: string;
  };
  useCases: ServiceValuePoint[];
  presenterOptions: ServiceFormat[];
  productionPhases: AISpokespersonProductionPhase[];
  multilingual: {
    title: string;
    description: string;
    capabilities: string[];
  };
  comparison: {
    spokesperson: string[];
    traditional: string[];
  };
  process: ServiceProcessStep[];
  whyRaju: ServiceValuePoint[];
  faqs: ServiceFaqItem[];
  customRequirement: ServiceCustomRequirement;
};


export type WebDesignServiceDetail = {
  kind: 'web-design-development';
  eyebrow: string;
  heroTitle: string;
  heroDescription: string;
  seoTitle: string;
  metaDescription: string;
  heroMedia: ServiceMedia[];
  projectTypes: ServiceValuePoint[];
  foundations: ServiceValuePoint[];
  designDevelopment: {
    design: {
      title: string;
      description: string;
      items: string[];
      media: ServiceMedia;
    };
    development: {
      title: string;
      description: string;
      items: string[];
      media: ServiceMedia;
    };
  };
  responsiveExperience: {
    title: string;
    description: string;
    media: ServiceMedia;
    points: ServiceValuePoint[];
  };
  performance: ServiceValuePoint[];
  seoFoundations: ServiceValuePoint[];
  process: ServiceProcessStep[];
  clientReceives: string[];
  whyRaju: ServiceValuePoint[];
  faqs: ServiceFaqItem[];
  customRequirement: ServiceCustomRequirement;
};

export type ServiceDetail = AIUGCServiceDetail | AIVideoServiceDetail | AISpokespersonServiceDetail | WebDesignServiceDetail;

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
      src: '/images/work/product-showcase-preview.svg',
      alt: 'Development preview artwork representing professional AI video production',
      width: 800,
      height: 450,
    },
    chooser: 'Need broader marketing video?',
    detail: {
      kind: 'ai-video',
      eyebrow: 'AI Video Production',
      heroTitle: 'AI Video Production for Brands That Inspire and Convert.',
      heroDescription: 'From product videos to branded visual stories, I create professional AI-assisted videos with clear creative direction, multi-scene production and polished post-production.',
      seoTitle: 'AI Video Production for Brands — Raju Das Rudro',
      metaDescription: 'Professional AI-assisted video production for product videos, branded stories, marketing campaigns, explainers and multi-scene creative from Raju Das Rudro.',
      heroMedia: [
        {
          src: '/images/work/product-showcase-preview.svg',
          alt: 'Development preview artwork representing a polished AI product video',
          width: 800,
          height: 450,
        },
        {
          src: '/images/work/travel-ugc-preview.svg',
          alt: 'Development preview artwork representing cinematic brand storytelling',
          width: 800,
          height: 450,
        },
        {
          src: '/images/work/spokesperson-preview.svg',
          alt: 'Development preview artwork representing an AI presenter-led production',
          width: 800,
          height: 450,
        },
      ],
      explanation: {
        title: 'What is AI Video Production?',
        description: 'AI video production combines AI-assisted visuals, presenters or scene generation with professional creative direction and editing. It is designed for broader product, brand and marketing stories rather than only creator-style UGC.',
        capabilities: [
          { title: 'AI Visuals', description: 'Realistic scenes, product-focused imagery and concept-driven visual directions.', icon: 'spark' },
          { title: 'AI Presenters', description: 'Presenter-led options when a clear on-screen voice fits the brief.', icon: 'users' },
          { title: 'Professional Editing', description: 'Polished sequencing, captions, motion and brand-ready finishing.', icon: 'video' },
        ],
      },
      creationTypes: [
        {
          title: 'Commercial Videos',
          description: 'Polished brand and product advertising creative.',
          media: { src: '/images/work/food-ugc-preview.svg', alt: 'Development preview artwork for a commercial-style AI video', width: 800, height: 450 },
        },
        {
          title: 'Product Videos',
          description: 'Product-focused showcases, demos and visual stories.',
          media: { src: '/images/work/product-showcase-preview.svg', alt: 'Development preview artwork for an AI product video', width: 800, height: 450 },
        },
        {
          title: 'Social Media Ads',
          description: 'Short marketing videos shaped for social campaigns.',
          media: { src: '/images/work/ugc-social-preview.svg', alt: 'Development preview artwork for a social media AI video ad', width: 800, height: 450 },
        },
        {
          title: 'Explainer Videos',
          description: 'Structured videos that clarify a product, service or idea.',
          media: { src: '/images/work/saas-video-preview.svg', alt: 'Development preview artwork for an AI explainer video', width: 800, height: 450 },
        },
        {
          title: 'Brand Videos',
          description: 'Story-led visuals that communicate a brand direction or campaign idea.',
          media: { src: '/images/work/travel-ugc-preview.svg', alt: 'Development preview artwork for cinematic brand storytelling', width: 800, height: 450 },
        },
        {
          title: 'AI Presenter Videos',
          description: 'Professional presenter-led content for marketing or education.',
          media: { src: '/images/work/spokesperson-preview.svg', alt: 'Development preview artwork for an AI presenter video', width: 800, height: 450 },
        },
      ],
      buyerNeeds: [
        { title: 'Product launches', description: 'Create polished launch or promotional assets around a product story.', icon: 'spark' },
        { title: 'More creative for testing', description: 'Develop alternate concepts or visual directions from one brief.', icon: 'video' },
        { title: 'Professional branded videos', description: 'Move beyond creator-native UGC into broader brand presentation.', icon: 'briefcase' },
        { title: 'Social media campaigns', description: 'Build adaptable assets for campaign and channel-specific use.', icon: 'users' },
        { title: 'Explainers and demos', description: 'Show a product, service or idea with structured visual storytelling.', icon: 'monitor' },
        { title: 'Flexible production', description: 'Create scenes and variations without relying on a full traditional shoot for every asset.', icon: 'globe' },
      ],
      productionPhases: [
        {
          title: 'Creative',
          description: 'Concept and story development.',
          icon: 'spark',
          items: ['Concept & strategy', 'Script writing / adaptation', 'Storyboard direction when needed'],
        },
        {
          title: 'Production',
          description: 'AI-assisted visual production.',
          icon: 'video',
          items: ['AI visuals & scenes', 'AI presenters / voice when applicable', 'Custom branded elements'],
        },
        {
          title: 'Post-Production',
          description: 'Professional finishing.',
          icon: 'monitor',
          items: ['Professional editing', 'Captions & motion', 'Music & sound design when included'],
        },
        {
          title: 'Final Delivery',
          description: 'Approved export package.',
          icon: 'briefcase',
          items: ['Multiple variations where scoped', 'Social-ready formats', 'Platform-optimized files'],
        },
      ],
      process: [
        { number: '01', title: 'Share the Brief', description: 'Send your goal, audience, product or brand context and useful references.' },
        { number: '02', title: 'Creative Direction', description: 'Define the story, visual approach, script direction and scene plan.' },
        { number: '03', title: 'AI Production', description: 'Create the approved visuals, presenter elements and multi-scene edit.' },
        { number: '04', title: 'Review & Deliver', description: 'Review the production and receive the agreed final formats and revisions.' },
      ],
      whyRaju: [
        { title: 'End-to-end production', description: 'Creative direction, generation and editing can stay within one coordinated workflow.', icon: 'briefcase' },
        { title: 'Marketing-focused approach', description: 'Production choices are shaped around the communication goal and intended audience.', icon: 'spark' },
        { title: 'Multiple creative directions', description: 'A project can support alternate visual concepts or variations when the scope calls for them.', icon: 'video' },
        { title: 'International client experience', description: 'The service is positioned for brands, founders and teams working internationally.', icon: 'globe' },
        { title: 'Clear delivery workflow', description: 'Scope, formats and review expectations are confirmed before final production.', icon: 'check' },
      ],
      faqs: [
        { question: 'What types of AI videos can you create?', answer: 'The service can cover product videos, commercial-style creative, brand stories, explainers, social marketing assets and presenter-led videos when those formats fit the approved brief.' },
        { question: 'How is this different from AI UGC?', answer: 'AI UGC is creator-native and social-first. AI Video Production is broader and can use multi-scene, cinematic, product-led, presenter-led or concept-driven visual storytelling.' },
        { question: 'Do you help with the concept and script?', answer: 'Yes. Concept development and script writing or adaptation can be included when they are part of the agreed scope.' },
        { question: 'Can the visuals match my brand?', answer: 'Brand references, products, tone and visual guidelines can be used to shape the approved creative direction.' },
        { question: 'Can you create multiple versions?', answer: 'Yes, when multiple formats, scenes or creative variations are included in the approved project scope.' },
        { question: 'How do revisions work?', answer: 'Revision expectations are agreed before production. The specific revision scope depends on the project brief and deliverables.' },
        { question: 'Do you provide different aspect ratios?', answer: 'Required aspect ratios and delivery formats are agreed per project so the final files match the intended channels.' },
        { question: 'Can I request a custom AI video concept?', answer: 'Yes. Share the idea through the project inquiry form and I can review whether it fits the AI video production scope.' },
      ],
      customRequirement: {
        title: 'Not sure if this is the right video approach?',
        description: 'Share the product, campaign or story you want to communicate and I can review whether AI Video Production is the right fit.',
      },
    },
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
    detail: {
      kind: 'ai-spokesperson',
      eyebrow: 'AI Spokesperson Videos',
      heroTitle: 'Professional AI Spokesperson Videos Without Traditional Filming.',
      heroDescription: 'Done-for-you presenter-led videos that turn an approved script or message into polished business, marketing, educational and social content with professional editing and brand-aligned delivery.',
      seoTitle: 'AI Spokesperson Videos for Brands — Raju Das Rudro',
      metaDescription: 'Professional AI spokesperson video production for explainers, business presentations, social content and multilingual versions, delivered as a done-for-you service.',
      heroMedia: [
        {
          src: '/images/work/spokesperson-preview.svg',
          alt: 'Development preview artwork representing a professional AI spokesperson video',
          width: 800,
          height: 450,
        },
        {
          src: '/images/work/spokesperson-ad-preview.svg',
          alt: 'Development preview artwork representing presenter-led business communication',
          width: 800,
          height: 450,
        },
        {
          src: '/images/work/spokesperson-education-preview.svg',
          alt: 'Development preview artwork representing an educational AI spokesperson video',
          width: 800,
          height: 450,
        },
      ],
      explanation: {
        title: 'What Is an AI Spokesperson Video?',
        description: 'An AI spokesperson video uses a realistic virtual presenter to communicate an approved script or message directly to the audience. The service combines presenter direction, voice or audio workflow, visual production and professional editing into a finished video deliverable rather than software access.',
      },
      useCases: [
        { title: 'Product Explainers', description: 'Presenter-led explanations that introduce a product, feature or offer clearly.', icon: 'briefcase' },
        { title: 'SaaS Onboarding', description: 'Structured presenter content for product walkthroughs, onboarding and support communication.', icon: 'spark' },
        { title: 'Social Media Ads', description: 'Short presenter-led marketing creative shaped for social campaigns.', icon: 'video' },
        { title: 'Educational Videos', description: 'Scripted instructional or informational content with a consistent on-screen presenter.', icon: 'monitor' },
        { title: 'Landing Page Content', description: 'Direct presenter messaging that can support a product, service or campaign page.', icon: 'arrow' },
        { title: 'Business Promotion', description: 'Professional presenter-led communication for brand, service or company messaging.', icon: 'users' },
      ],
      presenterOptions: [
        {
          title: 'Professional Business',
          description: 'Formal presenter direction for corporate and professional messaging.',
          media: { src: '/images/work/spokesperson-preview.svg', alt: 'Development preview artwork for a professional business presenter direction', width: 800, height: 450 },
        },
        {
          title: 'Casual & Friendly',
          description: 'Approachable presenter styling for modern brand communication.',
          media: { src: '/images/work/lifestyle-ugc-preview.svg', alt: 'Development preview artwork for a casual presenter direction', width: 800, height: 450 },
        },
        {
          title: 'Industry Specific',
          description: 'Presenter and visual direction shaped around the subject and audience.',
          media: { src: '/images/work/spokesperson-education-preview.svg', alt: 'Development preview artwork for an industry-focused presenter direction', width: 800, height: 450 },
        },
        {
          title: 'Different Presenter Styles',
          description: 'Presenter direction can vary by tone, role and visual context when the selected workflow supports it.',
          media: { src: '/images/work/spokesperson-ad-preview.svg', alt: 'Development preview artwork representing an alternate presenter style', width: 800, height: 450 },
        },
        {
          title: 'Multilingual Versions',
          description: 'Additional language versions can be planned when the selected production workflow supports the requested language.',
          media: { src: '/images/work/spokesperson-preview.svg', alt: 'Development preview artwork representing multilingual presenter versions', width: 800, height: 450 },
        },
        {
          title: 'Custom Look',
          description: 'Wardrobe, scene and background direction can be discussed for the approved project scope.',
          media: { src: '/images/work/ugc-social-preview.svg', alt: 'Development preview artwork representing a custom presenter look and setting', width: 800, height: 450 },
        },
      ],
      productionPhases: [
        {
          title: 'Script & Message',
          description: 'Clarify what the presenter needs to communicate.',
          icon: 'video',
          items: ['Message refinement', 'Script writing or adaptation when scoped', 'Hook and key-point planning', 'Brand-aligned tone'],
        },
        {
          title: 'Presenter',
          description: 'Choose a suitable presenter direction for the brief.',
          icon: 'users',
          items: ['Presenter selection', 'Look and scene direction', 'Voice or audio direction', 'Language-version planning when required'],
        },
        {
          title: 'Production',
          description: 'Build the approved presenter-led video.',
          icon: 'spark',
          items: ['Presenter video generation', 'Voice and lip-sync workflow where supported', 'Brand asset integration', 'Multiple versions when included in scope'],
        },
        {
          title: 'Post-Production',
          description: 'Finish the video for the agreed channels.',
          icon: 'monitor',
          items: ['Professional editing', 'Captions and on-screen text', 'Background audio when included', 'Final delivery in agreed formats'],
        },
      ],
      multilingual: {
        title: 'Multilingual / Localized Versions',
        description: 'A presenter-led message can be adapted into additional language versions when the selected production method supports the requested language and audio workflow.',
        capabilities: [
          'Language adaptation is confirmed per project rather than assumed universally',
          'Voice and lip-sync workflows can be considered where the selected tools support them',
          'The approved core message can be adapted across agreed language versions',
          'Accent, dialect and pronunciation requirements are reviewed before production',
        ],
      },
      comparison: {
        spokesperson: [
          'No physical studio filming is required for the presenter-led version',
          'Script and message can stay controlled across approved variations',
          'Presenter and visual direction can be planned before production',
          'Language and localization options can be reviewed per project',
        ],
        traditional: [
          'Requires presenter, crew or recording logistics',
          'Script changes may require additional recording',
          'Scheduling and location can affect iteration speed',
          'Additional language versions may require separate recording workflows',
        ],
      },
      process: [
        { number: '01', title: 'Brief & Script', description: 'Confirm the audience, message, script direction, presenter tone and intended use.' },
        { number: '02', title: 'Presenter Direction', description: 'Select the presenter style, look, scene and approved voice or language direction.' },
        { number: '03', title: 'Production', description: 'Create the presenter-led video and integrate approved brand or visual elements.' },
        { number: '04', title: 'Review & Delivery', description: 'Refine the edit within the agreed scope and deliver the approved final files.' },
      ],
      whyRaju: [
        { title: 'Script-to-video workflow', description: 'Message, presenter direction, production and editing can stay within one coordinated workflow.', icon: 'briefcase' },
        { title: 'Presenter-led communication focus', description: 'The production is shaped around clear delivery of the approved message to the intended audience.', icon: 'users' },
        { title: 'Brand-aligned finishing', description: 'Editing, captions and visual treatment can follow the approved brand direction.', icon: 'video' },
        { title: 'Localization planning', description: 'Language-version requirements can be reviewed early so the production method fits the brief.', icon: 'globe' },
        { title: 'International client workflow', description: 'Briefing, feedback and delivery are structured for remote brand and marketing teams.', icon: 'check' },
      ],
      faqs: [
        { question: 'What is an AI spokesperson video?', answer: 'It is a presenter-led finished video where a realistic virtual presenter communicates an approved script or message. The service is production and delivery of the video, not access to avatar software.' },
        { question: 'How is this different from AI UGC?', answer: 'AI UGC is creator-native and social-first. AI Spokesperson Videos focus on direct presenter-led communication, explanation and scripted message delivery.' },
        { question: 'Can I provide my own script?', answer: 'Yes. You can provide an approved script, or script writing and adaptation can be included when it is part of the agreed project scope.' },
        { question: 'Can you create multilingual versions?', answer: 'Multilingual versions can be considered when the requested language and selected production workflow support them. Language, pronunciation and delivery requirements are confirmed before production.' },
        { question: 'How does lip-sync work?', answer: 'Lip-sync may be part of the production workflow when the selected tools and source material support it. Exact output depends on the presenter method, language and audio used, so perfect lip-sync is not guaranteed.' },
        { question: 'Can the presenter exactly match a real person?', answer: 'An exact likeness or voice match is not promised. Presenter direction depends on the available production method, permitted source material and the approved project scope.' },
        { question: 'How do revisions work?', answer: 'Revision expectations are agreed before production. The specific revision scope depends on the presenter setup, script, deliverables and approved brief.' },
        { question: 'Can I request a custom presenter style or use case?', answer: 'Yes. Share the audience, script, visual direction, language requirements and intended use through the project inquiry form so the production approach can be reviewed.' },
      ],
      customRequirement: {
        title: 'Need a custom presenter or language setup?',
        description: 'Share the script, audience, presenter direction and any language or localization requirements so I can review a suitable production approach.',
      },
    },
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
    detail: {
      kind: 'web-design-development',
      eyebrow: 'Web Design & Development',
      heroTitle: 'Fast, Modern Websites Built Around Real Business Goals.',
      heroDescription: 'I design and develop custom websites that are modern, responsive, conversion-conscious and built for usability, performance and long-term growth.',
      seoTitle: 'Web Design & Development for Modern Brands — Raju Das Rudro',
      metaDescription: 'Custom responsive web design and frontend development for businesses, personal brands and service teams, with performance, accessibility and technical SEO foundations.',
      heroMedia: [
        {
          src: '/images/work/web-ecommerce-preview.svg',
          alt: 'Development preview artwork showing a responsive ecommerce website concept',
          width: 800,
          height: 450,
        },
        {
          src: '/images/work/web-business-preview.svg',
          alt: 'Development preview artwork showing a professional business website concept',
          width: 800,
          height: 450,
        },
      ],
      projectTypes: [
        { title: 'Business Websites', description: 'Professional websites for established businesses, local services and growing teams.', icon: 'briefcase' },
        { title: 'Personal Brand Sites', description: 'Clear, credible personal websites built around expertise, services and proof.', icon: 'users' },
        { title: 'Service Websites', description: 'Conversion-conscious sites that explain an offer and guide visitors toward the next step.', icon: 'arrow' },
        { title: 'Landing Pages', description: 'Focused pages for campaigns, launches, offers and lead-generation goals.', icon: 'monitor' },
        { title: 'Portfolio Websites', description: 'Work-led websites for creators, consultants and professionals who need a strong presentation.', icon: 'spark' },
        { title: 'Custom WordPress Builds', description: 'Project-specific WordPress implementation where that platform fits the content and workflow requirements.', icon: 'globe' },
      ],
      foundations: [
        { title: 'Fast Performance', description: 'Performance-conscious implementation with responsive assets and minimal unnecessary frontend overhead.', icon: 'spark' },
        { title: 'Clear UX', description: 'Structured navigation and page hierarchy designed to make important information easier to understand.', icon: 'check' },
        { title: 'Responsive Design', description: 'Layouts planned for mobile, tablet and desktop rather than scaled from one screen size.', icon: 'monitor' },
        { title: 'SEO Foundation', description: 'Semantic structure, metadata foundations and crawl-friendly technical setup where the project scope supports it.', icon: 'globe' },
        { title: 'Easy Content Management', description: 'Content editing workflows can be planned around the selected platform and project requirements.', icon: 'briefcase' },
        { title: 'Scalable Structure', description: 'Reusable page patterns and components help future additions stay consistent and maintainable.', icon: 'users' },
      ],
      designDevelopment: {
        design: {
          title: 'Design',
          description: 'A clear interface and content system shaped around the project goal before implementation begins.',
          items: ['Page structure and information architecture', 'Responsive UI/UX design', 'Reusable visual system and components', 'Content hierarchy and interaction planning'],
          media: { src: '/images/work/web-service-preview.svg', alt: 'Development preview artwork representing responsive website design', width: 800, height: 450 },
        },
        development: {
          title: 'Development',
          description: 'A maintainable frontend implementation selected around the approved project scope rather than one mandatory technology for every client.',
          items: ['Responsive frontend build', 'CMS or integration work where scoped', 'Performance-conscious implementation', 'Technical and on-page SEO foundations'],
          media: { src: '/images/work/web-business-preview.svg', alt: 'Development preview artwork representing a professional website implementation', width: 800, height: 450 },
        },
      },
      responsiveExperience: {
        title: 'Responsive Experience',
        description: 'The same content system is deliberately adapted across screen sizes so navigation, reading and conversion paths remain usable from mobile to desktop.',
        media: { src: '/images/work/web-ecommerce-preview.svg', alt: 'Development preview artwork representing a responsive website across devices', width: 800, height: 450 },
        points: [
          { title: 'Optimized for every screen', description: 'Layouts respond deliberately rather than relying on a desktop-only composition.', icon: 'monitor' },
          { title: 'Touch-friendly interface', description: 'Controls and spacing are planned for practical mobile interaction.', icon: 'check' },
          { title: 'Same great experience', description: 'Content priorities and key actions remain coherent across device sizes.', icon: 'users' },
        ],
      },
      performance: [
        { title: 'Optimized Media', description: 'Responsive images and sensible loading strategies help control page weight.', icon: 'spark' },
        { title: 'Minimal Scripts', description: 'Unnecessary JavaScript and third-party overhead are avoided where the experience does not need them.', icon: 'video' },
        { title: 'Clean Frontend', description: 'Semantic, maintainable frontend structure supports usability and long-term changes.', icon: 'monitor' },
        { title: 'Core Web Vitals Aware', description: 'Layout stability, interaction cost and loading behavior are considered during implementation.', icon: 'check' },
        { title: 'Lightweight Interactions', description: 'Native behavior and focused JavaScript are preferred over heavy decorative dependencies.', icon: 'arrow' },
      ],
      seoFoundations: [
        { title: 'Semantic Structure', description: 'Logical headings and semantic HTML provide a clear content hierarchy.', icon: 'briefcase' },
        { title: 'Metadata Support', description: 'Page titles, descriptions and social metadata foundations can be configured per page.', icon: 'spark' },
        { title: 'Clean URLs', description: 'Readable route structures support navigation, sharing and crawlability.', icon: 'arrow' },
        { title: 'Sitemap / Robots', description: 'Technical crawl-control foundations can be included where appropriate to the project.', icon: 'globe' },
        { title: 'Structured Data Support', description: 'Relevant schema can be added when the page content provides accurate information to support it.', icon: 'check' },
        { title: 'Internal Linking', description: 'Important related pages can be connected through a deliberate internal-linking structure.', icon: 'users' },
        { title: 'Responsive Performance', description: 'Mobile usability and performance-conscious implementation support a stronger technical base.', icon: 'monitor' },
      ],
      process: [
        { number: '01', title: 'Goals & Content', description: 'Clarify the business goal, audience, required pages, content and references.' },
        { number: '02', title: 'Structure & Design', description: 'Plan the page hierarchy, responsive layout and interface direction.' },
        { number: '03', title: 'Development', description: 'Build the approved interface and implement the agreed functionality.' },
        { number: '04', title: 'QA & Launch', description: 'Review responsiveness, content, accessibility and launch-readiness within the agreed scope.' },
      ],
      clientReceives: [
        'Custom, project-specific design direction',
        'Responsive implementation across agreed device ranges',
        'Reusable production components where the project benefits from them',
        'Performance-conscious media and frontend handling',
        'Technical and on-page SEO foundations within scope',
        'QA and launch-preparation support for the agreed delivery',
      ],
      whyRaju: [
        { title: 'Design + Development Together', description: 'The visual system and frontend implementation can be handled as one coordinated project.', icon: 'briefcase' },
        { title: 'Modern & Clean Design', description: 'Interfaces stay focused on clarity, responsive usability and the content that matters.', icon: 'spark' },
        { title: 'Technical Expertise', description: 'Implementation decisions are shaped around maintainability, accessibility and performance needs.', icon: 'monitor' },
        { title: 'Clear Communication', description: 'Scope, decisions and feedback points are kept visible throughout the project.', icon: 'users' },
        { title: 'Long-Term Structure', description: 'Reusable patterns help future page and content additions remain consistent after launch.', icon: 'check' },
      ],
      faqs: [
        { question: 'Do you handle both design and development?', answer: 'Yes, when both are included in the agreed scope. A project can cover page structure, responsive UI design and frontend implementation in one coordinated workflow.' },
        { question: 'Can you redesign an existing website?', answer: 'Yes. A redesign can start from the existing content and business goals, then improve structure, interface quality and responsive behavior within the approved project scope.' },
        { question: 'Will the website be mobile responsive?', answer: 'Responsive behavior is part of the service direction. The exact supported layouts and testing scope are confirmed for each project.' },
        { question: 'Can I update the website content myself?', answer: 'That depends on the chosen platform and project requirements. If content management is needed, an appropriate CMS or editing workflow can be scoped before development.' },
        { question: 'Do you optimize websites for performance?', answer: 'I use performance-conscious frontend practices such as responsive media, controlled JavaScript and layout-stability considerations. Specific performance scores are not guaranteed.' },
        { question: 'Is SEO included?', answer: 'Technical and on-page SEO foundations can be included, such as semantic structure, metadata, crawl controls and internal linking. Search-engine rankings or traffic outcomes are not guaranteed.' },
        { question: 'How much does a website cost?', answer: 'Pricing depends on the project scope, page count, content, design complexity, functionality and integration requirements. Send the brief for a project-specific review.' },
        { question: 'How long does a website take?', answer: 'Timeline depends on scope, content readiness, feedback cycles and functionality. A project schedule is confirmed after the requirements are reviewed.' },
        { question: 'What do you need from me to start?', answer: 'A useful starting brief includes your business goal, target audience, required pages, content/assets, examples you like, required functionality and any existing website that should be considered.' },
      ],
      customRequirement: {
        title: 'Have a custom website or redesign in mind?',
        description: 'Share the goals, content, functionality and references you already have so I can review the right design and development scope for the project.',
      },
    },
  },
];

export function getServiceBySlug(slug: string) {
  return services.find((service) => service.slug === slug);
}
