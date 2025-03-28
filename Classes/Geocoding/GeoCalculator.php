<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Geocoding;

use OliverKlee\Oelib\DataStructures\Collection;
use OliverKlee\Oelib\Interfaces\Geo;
use OliverKlee\Oelib\Model\AbstractModel;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * This class provides functions for calculating the distance between geo objects.
 */
class GeoCalculator implements SingletonInterface
{
    /**
     * the earth radius in kilometers
     *
     * @var float
     */
    private const EARTH_RADIUS_IN_KILOMETERS = 6378.7;

    /**
     * @var float
     */
    private const ONE_DEGREE_LATITUDE_IN_KILOMETERS = 111.0;

    /**
     * Calculates the great-circle distance in kilometers between two geo
     * objects using the haversine formula.
     *
     * @param Geo $object1 the first object, must have geo coordinates
     * @param Geo $object2 the second object, must have geo coordinates
     *
     * @return float the distance between $object1 and $object2 in kilometers, will be >= 0.0
     *
     * @throws \InvalidArgumentException
     */
    public function calculateDistanceInKilometers(Geo $object1, Geo $object2): float
    {
        if ($object1->hasGeoError()) {
            throw new \InvalidArgumentException('$object1 has a geo error.', 7969563665);
        }

        if ($object2->hasGeoError()) {
            throw new \InvalidArgumentException('$object2 has a geo error.', 4083730606);
        }

        if (!$object1->hasGeoCoordinates()) {
            throw new \InvalidArgumentException(
                '$object1 needs to have coordinates, but has none.',
                4175034309
            );
        }

        if (!$object2->hasGeoCoordinates()) {
            throw new \InvalidArgumentException(
                '$object2 needs to have coordinates, but has none.',
                5245310674
            );
        }

        /** @var array{latitude: float, longitude: float} $coordinates1 */
        $coordinates1 = $object1->getGeoCoordinates();
        $latitude1 = \deg2rad($coordinates1['latitude']);
        $longitude1 = \deg2rad($coordinates1['longitude']);
        /** @var array{latitude: float, longitude: float} $coordinates2 */
        $coordinates2 = $object2->getGeoCoordinates();
        $latitude2 = \deg2rad($coordinates2['latitude']);
        $longitude2 = \deg2rad($coordinates2['longitude']);

        return \acos(
            \sin($latitude1) * \sin($latitude2)
                + \cos($latitude1) * \cos($latitude2) * \cos($longitude2 - $longitude1)
        ) * self::EARTH_RADIUS_IN_KILOMETERS;
    }

    /**
     * Filters a list of geo objects by distance around another geo object.
     *
     * The returned list will only contain objects that are within $distance of
     * $center, including objects that are located at a distance of exactly
     * $distance.
     *
     * @param Collection<Geo&AbstractModel> $unfilteredObjects the list to filter, may be empty
     * @param Geo $center the center to which $distance related
     * @param float $distance the distance in kilometers within which the returned objects must be located
     *
     * @return Collection<Geo&AbstractModel> a subset of `$unfilteredObjects` with only those objects that are
     *         located within `$distance` kilometers of `$center`
     */
    public function filterByDistance(Collection $unfilteredObjects, Geo $center, float $distance): Collection
    {
        /** @var Collection<Geo&AbstractModel> $objectsWithinDistance */
        $objectsWithinDistance = new Collection();
        if (!$center->hasGeoCoordinates()) {
            return $objectsWithinDistance;
        }

        foreach ($unfilteredObjects as $object) {
            if ($object->hasGeoCoordinates() && $this->calculateDistanceInKilometers($center, $object) <= $distance) {
                $objectsWithinDistance->add($object);
            }
        }

        return $objectsWithinDistance;
    }

    /**
     * Moves $object by $distance kilometers in the direction of $direction.
     *
     * Note: This move is not very accurate.
     *
     * @param float $direction direction of the movement in degrees (0.0 is east)
     * @param float $distance distance to move in kilometers, may be positive, zero or negative
     */
    public function move(Geo $object, float $direction, float $distance): void
    {
        if (!$object->hasGeoCoordinates()) {
            return;
        }

        $directionInRadians = \deg2rad($direction);

        $originalCoordinates = $object->getGeoCoordinates();
        /** @var float $originalLatitude */
        $originalLatitude = $originalCoordinates['latitude'];
        /** @var float $originalLongitude */
        $originalLongitude = $originalCoordinates['longitude'];

        $xDeltaInKilometers = $distance * \cos($directionInRadians);
        $yDeltaInKilometers = $distance * \sin($directionInRadians);

        $oneDegreeLongitudeInKilometers = 2 * M_PI * self::EARTH_RADIUS_IN_KILOMETERS * cos($originalLongitude) / 360;

        $latitudeDelta = $yDeltaInKilometers / self::ONE_DEGREE_LATITUDE_IN_KILOMETERS;
        $longitudeDelta = $xDeltaInKilometers / $oneDegreeLongitudeInKilometers;

        $object->setGeoCoordinates(
            [
                'latitude' => $originalLatitude + $latitudeDelta,
                'longitude' => $originalLongitude + $longitudeDelta,
            ]
        );
    }

    /**
     * Moves $object at most by $maximumDistance kilometers in the direction of $direction.
     *
     * Note: This move is not very accurate.
     *
     * @param float $direction direction of the movement in degrees (0.0 is east)
     * @param float $maximumDistance maximum distance to move in kilometers, may be positive, zero or negative
     *
     * @throws \InvalidArgumentException
     */
    public function moveByRandomDistance(Geo $object, float $direction, float $maximumDistance): void
    {
        if ($maximumDistance < 0) {
            throw new \InvalidArgumentException(
                '$distance must be >= 0, but actually is: ' . $maximumDistance,
                1_407_432_668
            );
        }

        $safeDistance = $maximumDistance * .99;
        $distanceMultiplier = 10000;
        $randomDistance = \random_int(0, (int)($safeDistance * $distanceMultiplier)) / $distanceMultiplier;
        $this->move($object, $direction, $randomDistance);
    }

    /**
     * Moves $object by $distance kilometers in a random direction
     *
     * Note: This move is not very accurate.
     *
     * @param float $distance distance to move in kilometers, may be positive, zero or negative
     */
    public function moveInRandomDirection(Geo $object, float $distance): void
    {
        $direction = \random_int(0, 360);
        $this->move($object, $direction, $distance);
    }

    /**
     * Moves $object by at most $maximumDistance kilometers in a random direction
     *
     * Note: This move is not very accurate.
     *
     * @param float $maximumDistance maximum distance to move in kilometers, must not be negative
     */
    public function moveInRandomDirectionAndDistance(Geo $object, float $maximumDistance): void
    {
        $direction = \random_int(0, 360);
        $this->moveByRandomDistance($object, $direction, $maximumDistance);
    }
}
