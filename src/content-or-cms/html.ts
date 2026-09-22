import type { ArticleSection } from '@/types/content';

const FORBIDDEN_BLOCKS = /<(script|style|iframe|object|embed|template|form|input|button|select|textarea)\b[^>]*>[\s\S]*?<\/\1\s*>/gi;
const FORBIDDEN_SINGLE = /<(script|style|iframe|object|embed|template|form|input|button|select|textarea)\b[^>]*\/?\s*>/gi;
const EVENT_ATTR = /\s+on[a-z0-9_-]+\s*=\s*(?:"[^"]*"|'[^']*'|[^\s>]+)/gi;
const SRCDOC_ATTR = /\s+srcdoc\s*=\s*(?:"[^"]*"|'[^']*'|[^\s>]+)/gi;
const STYLE_ATTR = /\s+style\s*=\s*(?:"[^"]*"|'[^']*'|[^\s>]+)/gi;
const URL_ATTR = /\s+(href|src)\s*=\s*("([^"]*)"|'([^']*)'|([^\s>]+))/gi;
const ALLOWED_TAGS = new Set([
  'p','h2','h3','h4','ul','ol','li','strong','b','em','i','a','blockquote','figure','figcaption','img','br','hr','code','pre','span','sup','sub'
]);

function decodeUrlCharacterReferences(value: string): string {
  return value
    .replace(/&#x([0-9a-f]+);?/gi, (_match, hex: string) => String.fromCodePoint(Number.parseInt(hex, 16)))
    .replace(/&#([0-9]+);?/g, (_match, dec: string) => String.fromCodePoint(Number.parseInt(dec, 10)))
    .replace(/&colon;/gi, ':')
    .replace(/&tab;/gi, '\t')
    .replace(/&newline;/gi, '\n');
}

function safeUrl(value: string, attribute: 'href' | 'src'): string | null {
  const trimmed = value.trim();
  if (!trimmed) return null;
  if (trimmed.startsWith('#') && attribute === 'href') return trimmed;
  if (trimmed.startsWith('/') || trimmed.startsWith('./') || trimmed.startsWith('../')) return trimmed;

  const decoded = decodeUrlCharacterReferences(trimmed).trim();
  const protocolProbe = decoded.replace(/[\u0000-\u0020\u007f]+/g, '').toLowerCase();
  if (protocolProbe.startsWith('javascript:') || protocolProbe.startsWith('vbscript:')) return null;

  try {
    const url = new URL(decoded, 'https://rajudasrudro.com/');
    const allowed = attribute === 'src'
      ? new Set(['https:', 'http:', 'data:'])
      : new Set(['https:', 'http:', 'mailto:', 'tel:']);
    if (!allowed.has(url.protocol)) return null;
    if (url.protocol === 'data:' && !/^data:image\/(?:png|gif|jpe?g|webp);/i.test(decoded)) return null;
    return trimmed;
  } catch {
    return null;
  }
}

export function sanitizeCmsHtml(input: string): string {
  let html = String(input || '')
    .replace(FORBIDDEN_BLOCKS, '')
    .replace(FORBIDDEN_SINGLE, '')
    .replace(EVENT_ATTR, '')
    .replace(SRCDOC_ATTR, '')
    .replace(STYLE_ATTR, '');

  html = html.replace(URL_ATTR, (_match, name: 'href' | 'src', _quoted, dquote, squote, bare) => {
    const value = dquote ?? squote ?? bare ?? '';
    const safe = safeUrl(value, name.toLowerCase() as 'href' | 'src');
    return safe ? ` ${name.toLowerCase()}="${safe.replace(/"/g, '&quot;')}"` : '';
  });

  html = html.replace(/<\/?([a-z0-9-]+)(?:\s[^>]*)?>/gi, (tag, rawName: string) => {
    const name = rawName.toLowerCase();
    return ALLOWED_TAGS.has(name) ? tag : '';
  });

  return html;
}

export function stripHtmlToText(input: string): string {
  return sanitizeCmsHtml(input)
    .replace(/<br\s*\/?>/gi, '\n')
    .replace(/<\/p\s*>/gi, '\n')
    .replace(/<\/li\s*>/gi, '\n')
    .replace(/<[^>]+>/g, '')
    .replace(/&nbsp;/gi, ' ')
    .replace(/&amp;/gi, '&')
    .replace(/&lt;/gi, '<')
    .replace(/&gt;/gi, '>')
    .replace(/&quot;/gi, '"')
    .replace(/&#039;|&apos;/gi, "'")
    .replace(/\r/g, '')
    .replace(/\n{3,}/g, '\n\n')
    .trim();
}

export function cmsHtmlToParagraphs(input: string): string[] {
  const safe = sanitizeCmsHtml(input);
  const paragraphs = Array.from(safe.matchAll(/<p\b[^>]*>([\s\S]*?)<\/p>/gi))
    .map((match) => stripHtmlToText(match[1]))
    .filter(Boolean);
  if (paragraphs.length) return paragraphs;
  const text = stripHtmlToText(safe);
  return text ? text.split(/\n{2,}/).map((item) => item.trim()).filter(Boolean) : [];
}

export function cmsHtmlToArticleSections(input: string): ArticleSection[] {
  const safe = sanitizeCmsHtml(input);
  if (!safe.trim()) return [];

  const sections: ArticleSection[] = [];
  const h2Regex = /<h2\b[^>]*>([\s\S]*?)<\/h2>/gi;
  const matches = [...safe.matchAll(h2Regex)];

  if (!matches.length) {
    return [{ title: '', blocks: [{ type: 'html', html: safe }] }];
  }

  const firstIndex = matches[0].index ?? 0;
  const preface = safe.slice(0, firstIndex).trim();
  if (preface) sections.push({ title: '', blocks: [{ type: 'html', html: preface }] });

  matches.forEach((match, index) => {
    const start = (match.index ?? 0) + match[0].length;
    const end = index + 1 < matches.length ? (matches[index + 1].index ?? safe.length) : safe.length;
    const title = stripHtmlToText(match[1]);
    const body = safe.slice(start, end).trim();
    sections.push({
      title,
      blocks: body ? [{ type: 'html', html: body }] : [],
    });
  });

  return sections;
}
