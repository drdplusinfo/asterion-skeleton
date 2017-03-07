<?php
$documentRoot = rtrim(dirname($_SERVER['SCRIPT_FILENAME']), '\/');

require_once $documentRoot . '/vendor/autoload.php';

header('Content-Type: application/json');
header('Expires: on, 01 Jan 1970 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Content-Type: text/cache-manifest');

$manifestCache = new \DrdPlus\RulesSkeleton\ManifestCache($documentRoot);
echo $manifestCache->getManifest();
exit;