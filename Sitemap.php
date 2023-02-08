<?php

function getSiteLinks($dir) {
  $files = scandir($dir);
  $links = array();

  foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    if (is_dir("$dir/$file")) {
      $links = array_merge($links, getSiteLinks("$dir/$file"));
    } else {
      $links[] = "$dir/$file";
    }
  }

  return $links;
}

$siteLinks = getSiteLinks($_POST['dir']);

$sitemap = new SimpleXMLElement('<xml />');

$urlset = $sitemap->addChild('urlset');
$urlset->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

foreach ($siteLinks as $link) {
  $url = $urlset->addChild('url');
  $loc = $url->addChild('loc', $link);
}

header('Content-Type: text/xml');
print($sitemap->asXML());

?>
