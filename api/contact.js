const MAX_BODY_BYTES = 20_000;

const SERVICE_VALUES = new Set([
  'ai-ugc-video-ads',
  'ai-video-production',
  'ai-spokesperson-videos',
  'web-design-development',
  'other',
]);

const BUDGET_VALUES = new Set(['', 'under-500', '500-1000', '1000-2500', '2500-plus', 'not-sure']);
const TIMELINE_VALUES = new Set(['', 'asap', '1-2-weeks', 'within-month', 'flexible']);
const ALLOWED_KEYS = new Set(['name', 'email', 'company', 'service', 'projectDetails', 'budget', 'timeline', 'website']);

const LIMITS = {
  name: { min: 2, max: 80 },
  email: { min: 5, max: 254 },
  company: { min: 0, max: 120 },
  projectDetails: { min: 20, max: 1000 },
  budget: { min: 0, max: 40 },
  timeline: { min: 0, max: 40 },
  website: { min: 0, max: 200 },
};

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function sendJson(res, status, body) {
  res.statusCode = status;
  res.setHeader('Content-Type', 'application/json; charset=utf-8');
  res.setHeader('Cache-Control', 'no-store');
  res.end(JSON.stringify(body));
}

function normalizeString(value) {
  return typeof value === 'string' ? value.trim() : null;
}

function parseJsonBody(req) {
  if (req.body && typeof req.body === 'object' && !Array.isArray(req.body)) return req.body;
  if (typeof req.body === 'string') {
    try {
      const parsed = JSON.parse(req.body);
      return parsed && typeof parsed === 'object' && !Array.isArray(parsed) ? parsed : null;
    } catch {
      return null;
    }
  }
  return null;
}

function validateBody(body) {
  const unknownKeys = Object.keys(body).filter((key) => !ALLOWED_KEYS.has(key));
  if (unknownKeys.length > 0) {
    return { ok: false, status: 400, code: 'INVALID_BODY', message: 'Unsupported fields were included.' };
  }

  const values = {
    name: normalizeString(body.name),
    email: normalizeString(body.email),
    company: normalizeString(body.company ?? ''),
    service: normalizeString(body.service),
    projectDetails: normalizeString(body.projectDetails),
    budget: normalizeString(body.budget ?? ''),
    timeline: normalizeString(body.timeline ?? ''),
    website: normalizeString(body.website ?? ''),
  };

  const fieldErrors = {};

  if (values.name === null || values.name.length < LIMITS.name.min || values.name.length > LIMITS.name.max) {
    fieldErrors.name = 'Please enter a name between 2 and 80 characters.';
  }

  if (values.email === null || values.email.length < LIMITS.email.min || values.email.length > LIMITS.email.max || !emailPattern.test(values.email)) {
    fieldErrors.email = 'Please enter a valid email address.';
  }

  if (values.service === null || !SERVICE_VALUES.has(values.service)) {
    fieldErrors.service = 'Please select a valid service or custom requirement.';
  }

  if (values.projectDetails === null || values.projectDetails.length < LIMITS.projectDetails.min || values.projectDetails.length > LIMITS.projectDetails.max) {
    fieldErrors.projectDetails = 'Please add between 20 and 1000 characters of project detail.';
  }

  if (values.company === null || values.company.length > LIMITS.company.max) {
    fieldErrors.company = 'Company must be 120 characters or fewer.';
  }

  if (values.budget === null || values.budget.length > LIMITS.budget.max || !BUDGET_VALUES.has(values.budget)) {
    fieldErrors.budget = 'Please select a valid budget range.';
  }

  if (values.timeline === null || values.timeline.length > LIMITS.timeline.max || !TIMELINE_VALUES.has(values.timeline)) {
    fieldErrors.timeline = 'Please select a valid timeline.';
  }

  if (values.website === null || values.website.length > LIMITS.website.max) {
    return { ok: false, status: 400, code: 'INVALID_BODY', message: 'Invalid request.' };
  }

  if (values.website) {
    return { ok: false, status: 400, code: 'INVALID_REQUEST', message: 'Invalid request.' };
  }

  if (Object.keys(fieldErrors).length > 0) {
    return { ok: false, status: 422, code: 'VALIDATION_ERROR', fieldErrors };
  }

  return {
    ok: true,
    payload: {
      name: values.name,
      email: values.email,
      company: values.company,
      service: values.service,
      projectDetails: values.projectDetails,
      budget: values.budget,
      timeline: values.timeline,
      source: 'website-contact',
      submittedAt: new Date().toISOString(),
    },
  };
}

function getWebhookUrl() {
  const raw = process.env.CONTACT_FORM_WEBHOOK_URL?.trim();
  if (!raw) return null;
  try {
    const url = new URL(raw);
    if (url.protocol !== 'https:' && url.protocol !== 'http:') return null;
    return url.toString();
  } catch {
    return null;
  }
}

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    res.setHeader('Allow', 'POST');
    return sendJson(res, 405, { ok: false, code: 'METHOD_NOT_ALLOWED' });
  }

  const contentType = String(req.headers?.['content-type'] || '').toLowerCase();
  if (!contentType.startsWith('application/json')) {
    return sendJson(res, 415, { ok: false, code: 'UNSUPPORTED_MEDIA_TYPE' });
  }

  const declaredLength = Number(req.headers?.['content-length'] || 0);
  if (Number.isFinite(declaredLength) && declaredLength > MAX_BODY_BYTES) {
    return sendJson(res, 413, { ok: false, code: 'PAYLOAD_TOO_LARGE' });
  }

  const body = parseJsonBody(req);
  if (!body) {
    return sendJson(res, 400, { ok: false, code: 'INVALID_JSON' });
  }

  let bodySize = 0;
  try {
    bodySize = Buffer.byteLength(JSON.stringify(body), 'utf8');
  } catch {
    return sendJson(res, 400, { ok: false, code: 'INVALID_BODY' });
  }
  if (bodySize > MAX_BODY_BYTES) {
    return sendJson(res, 413, { ok: false, code: 'PAYLOAD_TOO_LARGE' });
  }

  const validation = validateBody(body);
  if (!validation.ok) {
    const response = { ok: false, code: validation.code };
    if (validation.fieldErrors) response.fieldErrors = validation.fieldErrors;
    return sendJson(res, validation.status, response);
  }

  const webhookUrl = getWebhookUrl();
  if (!webhookUrl) {
    return sendJson(res, 503, { ok: false, code: 'DELIVERY_UNAVAILABLE' });
  }

  try {
    const upstream = await fetch(webhookUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json, text/plain, */*',
      },
      body: JSON.stringify(validation.payload),
      redirect: 'error',
    });

    if (!upstream.ok) {
      return sendJson(res, 502, { ok: false, code: 'DELIVERY_FAILED' });
    }

    return sendJson(res, 200, { ok: true });
  } catch {
    return sendJson(res, 502, { ok: false, code: 'DELIVERY_FAILED' });
  }
}
