<?php
namespace Tests\DrdPlus\RulesSkeleton;

use Gt\Dom\Element;
use Gt\Dom\HTMLDocument;

class DevModeTest extends AbstractContentTest
{

    /**
     * @test
     */
    public function I_see_content_marked_by_development_classes()
    {
        $content = $this->getRulesContentForDev();
        $html = new HTMLDocument($content);
        if (!$this->checkingSkeleton($html)) {
            self::assertFalse(false, 'Intended for skeleton only');
        }
        self::assertGreaterThan(0, $html->getElementsByClassName('covered-by-code')->count());
        self::assertGreaterThan(0, $html->getElementsByClassName('generic')->count());
        self::assertGreaterThan(0, $html->getElementsByClassName('excluded')->count());
    }

    /**
     * @test
     */
    public function I_can_get_introduction_only()
    {
        $content = $this->getRulesContentForDev('introduction');
        $html = new HTMLDocument($content);
        self::assertGreaterThan(0, $html->children->count());
        $bodies = $html->getElementsByTagName('body');
        self::assertGreaterThan(0, $bodies->length);
        /** @var Element $body */
        foreach ($bodies as $body) {
            self::assertGreaterThan(0, $body->children->length, 'No introduction found');
            foreach ($body->children as $child) {
                self::assertTrue(
                    $child->classList->contains('introduction')
                    || $child->classList->contains('background-image')
                    || $child->classList->contains('quote')
                    || $child->nodeName === 'img',
                    'This does not have "introduction" class: ' . $child->innerHTML
                );
            }
        }
        self::assertSame(0, $html->getElementsByClassName('covered-by-code')->count());
        self::assertSame(0, $html->getElementsByClassName('generic')->count(), 'Class "generic" would be already hidden.');
        self::assertGreaterThan(0, $html->getElementsByTagName('img')->length, 'Expected some image in introduction-only mode');
        self::assertGreaterThan(
            0,
            $html->getElementsByClassName('background-image')->count(),
            'Background image should not be removed in "introduction" mode'
        );
    }
}