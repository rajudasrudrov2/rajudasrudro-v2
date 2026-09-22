/// <reference types="astro/client" />

interface ImportMetaEnv {
  readonly PUBLIC_SITE_ENV?: string;
  readonly PUBLIC_SITE_URL?: string;
  readonly CONTENT_SOURCE?: 'local' | 'wordpress' | string;
  readonly CMS_API_BASE_URL?: string;
  readonly CMS_GRAPHQL_URL?: string;
  readonly CMS_REST_URL?: string;
  readonly CONTACT_DELIVERY_API_KEY?: string;
}

interface ImportMeta {
  readonly env: ImportMetaEnv;
}
