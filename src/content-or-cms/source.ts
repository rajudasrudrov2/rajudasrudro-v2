export type ContentSource = 'local' | 'wordpress';

const DEFAULT_CONTENT_SOURCE: ContentSource = 'local';

function importMetaEnv(): ImportMetaEnv | undefined {
  return import.meta.env;
}

export function parseContentSource(rawValue?: string | null): ContentSource {
  const raw = (rawValue ?? '').trim().toLowerCase();
  if (!raw) return DEFAULT_CONTENT_SOURCE;
  if (raw === 'local' || raw === 'wordpress') return raw;
  throw new Error(`Invalid CONTENT_SOURCE "${rawValue}". Expected "local" or "wordpress".`);
}

export function getContentSource(rawValue = importMetaEnv()?.CONTENT_SOURCE): ContentSource {
  return parseContentSource(rawValue);
}

export function normalizeCmsApiBaseUrl(rawValue?: string | null): string {
  const raw = (rawValue ?? '').trim();
  if (!raw) {
    throw new Error('CMS_API_BASE_URL is required when CONTENT_SOURCE=wordpress.');
  }

  let url: URL;
  try {
    url = new URL(raw);
  } catch {
    throw new Error('CMS_API_BASE_URL must be a valid absolute HTTPS URL.');
  }

  if (url.protocol !== 'https:') {
    throw new Error('CMS_API_BASE_URL must use https:.');
  }
  if (!url.hostname) {
    throw new Error('CMS_API_BASE_URL must include a hostname.');
  }

  url.hash = '';
  url.search = '';
  const pathname = url.pathname.replace(/\/+$/, '');
  url.pathname = pathname ? `${pathname}/` : '/';
  return url.toString();
}

export function getCmsApiBaseUrl(rawValue = importMetaEnv()?.CMS_API_BASE_URL): string {
  return normalizeCmsApiBaseUrl(rawValue);
}

export function isWordPressContentSource(rawValue = importMetaEnv()?.CONTENT_SOURCE): boolean {
  return parseContentSource(rawValue) === 'wordpress';
}
