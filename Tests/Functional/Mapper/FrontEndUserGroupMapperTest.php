<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Mapper;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use OliverKlee\Oelib\Mapper\FrontEndUserGroupMapper;
use OliverKlee\Oelib\Model\FrontEndUserGroup;
use OliverKlee\Oelib\Testing\TestingFramework;

/**
 * Test case.
 *
 * @author Bernd Schönbach <bernd@oliverklee.de>
 */
class FrontEndUserGroupMapperTest extends FunctionalTestCase
{
    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    /**
     * @var TestingFramework for creating dummy records
     */
    private $testingFramework = null;

    /**
     * @var FrontEndUserGroupMapper the object to test
     */
    private $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->testingFramework = new TestingFramework('tx_oelib');

        $this->subject = new FrontEndUserGroupMapper();
    }

    protected function tearDown()
    {
        $this->testingFramework->cleanUp();
        parent::tearDown();
    }

    /////////////////////////////////////////
    // Tests concerning the basic functions
    /////////////////////////////////////////

    /**
     * @test
     */
    public function findWithUidOfExistingRecordReturnsFrontEndUserGroupInstance()
    {
        $uid = $this->testingFramework->createFrontEndUserGroup();

        self::assertInstanceOf(
            FrontEndUserGroup::class,
            $this->subject->find($uid)
        );
    }

    /**
     * @test
     */
    public function loadForExistingUserGroupCanLoadUserGroupData()
    {
        /** @var FrontEndUserGroup $userGroup */
        $userGroup = $this->subject->find(
            $this->testingFramework->createFrontEndUserGroup(['title' => 'foo'])
        );

        $this->subject->load($userGroup);

        self::assertSame(
            'foo',
            $userGroup->getTitle()
        );
    }
}
