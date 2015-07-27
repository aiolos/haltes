<?php
/**
 * Haltes
 *
 * PHP Version 5.3
 *
 * @category  Haltes
 * @package   Application\Form
 * @author    Henri de Jong <henri.dejong@mobiliteitsfabriek.nl>
 * @author    Peter Lammers <peter.lammers@mobiliteitsfabriek.nl>
 * @copyright 2013 Regiecentrale
 * @license   http://www.regiecentrale.nl/license Commercial License
 * @version   SVN: $Id$
 * @link      http://www.regiecentrale.nl
 */
namespace Application\Math;

use \SplPriorityQueue;
use SplStack;

class Coordinate
{
    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public function haversineGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(
            sqrt(
                pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
            )
        );
        return $angle * $earthRadius;
    }
}
