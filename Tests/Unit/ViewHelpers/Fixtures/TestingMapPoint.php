<?php

namespace OliverKlee\Oelib\Tests\Unit\ViewHelpers\Fixtures;

/**
 * This is just a dummy class that implements the MapPoint interface and the Identity interface.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class TestingMapPoint implements \Tx_Oelib_Interface_MapPoint, \Tx_Oelib_Interface_Identity
{
    /**
     * @var int
     */
    private $uid = 0;

    /**
     * Gets this object's UID.
     *
     * @return int
     *         this object's UID, will be zero if this object does not have a UID yet
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Checks whether this object has a UID.
     *
     * @return bool TRUE if this object has a non-zero UID, FALSE otherwise
     */
    public function hasUid()
    {
        return $this->getUid() !== 0;
    }

    /**
     * Sets this object's UID.
     *
     * This function may only be called on objects that do not have a UID yet.
     *
     * @param int $uid the UID to set, must be > 0
     *
     * @return void
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Returns this object's coordinates.
     *
     * @return float[]
     *         this object's geo coordinates using the keys "latitude" and
     *         "longitude", will be empty if this object has no coordinates
     */
    public function getGeoCoordinates()
    {
        return ['latitude' => 11.2, 'longitude' => 4.9];
    }

    /**
     * Checks whether this object has non-empty coordinates.
     *
     * @return bool
     *         TRUE if this object has both a non-empty longitude and a
     *         non-empty latitude, FALSE otherwise
     */
    public function hasGeoCoordinates()
    {
        return true;
    }

    /**
     * Gets the title for the tooltip of this object.
     *
     * @return string the tooltip title (plain text), might be empty
     */
    public function getTooltipTitle()
    {
        return '';
    }

    /**
     * Checks whether this object has a non-empty tooltip title.
     *
     * @return bool
     *         TRUE if this object has a non-empty tooltip title, FALSE otherwise
     */
    public function hasTooltipTitle()
    {
        return false;
    }

    /**
     * Gets the info window content of this object.
     *
     * @return string the info window content (HTML), might be empty
     */
    public function getInfoWindowContent()
    {
        return '';
    }

    /**
     * Checks whether this object has a non-empty info window content.
     *
     * @return bool
     *         TRUE if this object has a non-empty info window content, FALSE otherwise
     */
    public function hasInfoWindowContent()
    {
        return false;
    }
}
