import { defineConfig, loadEnv } from 'vite';
import symfonyPlugin from 'vite-plugin-symfony';

/* if you're using React */
// import react from '@vitejs/plugin-react';


export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '')

  const siteConfig = {
    name: 'default',
    buildLocation: env.APP_ENV === 'production' ? 'build' : 'build_dev',
    assetsLocation: 'assets',
  };

  return {
    plugins: [
      /* react(), // if you're using React */
      symfonyPlugin(),
    ],
    build: {
      outDir: `./public/assets/app/${siteConfig.buildLocation}/`,
      rollupOptions: {
        input: {
          index: `./${siteConfig.assetsLocation}/js/index.js`,
          'index-noncritical': `./${siteConfig.assetsLocation}/js/index-noncritical.js`
        },
      }
    },
  }
});
