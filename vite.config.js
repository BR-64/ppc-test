import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
  base: "",
  plugins: [
    laravel({
      buildDirectory: "build/",
      input: [
        "resources/css/reset.css",
        "resources/css/app.css",
        "resources/css/queries.css",
        "resources/css/style3.css",
        // "resources/assets/scss/main.scss",
        "resources/js/app.js",
        "resources/js/nav.js",
      ],
      refresh: true,
    }),
  ],
  //   css: {
  //     preprocessorOptions: {
  //       scss: {
  //         additionalData: `
  //               @import "./src/styles/_animations.scss";
  //               @import "./src/styles/_variables.scss";
  //               @import "./src/styles/_mixins.scss";
  //               @import "./src/styles/_helpers.scss";
  //             `,
  //       },
  //     },
  //   },
});
