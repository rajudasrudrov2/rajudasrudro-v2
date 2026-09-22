export const siteRoutes = {
  home: '/',
  work: '/work/',
  services: '/services/',
  about: '/about/',
  reviews: '/reviews/',
  insights: '/insights/',
  contact: '/contact/',
} as const;

export const site = {
  name: 'Raju Das Rudro',
  shortRole: 'AI Creative · UGC · Web for Modern Brands',
  description: 'AI UGC, AI video production, AI spokesperson videos and web design for modern brands.',
  url: 'https://rajudasrudro.com',
  email: 'contactwithrudro@gmail.com',
  responseCommitment: 'Usually within 24 hours',
  fiverr: 'https://www.fiverr.com/rajudasrudro',
} as const;

/**
 * Header navigation is intentionally surface-specific.
 * Do not derive desktop primary navigation from a general route list:
 * Reviews and Contact are mobile-only text destinations, while Home is the brand link.
 */
export const headerNavigation = {
  home: { label: 'Home', href: siteRoutes.home },
  desktopPrimary: [
    { label: 'Work', href: siteRoutes.work },
    { label: 'Services', href: siteRoutes.services },
    { label: 'About', href: siteRoutes.about },
    { label: 'Insights', href: siteRoutes.insights },
  ],
  mobile: [
    { label: 'Work', href: siteRoutes.work },
    { label: 'Services', href: siteRoutes.services },
    { label: 'About', href: siteRoutes.about },
    { label: 'Insights', href: siteRoutes.insights },
    { label: 'Reviews', href: siteRoutes.reviews },
    { label: 'Contact', href: siteRoutes.contact },
  ],
  contactCta: { label: 'Start a Project', href: siteRoutes.contact },
} as const;

export const verifiedTrust = [
  { value: '4.9', label: 'Fiverr Rating', source: 'Fiverr' },
  { value: '470', label: 'Fiverr Reviews', source: 'Fiverr' },
] as const;
