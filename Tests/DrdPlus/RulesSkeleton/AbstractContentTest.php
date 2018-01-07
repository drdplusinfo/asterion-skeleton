<?php
namespace Tests\DrdPlus\RulesSkeleton;

use DrdPlus\RulesSkeleton\UsagePolicy;
use Gt\Dom\HTMLDocument;
use PHPUnit\Framework\TestCase;

abstract class AbstractContentTest extends TestCase
{
    protected function setUp()
    {
        if (!\defined('DRD_PLUS_RULES_INDEX_FILE_NAME_TO_TEST')) {
            self::markTestSkipped('Missing constant \'DRD_PLUS_RULES_INDEX_FILE_NAME_TO_TEST\'');
        }
    }

    /**
     * @param bool $notCached
     * @return string
     */
    protected function getOwnershipConfirmationContent(bool $notCached = false): string
    {
        if ($notCached) {
            return $this->fetchRulesContent();
        }
        static $ownershipConfirmationContent;
        if ($ownershipConfirmationContent === null) {
            $ownershipConfirmationContent = $this->fetchRulesContent();
        }

        return $ownershipConfirmationContent;
    }

    private function fetchRulesContent(): string
    {
        \ob_start();
        /** @noinspection PhpIncludeInspection */
        include DRD_PLUS_RULES_INDEX_FILE_NAME_TO_TEST;

        return \ob_get_clean();
    }

    /**
     * @param string $show
     * @return string
     */
    protected function getRulesContent(string $show = ''): string
    {
        static $rulesContent = [];
        if (($rulesContent[$show] ?? null) === null) {
            if ($show !== '') {
                $_GET['show'] = $show;
            }
            $this->confirmOwnership();
            \ob_start();
            /** @noinspection PhpIncludeInspection */
            include DRD_PLUS_RULES_INDEX_FILE_NAME_TO_TEST;
            $rulesContent[$show] = \ob_get_clean();
            $this->removeOwnerShipConfirmation();
            unset($_GET['show']);
            self::assertNotSame($this->getOwnershipConfirmationContent(), $rulesContent);
        }

        return $rulesContent[$show];
    }

    private function confirmOwnership()
    {
        $_COOKIE[$this->getCookieNameForLocalOwnershipConfirmation()] = true; // this cookie simulates confirmation of ownership
    }

    private function getCookieNameForLocalOwnershipConfirmation(): string
    {
        static $cookieName;
        if ($cookieName === null) {
            $cookieName = $this->getCookieNameForOwnershipConfirmation(
                \basename($this->getDirName(DRD_PLUS_RULES_INDEX_FILE_NAME_TO_TEST))
            );
        }

        return $cookieName;
    }

    private function getDirName(string $fileName): string
    {
        $dirName = $fileName;
        while (\basename($dirName) === '.' || \basename($dirName) === '..' || !\is_dir($dirName)) {
            $dirName = \dirname(
                $dirName,
                \basename($dirName) === '.' || !\is_dir($dirName)
                    ? 1
                    : 2 // ..
            );
            if ($dirName === '/') {
                throw new \RuntimeException("Could not find name of dir by $fileName");
            }
        }

        return $dirName;
    }

    protected function getCookieNameForOwnershipConfirmation(string $rulesDirBasename): string
    {
        $usagePolicy = new UsagePolicy($rulesDirBasename);
        $reflectionClass = new \ReflectionClass(UsagePolicy::class);
        $getCookieName = $reflectionClass->getMethod('getCookieName');
        $getCookieName->setAccessible(true);

        return $getCookieName->invoke($usagePolicy);
    }

    private function removeOwnerShipConfirmation()
    {
        unset($_COOKIE[$this->getCookieNameForLocalOwnershipConfirmation()]);
    }

    protected function getRulesHtmlDocument(string $show = ''): HTMLDocument
    {
        static $rulesHtmlDocument = [];
        if (empty($rulesHtmlDocument[$show])) {
            $rulesHtmlDocument[$show] = new HTMLDocument($this->getRulesContent($show));
        }

        return $rulesHtmlDocument[$show];
    }

    /**
     * @param string $show = ''
     * @param string $hide = ''
     * @return string
     */
    protected function getRulesContentForDev(string $show = '', string $hide = ''): string
    {
        static $rulesContentForDev = [];
        if (empty($rulesContentForDev[$show][$hide])) {
            $this->confirmOwnership();
            $_GET['mode'] = 'dev';
            if ($show !== '') {
                $_GET['show'] = $show;
            }
            if ($hide !== '') {
                $_GET['hide'] = $hide;
            }
            \ob_start();
            /** @noinspection PhpIncludeInspection */
            include DRD_PLUS_RULES_INDEX_FILE_NAME_TO_TEST;
            $rulesContentForDev[$show][$hide] = \ob_get_clean();
            unset($_GET['mode'], $_GET['show'], $_GET['hide']);
            $this->removeOwnerShipConfirmation();
            self::assertNotSame($this->getOwnershipConfirmationContent(), $rulesContentForDev[$show]);
        }

        return $rulesContentForDev[$show][$hide];
    }

    protected function getRulesForDevHtmlDocument(string $show = '', string $hide = '')
    {
        static $rulesForDevHtmlDocument = [];
        if (empty($rulesForDevHtmlDocument[$show][$hide])) {
            $rulesForDevHtmlDocument[$show][$hide] = new HTMLDocument($this->getRulesContentForDev($show, $hide));
        }

        return $rulesForDevHtmlDocument[$show][$hide];
    }

    /**
     * @return string
     */
    protected function getRulesContentForDevWithHiddenCovered(): string
    {
        return $this->getRulesContentForDev('', 'covered');
    }

    protected function checkingSkeleton(HTMLDocument $document): bool
    {
        return \strpos($document->head->getElementsByTagName('title')->item(0)->nodeValue, 'skeleton') !== false;
    }

    protected function getDocumentRoot(): string
    {
        return \dirname(DRD_PLUS_RULES_INDEX_FILE_NAME_TO_TEST);
    }

    protected function getEshopFileName(): string
    {
        return $this->getDocumentRoot() . '/eshop_url.txt';
    }

}