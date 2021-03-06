<?php

declare(strict_types=1);

namespace {
    die('Access denied');
}

// Authentication
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_BackEndLoginManager extends \OliverKlee\Oelib\Authentication\BackEndLoginManager
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_FrontEndLoginManager extends \OliverKlee\Oelib\Authentication\FrontEndLoginManager
    {

    }
}

// Configuration
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_ConfigCheck extends \OliverKlee\Oelib\Configuration\ConfigurationCheck
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Configuration extends \OliverKlee\Oelib\Configuration\TypoScriptConfiguration
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_ConfigurationProxy extends \OliverKlee\Oelib\Configuration\ConfigurationProxy
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_ConfigurationRegistry extends \OliverKlee\Oelib\Configuration\ConfigurationRegistry
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_PageFinder extends \OliverKlee\Oelib\Configuration\PageFinder
    {

    }
}

// Database
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Db extends \OliverKlee\Oelib\Database\DatabaseService
    {

    }
}

// DataStructures
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    abstract class Tx_Oelib_Object extends \OliverKlee\Oelib\DataStructures\AbstractObjectWithAccessors
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    abstract class Tx_Oelib_PublicObject extends \OliverKlee\Oelib\DataStructures\AbstractObjectWithPublicAccessors
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_List extends \OliverKlee\Oelib\DataStructures\Collection
    {

    }
}

// Mail
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    abstract class Tx_Oelib_AbstractMailer extends \OliverKlee\Oelib\Email\AbstractMailer
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Attachment extends \OliverKlee\Oelib\Email\Attachment
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_EmailCollector extends \OliverKlee\Oelib\Email\EmailCollector
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Mail extends \OliverKlee\Oelib\Email\Mail
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_MailerFactory extends \OliverKlee\Oelib\Email\MailerFactory
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_RealMailer extends \OliverKlee\Oelib\Email\RealMailer
    {

    }
}

// Exception
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Exception_AccessDenied extends \OliverKlee\Oelib\Exception\AccessDeniedException
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Exception_Database extends \OliverKlee\Oelib\Exception\DatabaseException
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Exception_EmptyQueryResult extends \OliverKlee\Oelib\Exception\EmptyQueryResultException
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Exception_NotFound extends \OliverKlee\Oelib\Exception\NotFoundException
    {

    }
}

// Frontend
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_FrontEnd_UserWithoutCookies extends \OliverKlee\Oelib\FrontEnd\UserWithoutCookies
    {

    }
}

// Geocoding
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Geocoding_Calculator extends \OliverKlee\Oelib\Geocoding\GeoCalculator
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Geocoding_Dummy extends \OliverKlee\Oelib\Geocoding\DummyGeocodingLookup
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Geocoding_Google extends \OliverKlee\Oelib\Geocoding\GoogleGeocoding
    {

    }
}

// Http
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_AbstractHeaderProxy extends \OliverKlee\Oelib\Http\Interfaces\HeaderProxy
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_HeaderCollector extends \OliverKlee\Oelib\Http\HeaderCollector
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_HeaderProxyFactory extends \OliverKlee\Oelib\Http\HeaderProxyFactory
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_RealHeaderProxy extends \OliverKlee\Oelib\Http\RealHeaderProxy
    {

    }
}

// Interfaces
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Interface_Address extends \OliverKlee\Oelib\Interfaces\Address
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Interface_ConfigurationCheckable extends \OliverKlee\Oelib\Interfaces\ConfigurationCheckable
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Interface_GeocodingLookup extends \OliverKlee\Oelib\Interfaces\GeocodingLookup
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Interface_Geo extends \OliverKlee\Oelib\Interfaces\Geo
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Interface_Identity extends \OliverKlee\Oelib\Interfaces\Identity
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Interface_LoginManager extends \OliverKlee\Oelib\Interfaces\LoginManager
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Interface_MailRole extends \OliverKlee\Oelib\Interfaces\MailRole
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Interface_MapPoint extends \OliverKlee\Oelib\Interfaces\MapPoint
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Interface_Sortable extends \OliverKlee\Oelib\Interfaces\Sortable
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    interface Tx_Oelib_Time extends \OliverKlee\Oelib\Interfaces\Time
    {

    }
}

