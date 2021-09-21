<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\ViewHelpers;

use OliverKlee\Oelib\Configuration\ConfigurationRegistry;
use OliverKlee\Oelib\Interfaces\Identity;
use OliverKlee\Oelib\Interfaces\MapPoint;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This ViewHelper creates a Google Map with markers/points on it.
 *
 * Arguments:
 *
 * - `MapPoint[] mapPoints` the points to render, may be empty
 * - `string width` the CSS width of the Map element, e.g., "600px" or "100%", must be a non-empty valid CSS length
 * - `string height` the CSS height of the Map element, e.g., "400px" or "60%", must be a non-empty valid CSS length
 *
 * In the generated JavaScript, the markers will also be accessible via the map ID and the marker's UID
 * (if the markers implement IdentityInterface) like this:
 *
 * `mapMarkersByUid.tx_oelib_map_1[42]`
 *
 * `tx_oelib_map_1` is the map ID which can be retrieved with the function getMapId.
 *
 * 42 is the UID of the corresponding map point.
 */
class GoogleMapsViewHelper extends AbstractViewHelper
{
    /**
     * @var string
     */
    private const DEFAULT_WIDTH = '600px';

    /**
     * @var string
     */
    private const DEFAULT_HEIGHT = '400px';

    /**
     * array key in `$GLOBALS['TSFE']->additionalHeaderData` for the Google Maps JavaScript library
     *
     * @var string
     */
    private const LIBRARY_JAVASCRIPT_HEADER_KEY = 'tx-oelib-googleMapsLibrary';

    /**
     * the prefix to the HTML ID of the generated DIV
     *
     * @var string
     */
    private const MAP_HTML_ID_PREFIX = 'tx_oelib_map';

    /**
     * the URL of the Google Maps library
     *
     * @var string
     */
    private const GOOGLE_MAPS_LIBRARY_URL = 'https://maps.googleapis.com/maps/api/js?key=';

    /**
     * the default zoom level used when there is exactly one element with
     * coordinates (otherwise the map will automatically be zoomed so that all
     * map points are visible)
     *
     * @var int
     */
    private const DEFAULT_ZOOM_LEVEL = 8;

    /**
     * counter of the rendered map instances
     *
     * @var int
     */
    protected static $mapCounter = 0;

    /**
     * current number of the map instance (used for the HTML ID) to make sure
     * that several instances of the ViewHelper on a page will still work
     *
     * @var int
     */
    protected $mapNumber = 0;

    /**
     * Renders a Google Map with $mapPoints on it and sets the corresponding HTML HEAD data.
     *
     * @return string HTML for the Google Map, will be empty if there are no map points with coordinates
     */
    public function render(): string
    {
        /** @var array{mapPoints: MapPoint[]|null, width: ?string, height: ?string} $arguments */
        $arguments = $this->arguments;
        $mapPoints = (array)$arguments['mapPoints'];
        $width = (string)($arguments['width'] ?? self::DEFAULT_WIDTH);
        $height = (string)($arguments['height'] ?? self::DEFAULT_HEIGHT);
        if (!\preg_match('/^\\d+(px|%)$/', $width)) {
            throw new \InvalidArgumentException(
                '$width must be a valid CSS length, but actually is: ' . $width,
                1319058935
            );
        }
        if (!\preg_match('/^\\d+(px|%)$/', $height)) {
            throw new \InvalidArgumentException(
                '$height must be a valid CSS length, but actually is: ' . $height,
                1319058966
            );
        }
        $mapPointsWithCoordinates = $this->findMapPointsWithCoordinates($mapPoints);
        if (empty($mapPointsWithCoordinates)) {
            return '';
        }
        self::$mapCounter++;
        $this->mapNumber = self::$mapCounter;
        $mapId = $this->getMapId();
        $apiKey = ConfigurationRegistry::get('plugin.tx_oelib')->getAsString('googleMapsApiKey');
        // pageRenderer->addJsLibrary() would not work here if this ViewHelper
        // is used in an uncached plugin on a cached page.
        $this->getFrontEndController()->additionalHeaderData[self::LIBRARY_JAVASCRIPT_HEADER_KEY]
            = '<script src="' . self::GOOGLE_MAPS_LIBRARY_URL . $apiKey . '></script>';
        $initializeFunctionName = 'initializeGoogleMap_' . $this->mapNumber;
        $this->getFrontEndController()->additionalJavaScript[$mapId]
            = $this->generateJavaScript($mapId, $mapPointsWithCoordinates, $initializeFunctionName);

        // We use the inline JavaScript because adding body onload handlers does not work
        // for uncached plugins on cached pages.
        return '<div id="' . $mapId . '" style="width: ' .
            $width . '; height: ' . $height . ";\"></div>\n" .
            '<script>' . $initializeFunctionName . "();</script>\n";
    }

