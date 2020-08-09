<?php declare(strict_types=1);

namespace DrdPlus\Tests\AsterionSkeleton\Partials;

use DeviceDetector\Parser\Bot;
use DrdPlus\AsterionSkeleton\Configuration;
use DrdPlus\AsterionSkeleton\CookiesService;
use DrdPlus\AsterionSkeleton\Dirs;
use DrdPlus\AsterionSkeleton\Environment;
use DrdPlus\AsterionSkeleton\HtmlHelper;
use DrdPlus\AsterionSkeleton\Request;
use DrdPlus\AsterionSkeleton\RulesApplication;
use DrdPlus\AsterionSkeleton\CurrentWebVersion;
use DrdPlus\AsterionSkeleton\WebCache;

trait ClassesTrait
{
    /**
     * @return string|CurrentWebVersion
     */
    protected function getCurrentWebVersionClass(): string
    {
        return CurrentWebVersion::class;
    }

    /**
     * @return string|WebCache
     */
    protected function getWebCacheClass(): string
    {
        return WebCache::class;
    }

    /**
     * @return string|Configuration
     */
    protected function getConfigurationClass(): string
    {
        return Configuration::class;
    }

    /**
     * @return string|Request
     */
    protected function getRequestClass(): string
    {
        return Request::class;
    }

    /**
     * @return string|CookiesService
     */
    protected function getCookiesServiceClass()
    {
        return CookiesService::class;
    }

    /**
     * @return string|RulesApplication
     */
    protected function getRulesApplicationClass(): string
    {
        return RulesApplication::class;
    }

    /**
     * @return string|Dirs
     */
    protected function getDirsClass(): string
    {
        return Dirs::class;
    }

    /**
     * @return string|Bot
     */
    protected function getBotClass(): string
    {
        return Bot::class;
    }

    /**
     * @return string|Environment
     */
    protected function getEnvironmentClass(): string
    {
        return Environment::class;
    }

    /**
     * @return string|HtmlHelper
     */
    protected function getHtmlHelperClass(): string
    {
        return HtmlHelper::class;
    }
}