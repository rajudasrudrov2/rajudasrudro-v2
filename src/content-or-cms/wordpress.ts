import type {
  CmsAbout,
  CmsArticleDetail,
  CmsArticleListItem,
  CmsMedia,
  CmsProjectDetail,
  CmsProjectListItem,
  CmsReview,
  CmsServiceDetail,
  CmsServiceListItem,
  CmsSite,
} from './cms-types';
import { getCmsApiBaseUrl } from './source';
import type { MediaAsset } from '@/types/content';

export const CMS_FETCH_TIMEOUT_MS = 10_000;

type FetchLike = typeof fetch;
type RequestOptions = {
  baseUrl?: string;
  fetchImpl?: FetchLike;
  timeoutMs?: number;
  memo?: Map<string, Promise<unknown>>;
};

export class CmsRequestError extends Error {
  status?: number;
  endpoint: string;
  constructor(message: string, endpoint: string, status?: number) {
    super(message);
    this.name = 'CmsRequestError';
    this.endpoint = endpoint;
    this.status = status;
  }
}

const buildMemo = new Map<string, Promise<unknown>>();

function isRecord(value: unknown): value is Record<string, unknown> {
  return Boolean(value) && typeof value === 'object' && !Array.isArray(value);
}

function requiredString(value: unknown, field: string, endpoint: string): string {
  if (typeof value !== 'string') throw new CmsRequestError(`Invalid CMS DTO: ${field} must be a string.`, endpoint);
  return value;
}

function requiredNumber(value: unknown, field: string, endpoint: string): number {
  if (typeof value !== 'number' || !Number.isFinite(value)) throw new CmsRequestError(`Invalid CMS DTO: ${field} must be a number.`, endpoint);
  return value;
}

function validateIdentity(value: unknown, endpoint: string, expectedType: string): void {
  if (!isRecord(value)) throw new CmsRequestError('Invalid CMS DTO: expected object.', endpoint);
  requiredNumber(value.id, 'id', endpoint);
  requiredString(value.slug, 'slug', endpoint);
  if (value.type !== expectedType) throw new CmsRequestError(`Invalid CMS DTO: type must be ${expectedType}.`, endpoint);
}

function validateCollection(value: unknown, endpoint: string, expectedType: string): void {
  if (!Array.isArray(value)) throw new CmsRequestError('Invalid CMS DTO: expected array.', endpoint);
  value.forEach((item) => validateIdentity(item, endpoint, expectedType));
}

function validateSite(value: unknown, endpoint: string): void {
  if (!isRecord(value) || value.type !== 'site') throw new CmsRequestError('Invalid CMS Site DTO.', endpoint);
  requiredString(value.publicEmail, 'publicEmail', endpoint);
  requiredString(value.fiverrUrl, 'fiverrUrl', endpoint);
  requiredString(value.responseExpectation, 'responseExpectation', endpoint);
}

function validateAbout(value: unknown, endpoint: string): void {
  if (!isRecord(value) || value.type !== 'about') throw new CmsRequestError('Invalid CMS About DTO.', endpoint);
  requiredString(value.intro, 'intro', endpoint);
  requiredString(value.storyHtml, 'storyHtml', endpoint);
  requiredString(value.personalNote, 'personalNote', endpoint);
}

function validateDetail(value: unknown, endpoint: string, type: string): void {
  validateIdentity(value, endpoint, type);
}

function endpointUrl(baseUrl: string, endpoint: string): string {
  return new URL(endpoint.replace(/^\/+/, ''), baseUrl).toString();
}

export function createCmsRequester(options: RequestOptions = {}) {
  const baseUrl = options.baseUrl ? options.baseUrl : getCmsApiBaseUrl();
  const fetchImpl = options.fetchImpl ?? fetch;
  const timeoutMs = options.timeoutMs ?? CMS_FETCH_TIMEOUT_MS;
  const memo = options.memo ?? buildMemo;

  return async function request<T>(endpoint: string, validate: (value: unknown, endpoint: string) => void): Promise<T> {
    const url = endpointUrl(baseUrl, endpoint);
    const cacheKey = `GET ${url}`;
    const existing = memo.get(cacheKey);
    if (existing) return existing as Promise<T>;

    const promise = (async () => {
      const controller = new AbortController();
      const timer = setTimeout(() => controller.abort(), timeoutMs);
      try {
        let response: Response;
        try {
          response = await fetchImpl(url, { method: 'GET', signal: controller.signal, headers: { Accept: 'application/json' } });
        } catch (error) {
          if (controller.signal.aborted) throw new CmsRequestError(`CMS request timed out after ${timeoutMs}ms.`, endpoint);
          throw new CmsRequestError(`CMS request failed: ${error instanceof Error ? error.message : 'network error'}.`, endpoint);
        }

        if (!response.ok) {
          throw new CmsRequestError(`CMS request returned HTTP ${response.status}.`, endpoint, response.status);
        }

        let value: unknown;
        try {
          value = await response.json();
        } catch {
          throw new CmsRequestError('CMS response was not valid JSON.', endpoint, response.status);
        }
        validate(value, endpoint);
        return value as T;
      } finally {
        clearTimeout(timer);
      }
    })();

    memo.set(cacheKey, promise);
    try {
      return await promise;
    } catch (error) {
      if (memo.get(cacheKey) === promise) memo.delete(cacheKey);
      throw error;
    }
  };
}

