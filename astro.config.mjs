import { defineConfig } from 'astro/config';

export default defineConfig({
  site: 'https://rajudasrudro.com',
  output: 'static',
  trailingSlash: 'always',
  build: {
    format: 'directory',
  },
});