    /**
     * Generates the JavaScript for the Google Map.
     *
     * @param string $mapId HTML ID of the map, must not be empty
     * @param MapPoint[] $mapPoints map points with coordinates, must not be empty
     * @param string $initializeFunctionName name of the JavaScript initialization function to create, must not be empty
     *
     * @return string the generated JavaScript, will not be empty
     */
    protected function generateJavaScript(string $mapId, array $mapPoints, string $initializeFunctionName): string
    {
        // Note: If there are several map points with coordinates and the map
        // is fit to the map points, the Google Maps API still requires a center
        // point. In that case, any point will do (e.g., the first point).
        $centerCoordinates = $mapPoints[0]->getGeoCoordinates();

        return "var mapMarkersByUid = mapMarkersByUid || {};\n" .
            'function ' . $initializeFunctionName . "() {\n" .
            'var center = new google.maps.LatLng(' . \number_format($centerCoordinates['latitude'], 6, '.', '') . ', ' .
            \number_format($centerCoordinates['longitude'], 6, '.', '') . ");\n" .
            "var mapOptions = {\n" .
            "  mapTypeId: google.maps.MapTypeId.ROADMAP,\n" .
            "  scrollwheel: false, \n" .
            '  zoom: ' . self::DEFAULT_ZOOM_LEVEL . ", \n" .
            "  center: center\n" .
            "};\n" .
            'mapMarkersByUid.' . $mapId . " = {};\n" .
            'var map = new google.maps.Map(document.getElementById("' . $mapId . "\"), mapOptions);\n" .
            "var bounds = new google.maps.LatLngBounds();\n" .
            $this->createMapMarkers($mapPoints, $mapId) .
            '}';
    }

    /**
     * Finds the map points within $mapPoints that have coordinates.
     *
     * @param MapPoint[] $mapPoints the points to check for coordinates, may be empty
     *
     * @return array<int, MapPoint> the map points from $mapPoints that have coordinates, might be empty
     */
    protected function findMapPointsWithCoordinates(array $mapPoints): array
    {
        $mapPointsWithCoordinates = [];

        foreach ($mapPoints as $mapPoint) {
            if (!$mapPoint instanceof MapPoint) {
                throw new \InvalidArgumentException(
                    'All $mapPoints need to implement ' . MapPoint::class . ', but ' .
                    \get_class($mapPoint) . ' does not.',
                    1318093613
                );
            }
            if ($mapPoint->hasGeoCoordinates()) {
                $mapPointsWithCoordinates[] = $mapPoint;
            }
        }

        return $mapPointsWithCoordinates;
    }

