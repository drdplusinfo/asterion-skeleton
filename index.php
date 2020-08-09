<?php
\error_reporting(-1);
if ((!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '127.0.0.1') || PHP_SAPI === 'cli') {
    \ini_set('display_errors', '1');
} else {
    \ini_set('display_errors', '0');
}
$documentRoot = $documentRoot ?? (PHP_SAPI !== 'cli' ? \rtrim(\dirname($_SERVER['SCRIPT_FILENAME']), '\/') : \getcwd());

/** @noinspection PhpIncludeInspection */
require_once $documentRoot . '/vendor/autoload.php';

$dirs = $dirs ?? new \DrdPlus\AsterionSkeleton\Dirs($documentRoot);
$htmlHelper = $htmlHelper
    ?? \DrdPlus\AsterionSkeleton\HtmlHelper::createFromGlobals($dirs, \DrdPlus\AsterionSkeleton\Environment::createFromGlobals());
if (PHP_SAPI !== 'cli') {
    \DrdPlus\AsterionSkeleton\TracyDebugger::enable($htmlHelper->isInProduction());
}
$configuration = \DrdPlus\AsterionSkeleton\Configuration::createFromYml($dirs);
$servicesContainer = new \DrdPlus\AsterionSkeleton\ServicesContainer($configuration, $htmlHelper);

$rulesApplication = $rulesApplication ?? $controller ?? new \DrdPlus\AsterionSkeleton\RulesApplication($servicesContainer);
$rulesApplication->run();