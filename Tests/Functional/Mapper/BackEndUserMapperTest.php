<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Mapper;

use OliverKlee\Oelib\Mapper\BackEndUserMapper;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * @covers \OliverKlee\Oelib\Mapper\BackEndUserMapper
 * @covers \OliverKlee\Oelib\Model\BackEndUser
 */
final class BackEndUserMapperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    /**
     * @var BackEndUserMapper the object to test
     */
    private BackEndUserMapper $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new BackEndUserMapper();
    }

    /**
     * @test
     */
    public function loadForExistingRecordLoadsScalarData(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/BackEndUsers.csv');
        $model = $this->subject->find(1);

        $this->subject->load($model);

        self::assertSame('max', $model->getUserName());
    }
}
