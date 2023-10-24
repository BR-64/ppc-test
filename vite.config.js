import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

// export default defineConfig({
//   base: "",
//   plugins: [
//     laravel({
//       buildDirectory: "build/",
//       input: [
//         "resources/css/reset.css",
//         "resources/css/app.css",
//         "resources/css/style3.css",
//         "resources/css/queries.css",
//         // "resources/assets/scss/main.scss",
//         "resources/js/app.js",
//         "resources/js/nav.js",
//       ],
//       refresh: true,
//     }),
//   ],
// });

export default defineConfig({
  plugins: [
    laravel({
      buildDirectory: "build/",
      input: [
        // "resources/css/reset.css",
        // "resources/css/app.css",
        // "resources/css/queries.css",
        // "resources/css/style3.css",
        // // "resources/assets/scss/main.scss",
        // "resources/js/app.js",
        // "resources/js/nav.js",
      ],
      refresh: [
        // ...refreshPaths,
        refreshPaths,
        "app/Filament/**",
        "app/Forms/Components/**",
        "app/Livewire/**",
        "app/Infolists/Components/**",
        "app/Providers/Filament/**",
        "app/Tables/Columns/**",
      ],
    }),
  ],
});
