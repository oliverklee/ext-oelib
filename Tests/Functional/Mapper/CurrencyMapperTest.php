<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Mapper;

use Nimut\TestingFramework\Exception\Exception as NimutException;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use OliverKlee\Oelib\Mapper\CurrencyMapper;
use OliverKlee\Oelib\Model\Currency;

class CurrencyMapperTest extends FunctionalTestCase
{
    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/oelib', 'typo3conf/ext/static_info_tables'];

    /**
     * @var CurrencyMapper
     */
    private $subject = null;

    protected function setUp()
    {
        parent::setUp();

        $this->importStaticData();

        $this->subject = new CurrencyMapper();
    }

    /**
     * Imports static records - but only if they aren't already available as static data.
     *
     * @return void
     *
     * @throws NimutException
     */
    private function importStaticData()
    {
        if ($this->getDatabaseConnection()->selectCount('*', 'static_currencies') === 0) {
            $this->importDataSet(__DIR__ . '/../Fixtures/Currencies.xml');
        }
    }

    ///////////////////////////
    // Tests concerning find.
    ///////////////////////////

    /**
     * @test
     */
    public function findWithUidOfExistingRecordReturnsCurrencyInstance()
    {
        self::assertInstanceOf(
            Currency::class,
            $this->subject->find(49)
        );
    }

    /////////////////////////////////////////
    // Tests regarding findByIsoAlpha3Code.
    /////////////////////////////////////////

    /**
     * @test
     */
    public function findByIsoAlpha3CodeWithIsoAlpha3CodeOfExistingRecordReturnsCurrencyInstance()
    {
        self::assertInstanceOf(
            Currency::class,
            $this->subject->findByIsoAlpha3Code('EUR')
        );
    }

    /**
     * @test
     */
    public function findByIsoAlpha3CodeWithIsoAlpha3CodeOfExistingRecordReturnsRecordAsModel()
    {
        self::assertSame(
            49,
            $this->subject->findByIsoAlpha3Code('EUR')->getUid()
        );
    }
}