function defaultRequester() {
  return createCmsRequester();
}

export async function getWordPressSite(options?: RequestOptions): Promise<CmsSite> {
  return (options ? createCmsRequester(options) : defaultRequester())<CmsSite>('site', validateSite);
}

export async function getWordPressServices(options?: RequestOptions): Promise<CmsServiceListItem[]> {
  return (options ? createCmsRequester(options) : defaultRequester())<CmsServiceListItem[]>('services', (v, e) => validateCollection(v, e, 'service'));
}

export async function getWordPressServiceBySlug(slug: string, options?: RequestOptions): Promise<CmsServiceDetail | null> {
  const endpoint = `services/${encodeURIComponent(slug)}`;
  try {
    return await (options ? createCmsRequester(options) : defaultRequester())<CmsServiceDetail>(endpoint, (v, e) => validateDetail(v, e, 'service'));
  } catch (error) {
    if (error instanceof CmsRequestError && error.status === 404) return null;
    throw error;
  }
}

export async function getWordPressProjects(options?: RequestOptions): Promise<CmsProjectListItem[]> {
  return (options ? createCmsRequester(options) : defaultRequester())<CmsProjectListItem[]>('projects', (v, e) => validateCollection(v, e, 'project'));
}

export async function getWordPressProjectBySlug(slug: string, options?: RequestOptions): Promise<CmsProjectDetail | null> {
  const endpoint = `projects/${encodeURIComponent(slug)}`;
  try {
    return await (options ? createCmsRequester(options) : defaultRequester())<CmsProjectDetail>(endpoint, (v, e) => validateDetail(v, e, 'project'));
  } catch (error) {
    if (error instanceof CmsRequestError && error.status === 404) return null;
    throw error;
  }
}

export async function getWordPressReviews(options?: RequestOptions): Promise<CmsReview[]> {
  return (options ? createCmsRequester(options) : defaultRequester())<CmsReview[]>('reviews', (v, e) => validateCollection(v, e, 'review'));
}

export async function getWordPressArticles(options?: RequestOptions): Promise<CmsArticleListItem[]> {
  return (options ? createCmsRequester(options) : defaultRequester())<CmsArticleListItem[]>('articles', (v, e) => validateCollection(v, e, 'article'));
}

export async function getWordPressArticleBySlug(slug: string, options?: RequestOptions): Promise<CmsArticleDetail | null> {
  const endpoint = `articles/${encodeURIComponent(slug)}`;
  try {
    return await (options ? createCmsRequester(options) : defaultRequester())<CmsArticleDetail>(endpoint, (v, e) => validateDetail(v, e, 'article'));
  } catch (error) {
    if (error instanceof CmsRequestError && error.status === 404) return null;
    throw error;
  }
}

export async function getWordPressAbout(options?: RequestOptions): Promise<CmsAbout> {
  return (options ? createCmsRequester(options) : defaultRequester())<CmsAbout>('about', validateAbout);
}

export function normalizeCmsMedia(media: CmsMedia | null | undefined): MediaAsset | undefined {
  if (!media || typeof media.url !== 'string' || !media.url.trim()) return undefined;
  return {
    src: media.url,
    alt: typeof media.alt === 'string' ? media.alt : '',
    ...(media.width > 0 ? { width: media.width } : {}),
    ...(media.height > 0 ? { height: media.height } : {}),
    ...(media.mimeType ? { mimeType: media.mimeType } : {}),
  };
}

export function clearCmsBuildMemo(): void {
  buildMemo.clear();
}

export function cmsBuildMemoSize(): number {
  return buildMemo.size;
}
