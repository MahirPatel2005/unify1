<?php

namespace App\Controllers;

class Pwa extends App_Controller {
  function __construct() {
    parent::__construct();
    helper(array('general'));
  }

  public function manifest() {
    return $this->app_manifest();
  }

  public function app_manifest() {
    $base_url = base_url();

    $pwa_theme_color = get_setting("pwa_theme_color");
    if (!$pwa_theme_color) {
      $pwa_theme_color = "#1c2026";
    }

    $favicon_url = $base_url . "assets/images/favicon.png";
    $favicon_192_url = $base_url . "assets/images/favicon-192.png";

    $isMobile = preg_match('/(android|iphone|ipad|windows phone)/i', get_array_value($_SERVER, 'HTTP_USER_AGENT'));

    $display_mode = "standalone";
    if (!$isMobile) {
      $display_mode = "minimal-ui";
    }

    $manifest = [
      "name" => get_setting("app_title"),
      "short_name" => get_setting("app_title"),
      "start_url" => "{$base_url}index.php",
      "display" => $display_mode,
      "background_color" => $pwa_theme_color,
      "theme_color" => $pwa_theme_color,
      "icons" => [
        [
          "src" => $favicon_url,
          "sizes" => "32x32",
          "type" => "image/png"
        ],
        [
          "src" => $favicon_192_url,
          "sizes" => "192x192",
          "type" => "image/png"
        ]
      ]
    ];

    return $this->response->setContentType('application/json')
      ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate')
      ->setBody(json_encode($manifest));
  }

  public function service_worker() {
    $app_version = get_setting("app_version");
    $base_url = base_url();

    $serviceWorkerScript = "
            const CACHE_NAME = 'pwa-cache-{$app_version}';
            const urlsToCache = [
              '{$base_url}assets/css/app.all.css',
              '{$base_url}assets/js/app.all.js',
            ];

            self.addEventListener('install', event => {
              event.waitUntil(
                caches.open(CACHE_NAME)
                  .then(cache => {
                    return cache.addAll(urlsToCache);
                  })
              );
            });

            self.addEventListener('fetch', event => {
              event.respondWith(
                caches.match(event.request)
                  .then(response => {
                    return response || fetch(event.request);
                  })
              );
            });

            self.addEventListener('activate', event => {
              const cacheWhitelist = [CACHE_NAME];
              event.waitUntil(
                caches.keys().then(cacheNames => {
                  return Promise.all(
                    cacheNames.map(cacheName => {
                      if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                      }
                    })
                  );
                })
              );
            });
        ";

    // Set the content type to application/javascript and return the script
    return $this->response->setContentType('application/javascript')->setBody($serviceWorkerScript);
  }
}
