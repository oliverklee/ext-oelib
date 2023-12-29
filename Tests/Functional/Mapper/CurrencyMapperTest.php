<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Mapper;

use OliverKlee\Oelib\Mapper\CurrencyMapper;
use OliverKlee\Oelib\Model\Currency;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \OliverKlee\Oelib\Mapper\CurrencyMapper
 * @covers \OliverKlee\Oelib\Model\Currency
 */
final class CurrencyMapperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/oelib', 'typo3conf/ext/static_info_tables'];

    private CurrencyMapper $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->importStaticData();

        $this->subject = new CurrencyMapper();
    }

    /**
     * Imports static records - but only if they aren't already available as static data.
     */
    private function importStaticData(): void
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('static_currencies');
        if ($connection->count('*', 'static_currencies', []) === 0) {
            $this->importCSVDataSet(__DIR__ . '/Fixtures/Currencies.csv');
        }
    }

    /////////////////////////////////////////
    // Tests regarding findByIsoAlpha3Code.
    /////////////////////////////////////////

    /**
     * @test
     */
    public function findByIsoAlpha3CodeWithIsoAlpha3CodeOfExistingRecordReturnsCurrencyInstance(): void
    {
        self::assertInstanceOf(
            Currency::class,
            $this->subject->findByIsoAlpha3Code('EUR')
        );
    }

    /**
     * @test
     */
    public function findByIsoAlpha3CodeWithIsoAlpha3CodeOfExistingRecordReturnsRecordAsModel(): void
    {
        self::assertSame(
            49,
            $this->subject->findByIsoAlpha3Code('EUR')->getUid()
        );
    }
}
