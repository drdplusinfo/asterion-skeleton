<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\RulesSkeleton;

/**
 * @method string|TestsConfiguration getSutClass
 */
class TestsConfigurationTest extends \DrdPlus\Tests\FrontendSkeleton\TestsConfigurationTest
{
    /**
     * @param string $localUrl
     * @param string $publicUrl
     * @return \DrdPlus\Tests\FrontendSkeleton\TestsConfiguration|TestsConfiguration
     */
    protected function createSut(string $localUrl = 'http://drdplus.loc', string $publicUrl = 'https://example.com'): \DrdPlus\Tests\FrontendSkeleton\TestsConfiguration
    {
        $sutClass = $this->getSutClass();

        return new $sutClass($localUrl, $publicUrl);
    }

    protected function getNonExistingSettersToSkip(): array
    {
        return \array_merge(parent::getNonExistingSettersToSkip(), ['setPublicUrl']); // this has to set via constructor
    }

    /**
     * @test
     */
    public function I_can_set_and_get_local_and_public_url(): void
    {
        $testsConfiguration = $this->createSut('http://drdplus.loc', 'https://public.com');
        self::assertSame('http://drdplus.loc', $testsConfiguration->getLocalUrl());
        self::assertSame('https://public.com', $testsConfiguration->getPublicUrl());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tests\RulesSkeleton\Exceptions\InvalidLocalUrl
     * @expectedExceptionMessageRegExp ~not valid~
     */
    public function I_can_not_create_it_with_invalid_local_url(): void
    {
        $this->createSut('drdplus.loc'); // missing protocol
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tests\RulesSkeleton\Exceptions\InvalidPublicUrl
     * @expectedExceptionMessageRegExp ~not valid~
     */
    public function I_can_not_create_it_with_invalid_public_url(): void
    {
        $this->createSut('http://drdplus.loc', 'example.com'); // missing protocol
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tests\RulesSkeleton\Exceptions\PublicUrlShouldUseHttps
     * @expectedExceptionMessageRegExp ~HTTPS~
     */
    public function I_can_not_create_it_with_public_url_without_https(): void
    {
        $this->createSut('http://drdplus.loc', 'http://example.com');
    }

    /**
     * @test
     */
    public function I_will_get_expected_licence_by_access_by_default(): void
    {
        $testsConfiguration = $this->createSut();
        self::assertTrue($testsConfiguration->hasProtectedAccess());
        self::assertSame('proprietary', $testsConfiguration->getExpectedLicence(), 'Expected proprietary licence for protected access');
        $testsConfiguration->disableHasProtectedAccess();
        self::assertFalse($testsConfiguration->hasProtectedAccess());
        self::assertSame('MIT', $testsConfiguration->getExpectedLicence(), 'Expected MIT licence for free access');
        $testsConfiguration->setExpectedLicence('foo');
        self::assertSame('foo', $testsConfiguration->getExpectedLicence());
    }

    /**
     * @test
     */
    public function I_can_add_too_short_failure_name(): void
    {
        $testsConfiguration = $this->createSut();
        self::assertCount(0, $testsConfiguration->getTooShortFailureNames());
        $testsConfiguration->addTooShortFailureName('foo');
        self::assertSame(['foo'], $testsConfiguration->getTooShortFailureNames());
        $testsConfiguration->addTooShortFailureName('bar');
        self::assertSame(['foo', 'bar'], $testsConfiguration->getTooShortFailureNames());
        $testsConfiguration->setTooShortFailureNames(['baz', 'qux']);
        self::assertSame(['baz', 'qux'], $testsConfiguration->getTooShortFailureNames());
    }

    /**
     * @test
     */
    public function I_can_add_too_short_success_name(): void
    {
        $testsConfiguration = $this->createSut();
        self::assertCount(0, $testsConfiguration->getTooShortSuccessNames());
        $testsConfiguration->addTooShortSuccessName('foo');
        self::assertSame(['foo'], $testsConfiguration->getTooShortSuccessNames());
        $testsConfiguration->addTooShortSuccessName('bar');
        self::assertSame(['foo', 'bar'], $testsConfiguration->getTooShortSuccessNames());
        $testsConfiguration->setTooShortSuccessNames(['baz', 'qux']);
        self::assertSame(['baz', 'qux'], $testsConfiguration->getTooShortSuccessNames());
    }
}