// Language
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_SalutationSwitcher extends \OliverKlee\Oelib\Language\SalutationSwitcher
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Translator extends \OliverKlee\Oelib\Language\Translator
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_TranslatorRegistry extends \OliverKlee\Oelib\Language\TranslatorRegistry
    {

    }
}

// Mapper
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_IdentityMap extends \OliverKlee\Oelib\Mapper\IdentityMap
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_DataMapper extends \OliverKlee\Oelib\Mapper\AbstractDataMapper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Mapper_BackEndUser extends \OliverKlee\Oelib\Mapper\BackEndUserMapper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Mapper_BackEndUserGroup extends \OliverKlee\Oelib\Mapper\BackEndUserGroupMapper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Mapper_Country extends \OliverKlee\Oelib\Mapper\CountryMapper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Mapper_Currency extends \OliverKlee\Oelib\Mapper\CurrencyMapper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Mapper_FederalState extends \OliverKlee\Oelib\Mapper\FederalStateMapper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Mapper_FrontEndUser extends \OliverKlee\Oelib\Mapper\FrontEndUserMapper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Mapper_FrontEndUserGroup extends \OliverKlee\Oelib\Mapper\FrontEndUserGroupMapper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Mapper_Language extends \OliverKlee\Oelib\Mapper\LanguageMapper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_MapperRegistry extends \OliverKlee\Oelib\Mapper\MapperRegistry
    {

    }
}

// Model
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Model extends \OliverKlee\Oelib\Model\AbstractModel
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Model_BackEndUser extends \OliverKlee\Oelib\Model\BackEndUser
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Model_BackEndUserGroup extends \OliverKlee\Oelib\Model\BackEndUserGroup
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Model_Country extends \OliverKlee\Oelib\Model\Country
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Model_Currency extends \OliverKlee\Oelib\Model\Currency
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Model_FederalState extends \OliverKlee\Oelib\Model\FederalState
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Model_FrontEndUser extends \OliverKlee\Oelib\Model\FrontEndUser
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Model_FrontEndUserGroup extends \OliverKlee\Oelib\Model\FrontEndUserGroup
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Model_Language extends \OliverKlee\Oelib\Model\Language
    {

    }
}

// Session
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_FakeSession extends \OliverKlee\Oelib\Session\FakeSession
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Session extends \OliverKlee\Oelib\Session\Session
    {

    }
}

// Templating
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Template extends \OliverKlee\Oelib\Templating\Template
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_TemplateHelper extends \OliverKlee\Oelib\Templating\TemplateHelper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_TemplateRegistry extends \OliverKlee\Oelib\Templating\TemplateRegistry
    {

    }
}

// Tests
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_TestingFramework extends \OliverKlee\Oelib\Testing\TestingFramework
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_TestingFrameworkCleanup extends \OliverKlee\Oelib\Testing\TestingFrameworkCleanup
    {

    }
}

// ViewHelpers
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_ViewHelpers_GoogleMapsViewHelper extends \OliverKlee\Oelib\ViewHelpers\GoogleMapsViewHelper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_ViewHelper_Price extends \OliverKlee\Oelib\ViewHelpers\PriceViewHelper
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_ViewHelpers_UppercaseViewHelper extends \OliverKlee\Oelib\ViewHelpers\UppercaseViewHelper
    {

    }
}

// Visibility
namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Visibility_Node extends \OliverKlee\Oelib\Visibility\Node
    {

    }
}

namespace {
    /**
     * @deprecated will be removed in oelib 4.0.0
     */
    class Tx_Oelib_Visibility_Tree extends \OliverKlee\Oelib\Visibility\Tree
    {

    }
}

namespace OliverKlee\Oelib\Configuration {
    /**
     * @deprecated will be removed in oelib 5.0.0
     */
    class Configuration extends \OliverKlee\Oelib\Configuration\TypoScriptConfiguration
    {

    }
}
