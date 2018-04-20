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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class takes care of cleaning up oelib after the testing framework.
 *
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_TestingFrameworkCleanup
{
    /**
     * Cleans up oelib after running a test.
     *
     * @return void
     */
    public function cleanUp()
    {
        Tx_Oelib_ConfigurationProxy::purgeInstances();
        Tx_Oelib_BackEndLoginManager::purgeInstance();
        Tx_Oelib_ConfigurationRegistry::purgeInstance();
        Tx_Oelib_FrontEndLoginManager::purgeInstance();
        Tx_Oelib_Geocoding_Google::purgeInstance();
        Tx_Oelib_HeaderProxyFactory::purgeInstance();
        Tx_Oelib_MapperRegistry::purgeInstance();
        Tx_Oelib_PageFinder::purgeInstance();
        Tx_Oelib_Session::purgeInstances();
        Tx_Oelib_TemplateHelper::purgeCachedConfigurations();
        Tx_Oelib_TranslatorRegistry::purgeInstance();

        /** @var Tx_Oelib_MailerFactory $mailerFactory */
        $mailerFactory = GeneralUtility::makeInstance(\Tx_Oelib_MailerFactory::class);
        $mailerFactory->cleanUp();
    }
}
