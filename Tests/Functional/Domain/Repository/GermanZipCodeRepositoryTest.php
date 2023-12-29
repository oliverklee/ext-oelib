<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Domain\Repository;

use OliverKlee\Oelib\Domain\Model\GermanZipCode;
use OliverKlee\Oelib\Domain\Repository\GermanZipCodeRepository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \OliverKlee\Oelib\Domain\Model\GermanZipCode
 * @covers \OliverKlee\Oelib\Domain\Repository\GermanZipCodeRepository
 * @covers \OliverKlee\Oelib\Domain\Repository\Traits\StoragePageAgnostic
 */
final class GermanZipCodeRepositoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    private GermanZipCodeRepository $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->get(GermanZipCodeRepository::class);

        $this->importCSVDataSet(__DIR__ . '/Fixtures/ZipCodes.csv');
    }

    /**
     * @test
     */
    public function mapsAllModelFields(): void
    {
        /** @var GermanZipCode $result */
        $result = $this->subject->findByUid(9000);

        self::assertInstanceOf(GermanZipCode::class, $result);
        self::assertSame('01067', $result->getZipCode());
        self::assertSame('Dresden', $result->getCityName());
        self::assertSame(13.721068, $result->getLongitude());
        self::assertSame(51.060036, $result->getLatitude());
    }

    /**
     * @test
     */
    public function findOneByZipCodeWithMatchReturnsMatch(): void
    {
        $zipCode = '01067';
        /** @var GermanZipCode $result */
        $result = $this->subject->findOneByZipCode($zipCode);

        self::assertInstanceOf(GermanZipCode::class, $result);
        self::assertSame($zipCode, $result->getZipCode());
        self::assertSame('Dresden', $result->getCityName());
    }

    /**
     * @test
     */
    public function findOneByZipCodeWithMatchCalledTwoTimesReturnsTheSameModel(): void
    {
        $zipCode = '01067';
        $firstResult = $this->subject->findOneByZipCode($zipCode);
        $secondResult = $this->subject->findOneByZipCode($zipCode);

        self::assertSame($firstResult, $secondResult);
    }

    /**
     * @return string[][]
     */
    public function nonMatchedZipCodesDataProvider(): array
    {
        return [
            '5 digits without match' => ['00000'],
            '5 letters' => ['av3sd'],
            '4 digits' => ['1233'],
            '6 digits' => ['463726'],
            'empty string' => [''],
        ];
    }

    /**
     * @test
     *
     * @dataProvider nonMatchedZipCodesDataProvider
     */
    public function findOneByZipCodeWithoutMatchReturnsNull(string $zipCode): void
    {
        $result = $this->subject->findOneByZipCode($zipCode);

        self::assertNull($result);
    }
}
