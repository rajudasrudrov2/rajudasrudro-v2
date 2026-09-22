import {
  aboutClientExpectations as localAboutClientExpectations,
  aboutContent as localAboutContent,
  aboutPillars as localAboutPillars,
  aboutPrinciples as localAboutPrinciples,
  aboutSelectedWorkIds as localAboutSelectedWorkIds,
  type AboutIconName,
} from '../../data/about';
import { cmsHtmlToParagraphs } from '../html';
import { getContentSource } from '../source';
import { getWordPressAbout, normalizeCmsMedia } from '../wordpress';

export type { AboutIconName } from '../../data/about';

const source = getContentSource();
export const aboutUsesCms = source === 'wordpress';
const cms = source === 'wordpress' ? await getWordPressAbout() : null;
const icons: AboutIconName[] = ['spark', 'check', 'users', 'briefcase', 'globe'];

export const aboutContent = cms
  ? {
      ...localAboutContent,
      intro: cms.intro,
      story: cmsHtmlToParagraphs(cms.storyHtml),
      personalNote: cms.personalNote,
    }
  : localAboutContent;

export const aboutPillars = cms
  ? cms.pillars
      .filter((item): item is string => typeof item === 'string' && Boolean(item.trim()))
      .map((title, index) => ({
        number: String(index + 1).padStart(2, '0'),
        title,
        description: '',
        serviceSlugs: cms.featuredServices[index] ? [cms.featuredServices[index].slug] : [],
      }))
  : localAboutPillars;

export const aboutPrinciples = cms
  ? cms.principles
      .filter((item): item is string => typeof item === 'string' && Boolean(item.trim()))
      .map((title, index) => ({ title, description: '', icon: icons[index % icons.length] }))
  : localAboutPrinciples;

export const aboutClientExpectations = cms
  ? cms.expectations
      .filter((item): item is string => typeof item === 'string' && Boolean(item.trim()))
      .map((title, index) => ({ title, description: '', icon: icons[(index + 2) % icons.length] }))
  : localAboutClientExpectations;

export const aboutSelectedWorkIds = cms
  ? cms.featuredProjects.map((project) => String(project.id))
  : [...localAboutSelectedWorkIds];

export const aboutSelectedReviewIds = cms
  ? cms.featuredReviews.map((review) => String(review.id))
  : [];

export const aboutPortrait = cms
  ? normalizeCmsMedia(cms.portrait)
  : {
      src: '/images/raju-das-rudro.webp',
      alt: 'Portrait of Raju Das Rudro',
      width: 635,
      height: 640,
    };
