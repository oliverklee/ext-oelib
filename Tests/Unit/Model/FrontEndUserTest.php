<?php
/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Test case.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_Tests_Unit_Model_FrontEndUserTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_Oelib_Model_FrontEndUser
     */
    protected $subject = null;

    /**
     * @var int a backup of $GLOBALS['EXEC_TIME']
     */
    protected $globalExecTimeBackup = 0;

    protected function setUp()
    {
        $this->subject = new Tx_Oelib_Model_FrontEndUser();

        $this->globalExecTimeBackup = $GLOBALS['EXEC_TIME'];
    }

    protected function tearDown()
    {
        $GLOBALS['EXEC_TIME'] = $this->globalExecTimeBackup;
    }

    /*
     * Tests concerning the user name
     */

    /**
     * @test
     */
    public function getUserNameForEmptyUserNameReturnsEmptyString()
    {
        $this->subject->setData(['username' => '']);

        self::assertSame(
            '',
            $this->subject->getUserName()
        );
    }

    /**
     * @test
     */
    public function getUserNameForNonEmptyUserNameReturnsUserName()
    {
        $this->subject->setData(['username' => 'johndoe']);

        self::assertSame(
            'johndoe',
            $this->subject->getUserName()
        );
    }

    /**
     * @test
     */
    public function setUserNameSetsUserName()
    {
        $this->subject->setUserName('foo_bar');

        self::assertSame(
            'foo_bar',
            $this->subject->getUserName()
        );
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     */
    public function setUserNameWithEmptyUserNameThrowsException()
    {
        $this->subject->setUserName('');
    }

    /*
     * Tests concerning the password
     */

    /**
     * @test
     */
    public function getPasswordInitiallyReturnsEmptyString()
    {
        $this->subject->setData([]);

        self::assertSame(
            '',
            $this->subject->getPassword()
        );
    }

    /**
     * @test
     */
    public function getPasswordReturnsPassword()
    {
        $this->subject->setData(['password' => 'kasfdjklsdajk']);

        self::assertSame(
            'kasfdjklsdajk',
            $this->subject->getPassword()
        );
    }

    /**
     * @test
     */
    public function setPasswordSetsPassword()
    {
        $this->subject->setPassword('kljvasgd24vsga354');

        self::assertSame(
            'kljvasgd24vsga354',
            $this->subject->getPassword()
        );
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     */
    public function setPasswordWithEmptyPasswordThrowsException()
    {
        $this->subject->setPassword('');
    }

    /*
     * Tests concerning the name
     */

    /**
     * @test
     */
    public function hasNameForEmptyNameLastNameAndFirstNameReturnsFalse()
    {
        $this->subject->setData([
            'name' => '',
            'first_name' => '',
            'last_name' => '',
        ]);

        self::assertFalse(
            $this->subject->hasName()
        );
    }

    /**
     * @test
     */
    public function hasNameForNonEmptyUserReturnsFalse()
    {
        $this->subject->setData([
            'username' => 'johndoe',
        ]);

        self::assertFalse(
            $this->subject->hasName()
        );
    }

    /**
     * @test
     */
    public function hasNameForNonEmptyNameReturnsTrue()
    {
        $this->subject->setData([
            'name' => 'John Doe',
            'first_name' => '',
            'last_name' => '',
        ]);

        self::assertTrue(
            $this->subject->hasName()
        );
    }

    /**
     * @test
     */
    public function hasNameForNonEmptyFirstNameReturnsTrue()
    {
        $this->subject->setData([
            'name' => '',
            'first_name' => 'John',
            'last_name' => '',
        ]);

        self::assertTrue(
            $this->subject->hasName()
        );
    }

    /**
     * @test
     */
    public function hasNameForNonEmptyLastNameReturnsTrue()
    {
        $this->subject->setData([
            'name' => '',
            'first_name' => '',
            'last_name' => 'Doe',
        ]);

        self::assertTrue(
            $this->subject->hasName()
        );
    }

    /**
     * @test
     */
    public function getNameForNonEmptyNameReturnsName()
    {
        $this->subject->setData([
            'name' => 'John Doe',
        ]);

        self::assertSame(
            'John Doe',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function getNameForNonEmptyNameFirstNameAndLastNameReturnsName()
    {
        $this->subject->setData([
            'name' => 'John Doe',
            'first_name' => 'Peter',
            'last_name' => 'Pan',
        ]);

        self::assertSame(
            'John Doe',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function getNameForEmptyNameAndNonEmptyFirstAndLastNameReturnsFirstAndLastName()
    {
        $this->subject->setData([
            'name' => '',
            'first_name' => 'Peter',
            'last_name' => 'Pan',
        ]);

        self::assertSame(
            'Peter Pan',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function getNameForNonEmptyFirstAndLastNameAndNonEmptyUserNameReturnsFirstAndLastName()
    {
        $this->subject->setData([
            'first_name' => 'Peter',
            'last_name' => 'Pan',
            'username' => 'johndoe',
        ]);

        self::assertSame(
            'Peter Pan',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function getNameForEmptyFirstNameAndNonEmptyLastAndUserNameReturnsLastName()
    {
        $this->subject->setData([
            'first_name' => '',
            'last_name' => 'Pan',
            'username' => 'johndoe',
        ]);

        self::assertSame(
            'Pan',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function getNameForEmptyLastNameAndNonEmptyFirstAndUserNameReturnsFirstName()
    {
        $this->subject->setData([
            'first_name' => 'Peter',
            'last_name' => '',
            'username' => 'johndoe',
        ]);

        self::assertSame(
            'Peter',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function getNameForEmptyFirstAndLastNameAndNonEmptyUserNameReturnsUserName()
    {
        $this->subject->setData([
            'first_name' => '',
            'last_name' => '',
            'username' => 'johndoe',
        ]);

        self::assertSame(
            'johndoe',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameSetsFullName()
    {
        $this->subject->setName('Alfred E. Neumann');

        self::assertSame(
            'Alfred E. Neumann',
            $this->subject->getName()
        );
    }

    /*
     * Tests concerning getting the company
     */

    /**
     * @test
     */
    public function hasCompanyForEmptyCompanyReturnsFalse()
    {
        $this->subject->setData(['company' => '']);

        self::assertFalse(
            $this->subject->hasCompany()
        );
    }

    /**
     * @test
     */
    public function hasCompanyForNonEmptyCompanyReturnsTrue()
    {
        $this->subject->setData(['company' => 'Test Inc.']);

        self::assertTrue(
            $this->subject->hasCompany()
        );
    }

    /**
     * @test
     */
    public function getCompanyForEmptyCompanyReturnsEmptyString()
    {
        $this->subject->setData(['company' => '']);

        self::assertSame(
            '',
            $this->subject->getCompany()
        );
    }

    /**
     * @test
     */
    public function getCompanyForNonEmptyCompanyReturnsCompany()
    {
        $this->subject->setData(['company' => 'Test Inc.']);

        self::assertSame(
            'Test Inc.',
            $this->subject->getCompany()
        );
    }

    /**
     * @test
     */
    public function setCompanySetsCompany()
    {
        $this->subject->setCompany('Test Inc.');

        self::assertSame(
            'Test Inc.',
            $this->subject->getCompany()
        );
    }

    /*
     * Tests concerning getting the street
     */

    /**
     * @test
     */
    public function hasStreetForEmptyAddressReturnsFalse()
    {
        $this->subject->setData(['address' => '']);

        self::assertFalse(
            $this->subject->hasStreet()
        );
    }

    /**
     * @test
     */
    public function hasStreetForNonEmptyAddressReturnsTrue()
    {
        $this->subject->setData(['address' => 'Foo street 1']);

        self::assertTrue(
            $this->subject->hasStreet()
        );
    }

    /**
     * @test
     */
    public function getStreetForEmptyAddressReturnsEmptyString()
    {
        $this->subject->setData(['address' => '']);

        self::assertSame(
            '',
            $this->subject->getStreet()
        );
    }

    /**
     * @test
     */
    public function getStreetForNonEmptyAddressReturnsAddress()
    {
        $this->subject->setData(['address' => 'Foo street 1']);

        self::assertSame(
            'Foo street 1',
            $this->subject->getStreet()
        );
    }

    /**
     * @test
     */
    public function getStreetForMultilineAddressReturnsAddress()
    {
        $this->subject->setData([
            'address' => 'Foo street 1' . LF . 'Floor 3',
        ]);

        self::assertSame(
            'Foo street 1' . LF . 'Floor 3',
            $this->subject->getStreet()
        );
    }

    /**
     * @test
     */
    public function setStreetSetsStreet()
    {
        $street = 'Barber Street 42';
        $this->subject->setData([]);
        $this->subject->setStreet($street);

        self::assertSame(
            $street,
            $this->subject->getStreet()
        );
    }

    /*
     * Tests concerning the ZIP code
     */

    /**
     * @test
     */
    public function hasZipForEmptyZipReturnsFalse()
    {
        $this->subject->setData(['zip' => '']);

        self::assertFalse(
            $this->subject->hasZip()
        );
    }

    /**
     * @test
     */
    public function hasZipForNonEmptyZipReturnsTrue()
    {
        $this->subject->setData(['zip' => '12345']);

        self::assertTrue(
            $this->subject->hasZip()
        );
    }

    /**
     * @test
     */
    public function getZipForEmptyZipReturnsEmptyString()
    {
        $this->subject->setData(['zip' => '']);

        self::assertSame(
            '',
            $this->subject->getZip()
        );
    }

    /**
     * @test
     */
    public function getZipForNonEmptyZipReturnsZip()
    {
        $this->subject->setData(['zip' => '12345']);

        self::assertSame(
            '12345',
            $this->subject->getZip()
        );
    }

    /**
     * @test
     */
    public function setZipSetsZip()
    {
        $zip = '12356';
        $this->subject->setData([]);
        $this->subject->setZip($zip);

        self::assertSame(
            $zip,
            $this->subject->getZip()
        );
    }

    /*
     * Tests concerning the city
     */

    /**
     * @test
     */
    public function hasCityForEmptyCityReturnsFalse()
    {
        $this->subject->setData(['city' => '']);

        self::assertFalse(
            $this->subject->hasCity()
        );
    }

    /**
     * @test
     */
    public function hasCityForNonEmptyCityReturnsTrue()
    {
        $this->subject->setData(['city' => 'Test city']);

        self::assertTrue(
            $this->subject->hasCity()
        );
    }

    /**
     * @test
     */
    public function getCityForEmptyCityReturnsEmptyString()
    {
        $this->subject->setData(['city' => '']);

        self::assertSame(
            '',
            $this->subject->getCity()
        );
    }

    /**
     * @test
     */
    public function getCityForNonEmptyCityReturnsCity()
    {
        $this->subject->setData(['city' => 'Test city']);

        self::assertSame(
            'Test city',
            $this->subject->getCity()
        );
    }

    /**
     * @test
     */
    public function setCitySetsCity()
    {
        $city = 'Köln';
        $this->subject->setData([]);
        $this->subject->setCity($city);

        self::assertSame(
            $city,
            $this->subject->getCity()
        );
    }

    /**
     * @test
     */
    public function getZipAndCityForNonEmptyZipAndCityReturnsZipAndCity()
    {
        $this->subject->setData([
            'zip' => '12345',
            'city' => 'Test city',
        ]);

        self::assertSame(
            '12345 Test city',
            $this->subject->getZipAndCity()
        );
    }

    /**
     * @test
     */
    public function getZipAndCityForEmptyZipAndNonEmptyCityReturnsCity()
    {
        $this->subject->setData([
            'zip' => '',
            'city' => 'Test city',
        ]);

        self::assertSame(
            'Test city',
            $this->subject->getZipAndCity()
        );
    }

    /**
     * @test
     */
    public function getZipAndGetCityForNonEmptyZipAndEmptyCityReturnsEmptyString()
    {
        $this->subject->setData([
            'zip' => '12345',
            'city' => '',
        ]);

        self::assertSame(
            '',
            $this->subject->getZipAndCity()
        );
    }

    /**
     * @test
     */
    public function getZipAndGetCityForEmptyZipAndEmptyCityReturnsEmptyString()
    {
        $this->subject->setData([
            'zip' => '',
            'city' => '',
        ]);

        self::assertSame(
            '',
            $this->subject->getZipAndCity()
        );
    }

    /*
     * Tests concerning the phone number
     */

    /**
     * @test
     */
    public function hasPhoneNumberForEmptyPhoneReturnsFalse()
    {
        $this->subject->setData(['telephone' => '']);

        self::assertFalse(
            $this->subject->hasPhoneNumber()
        );
    }

    /**
     * @test
     */
    public function hasPhoneNumberForNonEmptyPhoneReturnsTrue()
    {
        $this->subject->setData(['telephone' => '1234 5678']);

        self::assertTrue(
            $this->subject->hasPhoneNumber()
        );
    }

    /**
     * @test
     */
    public function getPhoneNumberForEmptyPhoneReturnsEmptyString()
    {
        $this->subject->setData(['telephone' => '']);

        self::assertSame(
            '',
            $this->subject->getPhoneNumber()
        );
    }

    /**
     * @test
     */
    public function getPhoneNumberForNonEmptyPhoneReturnsPhone()
    {
        $this->subject->setData(['telephone' => '1234 5678']);

        self::assertSame(
            '1234 5678',
            $this->subject->getPhoneNumber()
        );
    }

    /**
     * @test
     */
    public function setPhoneNumberSetsPhoneNumber()
    {
        $phoneNumber = '+49 124 1234123';
        $this->subject->setData([]);
        $this->subject->setPhoneNumber($phoneNumber);

        self::assertSame(
            $phoneNumber,
            $this->subject->getPhoneNumber()
        );
    }

    /*
     * Tests concerning the e-mail address
     */

    /**
     * @test
     */
    public function hasEmailAddressForEmptyEmailReturnsFalse()
    {
        $this->subject->setData(['email' => '']);

        self::assertFalse(
            $this->subject->hasEmailAddress()
        );
    }

    /**
     * @test
     */
    public function hasEmailAddressForNonEmptyEmailReturnsTrue()
    {
        $this->subject->setData(['email' => 'john@doe.com']);

        self::assertTrue(
            $this->subject->hasEmailAddress()
        );
    }

    /**
     * @test
     */
    public function getEmailAddressForEmptyEmailReturnsEmptyString()
    {
        $this->subject->setData(['email' => '']);

        self::assertSame(
            '',
            $this->subject->getEmailAddress()
        );
    }

    /**
     * @test
     */
    public function getEmailAddressForNonEmptyEmailReturnsEmail()
    {
        $this->subject->setData(['email' => 'john@doe.com']);

        self::assertSame(
            'john@doe.com',
            $this->subject->getEmailAddress()
        );
    }

    /**
     * @test
     */
    public function setEmailAddressSetsEmailAddress()
    {
        $this->subject->setEmailAddress('john@example.com');

        self::assertSame(
            'john@example.com',
            $this->subject->getEmailAddress()
        );
    }

    /*
     * Tests concerning getting the homepage
     */

    /**
     * @test
     */
    public function hasHomepageForEmptyWwwReturnsFalse()
    {
        $this->subject->setData(['www' => '']);

        self::assertFalse(
            $this->subject->hasHomepage()
        );
    }

    /**
     * @test
     */
    public function hasHomepageForNonEmptyWwwReturnsTrue()
    {
        $this->subject->setData(['www' => 'http://www.doe.com']);

        self::assertTrue(
            $this->subject->hasHomepage()
        );
    }

    /**
     * @test
     */
    public function getHomepageForEmptyWwwReturnsEmptyString()
    {
        $this->subject->setData(['www' => '']);

        self::assertSame(
            '',
            $this->subject->getHomepage()
        );
    }

    /**
     * @test
     */
    public function getHomepageForNonEmptyWwwReturnsWww()
    {
        $this->subject->setData(['www' => 'http://www.doe.com']);

        self::assertSame(
            'http://www.doe.com',
            $this->subject->getHomepage()
        );
    }

    /*
     * Tests concerning getting the picture
     */

    /**
     * @test
     */
    public function hasImageForEmptyImageReturnsFalse()
    {
        $this->subject->setData(['image' => '']);

        self::assertFalse(
            $this->subject->hasImage()
        );
    }

    /**
     * @test
     */
    public function hasImageForNonEmptyImageReturnsTrue()
    {
        $this->subject->setData(['image' => 'thats-me.jpg']);

        self::assertTrue(
            $this->subject->hasImage()
        );
    }

    /**
     * @test
     */
    public function getImageForEmptyImageReturnsEmptyString()
    {
        $this->subject->setData(['image' => '']);

        self::assertSame(
            '',
            $this->subject->getImage()
        );
    }

    /**
     * @test
     */
    public function getImageForNonEmptyImageReturnsImage()
    {
        $this->subject->setData(['image' => 'thats-me.jpg']);

        self::assertSame(
            'thats-me.jpg',
            $this->subject->getImage()
        );
    }

    /*
     * Tests concerning wantsHtmlEmail
     */

    /**
     * @test
     */
    public function wantsHtmlEmailForMissingModuleSysDmailHtmlFieldReturnsFalse()
    {
        $this->subject->setData([]);

        self::assertFalse(
            $this->subject->wantsHtmlEmail()
        );
    }

    /**
     * @test
     */
    public function wantsHtmlEmailForModuleSysDmailHtmlOneReturnsTrue()
    {
        $this->subject->setData(['module_sys_dmail_html' => 1]);

        self::assertTrue(
            $this->subject->wantsHtmlEmail()
        );
    }

    /**
     * @test
     */
    public function wantsHtmlEmailForModuleSysDmailHtmlZeroReturnsFalse()
    {
        $this->subject->setData(['module_sys_dmail_html' => 0]);

        self::assertFalse(
            $this->subject->wantsHtmlEmail()
        );
    }

    /*
     * Tests concerning the user groups
     */

    /**
     * @test
     */
    public function getUserGroupsForReturnsUserGroups()
    {
        $userGroups = new Tx_Oelib_List();

        $this->subject->setData(['usergroup' => $userGroups]);

        self::assertSame(
            $userGroups,
            $this->subject->getUserGroups()
        );
    }

    /**
     * @test
     */
    public function setUserGroupsSetsUserGroups()
    {
        $userGroups = new Tx_Oelib_List();

        $this->subject->setUserGroups($userGroups);

        self::assertSame(
            $userGroups,
            $this->subject->getUserGroups()
        );
    }

    /**
     * @test
     */
    public function addUserGroupAddsUserGroup()
    {
        $userGroups = new Tx_Oelib_List();
        $this->subject->setUserGroups($userGroups);

        $userGroup = new Tx_Oelib_Model_FrontEndUserGroup();
        $this->subject->addUserGroup($userGroup);

        self::assertTrue(
            $this->subject->getUserGroups()->contains($userGroup)
        );
    }

    /*
     * Test concerning hasGroupMembership
     */

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function hasGroupMembershipWithEmptyUidListThrowsException()
    {
        $this->subject->hasGroupMembership('');
    }

    /**
     * @test
     */
    public function hasGroupMembershipForUserOnlyInProvidedGroupReturnsTrue()
    {
        $userGroup = Tx_Oelib_MapperRegistry
            ::get(Tx_Oelib_Mapper_FrontEndUserGroup::class)->getNewGhost();
        $list = new Tx_Oelib_List();
        $list->add($userGroup);

        $this->subject->setData(['usergroup' => $list]);

        self::assertTrue(
            $this->subject->hasGroupMembership($userGroup->getUid())
        );
    }

    /**
     * @test
     */
    public function hasGroupMembershipForUserInProvidedGroupAndInAnotherReturnsTrue()
    {
        $groupMapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_FrontEndUserGroup::class);
        $userGroup = $groupMapper->getNewGhost();
        $list = new Tx_Oelib_List();
        $list->add($groupMapper->getNewGhost());
        $list->add($userGroup);

        $this->subject->setData(['usergroup' => $list]);

        self::assertTrue(
            $this->subject->hasGroupMembership($userGroup->getUid())
        );
    }

    /**
     * @test
     */
    public function hasGroupMembershipForUserInOneOfTheProvidedGroupsReturnsTrue()
    {
        $groupMapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_FrontEndUserGroup::class);
        $userGroup = $groupMapper->getNewGhost();
        $list = new Tx_Oelib_List();
        $list->add($userGroup);

        $this->subject->setData(['usergroup' => $list]);

        self::assertTrue(
            $this->subject->hasGroupMembership(
                $userGroup->getUid() . ',' . $groupMapper->getNewGhost()->getUid()
            )
        );
    }

    /**
     * @test
     */
    public function hasGroupMembershipForUserNoneOfTheProvidedGroupsReturnsFalse()
    {
        $groupMapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_FrontEndUserGroup::class);
        $list = new Tx_Oelib_List();
        $list->add($groupMapper->getNewGhost());
        $list->add($groupMapper->getNewGhost());

        $this->subject->setData(['usergroup' => $list]);

        self::assertFalse(
            $this->subject->hasGroupMembership(
                $groupMapper->getNewGhost()->getUid() . ',' . $groupMapper->getNewGhost()->getUid()
            )
        );
    }

    /*
     * Tests concerning the gender
     */

    /**
     * @test
     */
    public function getGenderForNotInstalledSrFeUserRegisterReturnsGenderUnknown()
    {
        if (Tx_Oelib_Model_FrontEndUser::hasGenderField()) {
            self::markTestSkipped('This test is only applicable if no FrontEndUser.gender field exists.');
        }

        self::assertSame(
            Tx_Oelib_Model_FrontEndUser::GENDER_UNKNOWN,
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function getGenderForGenderValueZeroReturnsGenderMale()
    {
        if (!Tx_Oelib_Model_FrontEndUser::hasGenderField()) {
            self::markTestSkipped('This test is only applicable if the FrontEndUser.gender field exists.');
        }
        $this->subject->setData(['gender' => 0]);

        self::assertSame(
            Tx_Oelib_Model_FrontEndUser::GENDER_MALE,
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function getGenderForGenderValueOneReturnsGenderFemale()
    {
        if (!Tx_Oelib_Model_FrontEndUser::hasGenderField()) {
            self::markTestSkipped('This test is only applicable if the FrontEndUser.gender field exists.');
        }
        $this->subject->setData(['gender' => 1]);

        self::assertSame(
            Tx_Oelib_Model_FrontEndUser::GENDER_FEMALE,
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function setGenderCanSetGenderToMale()
    {
        if (!Tx_Oelib_Model_FrontEndUser::hasGenderField()) {
            self::markTestSkipped('This test is only applicable if the FrontEndUser.gender field exists.');
        }
        $this->subject->setData([]);
        $this->subject->setGender(Tx_Oelib_Model_FrontEndUser::GENDER_MALE);

        self::assertSame(
            Tx_Oelib_Model_FrontEndUser::GENDER_MALE,
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function setGenderCanSetGenderToFemale()
    {
        if (!Tx_Oelib_Model_FrontEndUser::hasGenderField()) {
            self::markTestSkipped('This test is only applicable if the FrontEndUser.gender field exists.');
        }
        $this->subject->setData([]);
        $this->subject->setGender(Tx_Oelib_Model_FrontEndUser::GENDER_FEMALE);

        self::assertSame(
            Tx_Oelib_Model_FrontEndUser::GENDER_FEMALE,
            $this->subject->getGender()
        );
    }

    /**
     * @test
     */
    public function setGenderCanSetGenderToUnknown()
    {
        if (!Tx_Oelib_Model_FrontEndUser::hasGenderField()) {
            self::markTestSkipped('This test is only applicable if the FrontEndUser.gender field exists.');
        }
        $this->subject->setData([]);
        $this->subject->setGender(Tx_Oelib_Model_FrontEndUser::GENDER_UNKNOWN);

        self::assertSame(
            Tx_Oelib_Model_FrontEndUser::GENDER_UNKNOWN,
            $this->subject->getGender()
        );
    }

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     */
    public function setGenderForInvalidGenderKeyThrowsException()
    {
        if (!Tx_Oelib_Model_FrontEndUser::hasGenderField()) {
            self::markTestSkipped('This test is only applicable if the FrontEndUser.gender field exists.');
        }
        $this->subject->setData([]);
        $this->subject->setGender(4);
    }

    /*
     * Tests concerning the first name
     */

    /**
     * @test
     */
    public function hasFirstNameForNoFirstNameSetReturnsFalse()
    {
        $this->subject->setData([]);

        self::assertFalse(
            $this->subject->hasFirstName()
        );
    }

    /**
     * @test
     */
    public function hasFirstNameForFirstNameSetReturnsTrue()
    {
        $this->subject->setData(['first_name' => 'foo']);

        self::assertTrue(
            $this->subject->hasFirstName()
        );
    }

    /**
     * @test
     */
    public function getFirstNameForNoFirstNameSetReturnsEmptyString()
    {
        $this->subject->setData([]);

        self::assertSame(
            '',
            $this->subject->getFirstName()
        );
    }

    /**
     * @test
     */
    public function getFirstNameForFirstNameSetReturnsFirstName()
    {
        $this->subject->setData(['first_name' => 'foo']);

        self::assertSame(
            'foo',
            $this->subject->getFirstName()
        );
    }

    /**
     * @test
     */
    public function setFirstNameSetsFirstName()
    {
        $this->subject->setFirstName('John');

        self::assertSame(
            'John',
            $this->subject->getFirstName()
        );
    }

    /**
     * @test
     */
    public function getFirstOrFullNameForUserWithFirstNameReturnsFirstName()
    {
        $this->subject->setData(
            ['first_name' => 'foo', 'name' => 'foo bar']
        );

        self::assertSame(
            'foo',
            $this->subject->getFirstOrFullName()
        );
    }

    /**
     * @test
     */
    public function getFirstOrFullNameForUserWithoutFirstNameReturnsName()
    {
        $this->subject->setData(['name' => 'foo bar']);

        self::assertSame(
            'foo bar',
            $this->subject->getFirstOrFullName()
        );
    }

    /*
     * Tests concerning the last name
     */

    /**
     * @test
     */
    public function hasLastNameForNoLastNameSetReturnsFalse()
    {
        $this->subject->setData([]);

        self::assertFalse(
            $this->subject->hasLastName()
        );
    }

    /**
     * @test
     */
    public function hasLastNameForLastNameSetReturnsTrue()
    {
        $this->subject->setData(['last_name' => 'bar']);

        self::assertTrue(
            $this->subject->hasLastName()
        );
    }

    /**
     * @test
     */
    public function getLastNameForNoLastNameSetReturnsEmptyString()
    {
        $this->subject->setData([]);

        self::assertSame(
            '',
            $this->subject->getLastName()
        );
    }

    /**
     * @test
     */
    public function getLastNameForLastNameSetReturnsLastName()
    {
        $this->subject->setData(['last_name' => 'bar']);

        self::assertSame(
            'bar',
            $this->subject->getLastName()
        );
    }

    /**
     * @test
     */
    public function setLastNameSetsLastName()
    {
        $this->subject->setLastName('Jacuzzi');

        self::assertSame(
            'Jacuzzi',
            $this->subject->getLastName()
        );
    }

    /**
     * @test
     */
    public function getLastOrFullNameForUserWithLastNameReturnsLastName()
    {
        $this->subject->setData(
            ['last_name' => 'bar', 'name' => 'foo bar']
        );

        self::assertSame(
            'bar',
            $this->subject->getLastOrFullName()
        );
    }

    /**
     * @test
     */
    public function getLastOrFullNameForUserWithoutLastNameReturnsName()
    {
        $this->subject->setData(['name' => 'foo bar']);

        self::assertSame(
            'foo bar',
            $this->subject->getLastOrFullName()
        );
    }

    /*
     * Tests concerning the date of birth
     */

    /**
     * @test
     */
    public function getDateOfBirthReturnsZeroForNoDateSet()
    {
        $this->subject->setData([]);

        self::assertSame(
            0,
            $this->subject->getDateOfBirth()
        );
    }

    /**
     * @test
     */
    public function getDateOfBirthReturnsDateFromDateOfBirthField()
    {
        // 1980-04-01
        $date = 323391600;
        $this->subject->setData(['date_of_birth' => $date]);

        self::assertSame(
            $date,
            $this->subject->getDateOfBirth()
        );
    }

    /**
     * @test
     */
    public function hasDateOfBirthForNoDateOfBirthReturnsFalse()
    {
        $this->subject->setData([]);

        self::assertFalse(
            $this->subject->hasDateOfBirth()
        );
    }

    /**
     * @test
     */
    public function hasDateOfBirthForNonZeroDateOfBirthReturnsTrue()
    {
        // 1980-04-01
        $date = 323391600;
        $this->subject->setData(['date_of_birth' => $date]);

        self::assertTrue(
            $this->subject->hasDateOfBirth()
        );
    }

    /*
     * Tests concerning getAge
     */

    /**
     * @test
     */
    public function getAgeForNoDateOfBirthReturnsZero()
    {
        $this->subject->setData([]);

        self::assertSame(
            0,
            $this->subject->getAge()
        );
    }

    /**
     * @test
     */
    public function getAgeForBornOneHourAgoReturnsZero()
    {
        $now = mktime(18, 0, 0, 9, 15, 2010);
        $GLOBALS['EXEC_TIME'] = $now;

        $this->subject->setData(
            ['date_of_birth' => $now - 60 * 60]
        );

        self::assertSame(
            0,
            $this->subject->getAge()
        );
    }

    /**
     * @test
     */
    public function getAgeForAnAgeOfTenYearsAndSomeMonthsReturnsTen()
    {
        $GLOBALS['EXEC_TIME'] = mktime(18, 0, 0, 9, 15, 2010);

        $this->subject->setData(
            ['date_of_birth' => mktime(18, 0, 0, 1, 15, 2000)]
        );

        self::assertSame(
            10,
            $this->subject->getAge()
        );
    }

    /**
     * @test
     */
    public function getAgeForAnAgeOfTenYearsMinusSomeMonthsReturnsNine()
    {
        $GLOBALS['EXEC_TIME'] = mktime(18, 0, 0, 9, 15, 2010);

        $this->subject->setData(
            ['date_of_birth' => mktime(18, 0, 0, 11, 15, 2000)]
        );

        self::assertSame(
            9,
            $this->subject->getAge()
        );
    }

    /**
     * @test
     */
    public function getAgeForAnAgeOfTenYearsMinusSomeDaysReturnsNine()
    {
        $GLOBALS['EXEC_TIME'] = mktime(18, 0, 0, 9, 15, 2010);

        $this->subject->setData(
            ['date_of_birth' => mktime(18, 0, 0, 9, 21, 2000)]
        );

        self::assertSame(
            9,
            $this->subject->getAge()
        );
    }

    /*
     * Tests concerning the date of the last login
     */

    /**
     * @test
     */
    public function getLastLoginAsUnixTimestampReturnsZeroForNoDateSet()
    {
        $this->subject->setData([]);

        self::assertSame(
            0,
            $this->subject->getLastLoginAsUnixTimestamp()
        );
    }

    /**
     * @test
     */
    public function getLastLoginAsUnixTimestampReturnsDateFromLastLoginField()
    {
        // 1980-04-01
        $date = 323391600;
        $this->subject->setData(['lastlogin' => $date]);

        self::assertSame(
            $date,
            $this->subject->getLastLoginAsUnixTimestamp()
        );
    }

    /**
     * @test
     */
    public function hasLastLoginForNoLastLoginReturnsFalse()
    {
        $this->subject->setData([]);

        self::assertFalse(
            $this->subject->hasLastLogin()
        );
    }

    /**
     * @test
     */
    public function hasLastLoginForNonZeroLastLoginReturnsTrue()
    {
        // 1980-04-01
        $date = 323391600;
        $this->subject->setData(['lastlogin' => $date]);

        self::assertTrue(
            $this->subject->hasLastLogin()
        );
    }

    /*
     * Tests regarding the country
     */

    /**
     * @test
     */
    public function getCountryWithoutCountryReturnsNull()
    {
        if (!ExtensionManagementUtility::isLoaded('static_info_tables')) {
            self::markTestSkipped('This tests needs the static_info_tables extension.');
        }

        $this->subject->setData([]);

        self::assertNull(
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     */
    public function getCountryWithInvalidCountryCodeReturnsNull()
    {
        if (!ExtensionManagementUtility::isLoaded('static_info_tables')) {
            self::markTestSkipped('This tests needs the static_info_tables extension.');
        }

        $this->subject->setData(['static_info_country' => 'xyz']);

        self::assertNull(
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     */
    public function getCountryWithCountryReturnsCountryAsModel()
    {
        if (!ExtensionManagementUtility::isLoaded('static_info_tables')) {
            self::markTestSkipped('This tests needs the static_info_tables extension.');
        }

        /** @var Tx_Oelib_Mapper_Country $mapper */
        $mapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_Country::class);
        /** @var Tx_Oelib_Model_Country $country */
        $country = $mapper->find(54);
        $this->subject->setData(
            ['static_info_country' => $country->getIsoAlpha3Code()]
        );

        self::assertSame(
            $country,
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     */
    public function setCountrySetsCountry()
    {
        if (!ExtensionManagementUtility::isLoaded('static_info_tables')) {
            self::markTestSkipped('This tests needs the static_info_tables extension.');
        }

        /** @var Tx_Oelib_Mapper_Country $mapper */
        $mapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_Country::class);
        /** @var Tx_Oelib_Model_Country $country */
        $country = $mapper->find(54);
        $this->subject->setCountry($country);

        self::assertSame(
            $country,
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     */
    public function countryCanBeSetToNull()
    {
        if (!ExtensionManagementUtility::isLoaded('static_info_tables')) {
            self::markTestSkipped('This tests needs the static_info_tables extension.');
        }

        $this->subject->setCountry(null);

        self::assertNull(
            $this->subject->getCountry()
        );
    }

    /**
     * @test
     */
    public function hasCountryWithoutCountryReturnsFalse()
    {
        if (!ExtensionManagementUtility::isLoaded('static_info_tables')) {
            self::markTestSkipped('This tests needs the static_info_tables extension.');
        }

        $this->subject->setData([]);

        self::assertFalse(
            $this->subject->hasCountry()
        );
    }

    /**
     * @test
     */
    public function hasCountryWithInvalidCountryReturnsFalse()
    {
        if (!ExtensionManagementUtility::isLoaded('static_info_tables')) {
            self::markTestSkipped('This tests needs the static_info_tables extension.');
        }

        $this->subject->setData(['static_info_country' => 'xyz']);

        self::assertFalse(
            $this->subject->hasCountry()
        );
    }

    /**
     * @test
     */
    public function hasCountryWithCountryReturnsTrue()
    {
        if (!ExtensionManagementUtility::isLoaded('static_info_tables')) {
            self::markTestSkipped('This tests needs the static_info_tables extension.');
        }

        /** @var Tx_Oelib_Mapper_Country $mapper */
        $mapper = Tx_Oelib_MapperRegistry::get(Tx_Oelib_Mapper_Country::class);
        /** @var Tx_Oelib_Model_Country $country */
        $country = $mapper->find(54);
        $this->subject->setCountry($country);

        self::assertTrue(
            $this->subject->hasCountry()
        );
    }

    /*
     * Tests concerning the job title
     */

    /**
     * @test
     */
    public function hasJobTitleForEmptyJobTitleReturnsFalse()
    {
        $this->subject->setData(['title' => '']);

        self::assertFalse(
            $this->subject->hasJobTitle()
        );
    }

    /**
     * @test
     */
    public function hasJobTitleForNonEmptyJobTitleReturnsTrue()
    {
        $this->subject->setData(['title' => 'facility manager']);

        self::assertTrue(
            $this->subject->hasJobTitle()
        );
    }

    /**
     * @test
     */
    public function getJobTitleForEmptyJobTitleReturnsEmptyString()
    {
        $this->subject->setData(['title' => '']);

        self::assertSame(
            '',
            $this->subject->getJobTitle()
        );
    }

    /**
     * @test
     */
    public function getJobTitleForNonEmptyJobTitleReturnsJobTitle()
    {
        $this->subject->setData(['title' => 'facility manager']);

        self::assertSame(
            'facility manager',
            $this->subject->getJobTitle()
        );
    }

    /**
     * @test
     */
    public function setJobTitleSetsJobTitle()
    {
        $this->subject->setJobTitle('foo bar');

        self::assertSame(
            'foo bar',
            $this->subject->getJobTitle()
        );
    }
}