    /**
     * Creates the JavaScript code for creating map markers for $mapPoints.
     *
     * @param MapPoint[] $mapPoints the points to render, all must have geo coordinates, may be empty
     * @param string $mapId HTML ID of the map, must not be empty
     *
     * @return string the JavaScript code to create all markers, will be empty if `$mapPoints` is empty
     */
    protected function createMapMarkers(array $mapPoints, string $mapId): string
    {
        $javaScript = '';

        foreach ($mapPoints as $index => $mapPoint) {
            $coordinates = $mapPoint->getGeoCoordinates();
            $positionVariableName = 'markerPosition_' . $index;
            $javaScript .= 'var ' . $positionVariableName . ' = new google.maps.LatLng(' .
                \number_format($coordinates['latitude'], 6, '.', '') . ', ' .
                \number_format($coordinates['longitude'], 6, '.', '') . ");\n" .
                'bounds.extend(' . $positionVariableName . ');';

            $markerParts = [
                'position: ' . $positionVariableName,
                'map: map',
            ];
            $escapedTooltipTitle = \str_replace(
                ['\\', '"', "\n", "\r"],
                ['\\\\', '\\"', '\\n', '\\r'],
                $mapPoint->getTooltipTitle()
            );
            if ($mapPoint->hasTooltipTitle()) {
                $markerParts[] = 'title: "' . $escapedTooltipTitle . '"';
            }

            $markerVariableName = 'marker_' . $index;
            if (($mapPoint instanceof Identity) && $mapPoint->hasUid()) {
                $markerParts[] = 'uid: ' . $mapPoint->getUid();
                $mapMarkersByUidEntry = 'mapMarkersByUid.' . $mapId .
                    '[' . $mapPoint->getUid() . '] = ' . $markerVariableName . ";\n";
            } else {
                $mapMarkersByUidEntry = '';
            }

            $javaScript .= 'var ' . $markerVariableName . " = new google.maps.Marker({\n" .
                '  ' . \implode(",\n  ", $markerParts) . "\n" .
                "});\n" .
                $this->createInfoWindowJavaScript($mapPoint, $markerVariableName, $index) .
                $mapMarkersByUidEntry;
        }

        if (\count($mapPoints) > 1) {
            $javaScript .= "map.fitBounds(bounds);\n";
        }

        return $javaScript;
    }

    /**
     * Creates the JavaScript for the info window of $mapPoint.
     *
     * @param MapPoint $mapPoint the map point for which to create the info window
     * @param string $markerVariableName valid name of the marker JavaScript variable, must not be empty
     * @param int $index the zero-based index of the map marker, must be >= 0
     *
     * @return string JavaScript code for the info window, will be empty if $mapPoint does not have info window content
     */
    protected function createInfoWindowJavaScript(
        MapPoint $mapPoint,
        string $markerVariableName,
        int $index
    ): string {
        if (!$mapPoint->hasInfoWindowContent()) {
            return '';
        }

        $infoWindowVariableName = 'infoWindow_' . $index;
        $escapedInfoWindowContent = str_replace(
            ['\\', '"', "\n", "\r"],
            ['\\\\', '\\"', '\\n', '\\r'],
            $mapPoint->getInfoWindowContent()
        );

        return 'var ' . $infoWindowVariableName . ' = new google.maps.InfoWindow({content: "' .
            $escapedInfoWindowContent . "\"});\n" .
            'google.maps.event.addListener(' . $markerVariableName . ", \"click\", function() {\n" .
            '  ' . $infoWindowVariableName . '.open(map, ' . $markerVariableName . ");\n" .
            "});\n";
    }

    /**
     * Returns the ID of the map.
     *
     * The ID is used both for the HTML ID of the DIV HTML element and as the
     * array key in $GLOBALS['TSFE']->additionalHeaderData for the maps-specific
     * Google Maps JavaScript.
     *
     * @return string the map ID, will not be empty
     */
    public function getMapId(): string
    {
        return self::MAP_HTML_ID_PREFIX . '_' . $this->mapNumber;
    }

    protected function getFrontEndController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('mapPoints', 'array', '', false, []);
        $this->registerArgument('width', 'string', '', false, '600px');
        $this->registerArgument('height', 'string', '', false, '400px');
    }
}
