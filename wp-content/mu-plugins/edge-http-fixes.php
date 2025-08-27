<?php
/**
 * Force IPv4, pass CA bundle to cURL and help loopbacks.
 */
add_action('http_api_curl', function($h, $r, $url){
  // IPv6-Fallstricke umgehen
  if (defined('CURL_IPRESOLVE_V4')) {
    curl_setopt($h, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  }

  // CA-Bundle direkt an cURL setzen (falls php.ini übergangen wird)
  $caf = ini_get('curl.cainfo') ?: ini_get('openssl.cafile');
  if ($caf && file_exists($caf)) {
    curl_setopt($h, CURLOPT_CAINFO, $caf);
  }

  // Loopback: Wenn WP sich selbst aufruft und DNS zickt,
  // pinne die IP via CURLOPT_RESOLVE (umgeht DNS).
  $host = parse_url($url, PHP_URL_HOST);
  $scheme = parse_url($url, PHP_URL_SCHEME);
  $port = $scheme === 'https' ? 443 : 80;

  $homeHost = parse_url(home_url(), PHP_URL_HOST);
  $wpHosts  = ['api.wordpress.org','downloads.wordpress.org','s.w.org'];

  if ($host && ( $host === $homeHost || in_array($host, $wpHosts, true) )) {
    $ip = gethostbyname($host); // IPv4
    if ($ip && $ip !== $host) {
      curl_setopt($h, CURLOPT_RESOLVE, ["{$host}:{$port}:{$ip}"]); // DNS pinning
    }
  }

  curl_setopt($h, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($h, CURLOPT_TIMEOUT, 20);
}, 10, 3);

// Falls irgendwo WP_HTTP_BLOCK_EXTERNAL aktiviert ist:
if (defined('WP_HTTP_BLOCK_EXTERNAL') && WP_HTTP_BLOCK_EXTERNAL && !defined('WP_ACCESSIBLE_HOSTS')) {
  define('WP_ACCESSIBLE_HOSTS', 'api.wordpress.org,downloads.wordpress.org,*.wordpress.org,s.w.org');
}