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

$dirs = new \DrdPlus\RulesSkeleton\Dirs($documentRoot);
$htmlHelper = $htmlHelper ?? \DrdPlus\RulesSkeleton\HtmlHelper::createFromGlobals($dirs);
if (\PHP_SAPI !== 'cli') {
    \DrdPlus\RulesSkeleton\TracyDebugger::enable($htmlHelper->isInProduction());
}
$configuration = \DrdPlus\RulesSkeleton\Configuration::createFromYml($dirs);
$servicesContainer = new \DrdPlus\RulesSkeleton\ServicesContainer($configuration, $htmlHelper);

$controller = $controller ?? new \DrdPlus\RulesSkeleton\RulesController($servicesContainer);
$controller->sendCustomHeaders();
$routeCollection = new \Symfony\Component\Routing\RouteCollection();
$controller->registerRouters($routeCollection);

$requestContext = new \Symfony\Component\Routing\RequestContext('/');
$urlMatcher = new \Symfony\Component\Routing\Matcher\UrlMatcher($routeCollection, $requestContext);
$parameters = $urlMatcher->matchRequest(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
$controllerMethod = $parameters['_controller'] ?? null;

if ($controller->isRequestedWebVersionUpdate()) {
    $controller->updateWebVersion();
    echo 'OK';

    return;
}
$controller->persistCurrentVersion();
echo $controller->getContent()->getStringContent();