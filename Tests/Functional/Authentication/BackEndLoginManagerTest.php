<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Functional\Authentication;

use Nimut\TestingFramework\Exception\Exception as NimutException;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use OliverKlee\Oelib\Tests\Unit\Mapper\Fixtures\TestingMapper;
use OliverKlee\Oelib\Tests\Unit\Model\Fixtures\TestingModel;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * Test case.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class BackEndLoginManagerTest extends FunctionalTestCase
{
    /**
     * @var string[]
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/oelib'];

    /**
     * @var \Tx_Oelib_BackEndLoginManager
     */
    private $subject = null;

    /**
     * @var \Tx_Oelib_Mapper_BackEndUser
     */
    private $backEndUserMapper = null;

    protected function setUp()
    {
        parent::setUp();

        $this->backEndUserMapper = \Tx_Oelib_MapperRegistry::get(\Tx_Oelib_Mapper_BackEndUser::class);

        $this->subject = \Tx_Oelib_BackEndLoginManager::getInstance();
    }

    /**
     * @return void
     *
     * @throws NimutException
     */
    private function logInBackEndUser()
    {
        $this->setUpBackendUserFromFixture(1);
    }

    /**
     * Returns $GLOBALS['BE_USER'].
     *
     * @return BackendUserAuthentication
     */
    private function getBackEndUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    /*
     * Tests concerning isLoggedIn
     */

    /**
     * @test
     */
    public function isLoggedInWithoutLoggedInBackEndUserReturnsFalse()
    {
        self::assertFalse($this->subject->isLoggedIn());
    }

    /**
     * @test
     */
    public function isLoggedInWithLoggedInBackEndUserReturnsTrue()
    {
        $this->logInBackEndUser();

        self::assertTrue($this->subject->isLoggedIn());
    }

    /**
     * @test
     */
    public function isLoggedInForFakedUserReturnsTrue()
    {
        /** @var \Tx_Oelib_Model_BackEndUser $ghostUser */
        $ghostUser = $this->backEndUserMapper->getNewGhost();
        $this->subject->setLoggedInUser($ghostUser);

        self::assertTrue($this->subject->isLoggedIn());
    }

    /*
     * Tests concerning getLoggedInUser
     */

    /**
     * @test
     */
    public function getLoggedInUserWithoutLoggedInUserReturnsNull()
    {
        self::assertNull($this->subject->getLoggedInUser());
    }

    /**
     * @test
     */
    public function getLoggedInUserWithLoggedInUserReturnsBackEndUserInstance()
    {
        $this->logInBackEndUser();

        self::assertInstanceOf(\Tx_Oelib_Model_BackEndUser::class, $this->subject->getLoggedInUser());
    }

    /**
     * @test
     */
    public function getLoggedInUserWithOtherMapperNameAndLoggedInUserReturnsCorrespondingModel()
    {
        $this->logInBackEndUser();

        $result = $this->subject->getLoggedInUser(TestingMapper::class);

        self::assertInstanceOf(TestingModel::class, $result);
    }

    /**
     * @test
     */
    public function getLoggedInUserWithLoggedInUserReturnsBackEndUserWithUidOfLoggedInUser()
    {
        $this->logInBackEndUser();

        $result = $this->subject->getLoggedInUser();

        self::assertSame((int)$this->getBackEndUserAuthentication()->user['uid'], $result->getUid());
    }

    /**
     * @test
     */
    public function getLoggedInUserWithAlreadyCreatedUserModelReturnsThatInstance()
    {
        $this->logInBackEndUser();

        /** @var \Tx_Oelib_Model_BackEndUser $user */
        $user = $this->backEndUserMapper->find($this->getBackEndUserAuthentication()->user['uid']);

        self::assertSame($user, $this->subject->getLoggedInUser());
    }

    /**
     * @test
     */
    public function getLoggedInUserUsesMappedUserDataFromMemory()
    {
        $this->logInBackEndUser();

        $name = 'John Doe';
        $this->getBackEndUserAuthentication()->user['realName'] = $name;

        self::assertSame($name, $this->subject->getLoggedInUser()->getName());
    }

    /*
     * Tests concerning setLoggedInUser
     */

    /**
     * @test
     */
    public function setLoggedInUserForUserGivenSetsTheLoggedInUser()
    {
        /** @var \Tx_Oelib_Model_BackEndUser $backEndUser */
        $backEndUser = $this->backEndUserMapper->getNewGhost();
        $this->subject->setLoggedInUser($backEndUser);

        self::assertSame($backEndUser, $this->subject->getLoggedInUser());
    }

    /**
     * @test
     */
    public function setLoggedInUserForUserGivenAndAlreadyStoredLoggedInUserOverridesTheOldUserWithTheNewOne()
    {
        /** @var \Tx_Oelib_Model_BackEndUser $oldBackEndUser */
        $oldBackEndUser = $this->backEndUserMapper->getNewGhost();
        $this->subject->setLoggedInUser($oldBackEndUser);

        /** @var \Tx_Oelib_Model_BackEndUser $newBackEndUser */
        $newBackEndUser = $this->backEndUserMapper->getNewGhost();
        $this->subject->setLoggedInUser($newBackEndUser);

        self::assertSame($newBackEndUser, $this->subject->getLoggedInUser());
    }
}
