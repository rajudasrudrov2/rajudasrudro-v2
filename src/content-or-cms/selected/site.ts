import {
  headerNavigation as localHeaderNavigation,
  site as localSite,
  siteRoutes as localSiteRoutes,
  verifiedTrust as localVerifiedTrust,
} from '../../data/site';
import { getContentSource } from '../source';
import { getWordPressSite, normalizeCmsMedia } from '../wordpress';

const source = getContentSource();
const cms = source === 'wordpress' ? await getWordPressSite() : null;

export const siteRoutes = localSiteRoutes;
export const headerNavigation = localHeaderNavigation;
export const verifiedTrust = localVerifiedTrust;

export const site = cms
  ? {
      ...localSite,
      email: cms.publicEmail,
      responseCommitment: cms.responseExpectation,
      fiverr: cms.fiverrUrl,
      linkedin: cms.social?.linkedin || '',
      defaultSocialImage: normalizeCmsMedia(cms.defaultSocialImage),
    }
  : {
      ...localSite,
      linkedin: '',
      defaultSocialImage: undefined,
    };
