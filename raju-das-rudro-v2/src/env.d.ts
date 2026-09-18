/// <reference types="astro/client" />

interface ImportMetaEnv {
  readonly PUBLIC_SITE_ENV?: string;
  readonly PUBLIC_SITE_URL?: string;
  readonly PUBLIC_CONTACT_ENDPOINT?: string;
  readonly CMS_GRAPHQL_URL?: string;
  readonly CMS_REST_URL?: string;
  readonly CONTACT_DELIVERY_API_KEY?: string;
}

interface ImportMeta {
  readonly env: ImportMetaEnv;
}
