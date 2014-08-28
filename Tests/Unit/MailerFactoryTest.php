<?php
/**
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

/**
 * Test case.
 *
 * @package TYPO3
 * @subpackage tx_oelib
 *
 * @author Saskia Metzler <saskia@merlin.owl.de>
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_MailerFactoryTest extends Tx_Phpunit_TestCase {
	/**
	 * @var Tx_Oelib_MailerFactory
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = Tx_Oelib_MailerFactory::getInstance();
	}

	protected function tearDown() {
		unset($this->subject);
		t3lib_div::purgeInstances();
	}


	/*
	 * Tests concerning the basic functionality
	 */

	/**
	 * @test
	 */
	public function factoryIsSingleton() {
		$this->assertInstanceOf(
			't3lib_Singleton',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function callingGetInstanceTwoTimesReturnsTheSameInstance() {
		$this->assertSame(
			$this->subject,
			Tx_Oelib_MailerFactory::getInstance()
		);
	}

	/**
	 * @test
	 */
	public function getMailerInTestModeReturnsEmailCollector() {
		$this->subject->enableTestMode();
		$this->assertSame(
			'Tx_Oelib_EmailCollector',
			get_class($this->subject->getMailer())
		);
	}

	/**
	 * @test
	 */
	public function getMailerInNonTestModeReturnsRailMailer() {
		// initially, the test mode is disabled
		t3lib_div::purgeInstances();

		$this->assertSame(
			'Tx_Oelib_RealMailer',
			get_class($this->subject->getMailer())
		);
	}

	/**
	 * @test
	 */
	public function getMailerReturnsTheSameObjectWhenTheInstanceWasNotDiscarded() {
		$this->assertSame(
			$this->subject->getMailer(),
			$this->subject->getMailer()
		);
	}
}