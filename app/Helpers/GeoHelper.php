<?php

namespace App\Helpers;

class GeoHelper
{
    // Base32 character set used for geohashing
    private static $BASE32 = "0123456789bcdefghjkmnpqrstuvwxyz";

    /**
     * Encode latitude and longitude into a Geohash string.
     *
     * @param float $latitude  Latitude value (-90 to 90)
     * @param float $longitude Longitude value (-180 to 180)
     * @param int $precision   Desired length of the geohash (default: 7, higher = more precise)
     * @return string          Geohash string representation of the location
     */
    public static function encodeGeohash($latitude, $longitude, $precision = 7)
    {
        // Define latitude and longitude range limits
        $latRange = [-90.0, 90.0];
        $lonRange = [-180.0, 180.0];

        $bit = 0;       // Keeps track of bit position in 5-bit chunks
        $ch = 0;        // Stores the 5-bit chunk before conversion to Base32
        $geohash = "";  // Final geohash string
        $even = true;   // Toggle to determine whether to refine latitude or longitude

        // Each geohash character encodes 5 bits, so loop runs for precision * 5 iterations
        for ($i = 0; $i < ($precision * 5); $i++) {
            if ($even) {
                // Longitude refinement: Divide range in half
                $mid = ($lonRange[0] + $lonRange[1]) / 2;
                if ($longitude >= $mid) {
                    $ch |= (1 << (4 - $bit));  // Set the corresponding bit to 1
                    $lonRange[0] = $mid;       // Narrow the range to upper half
                } else {
                    $lonRange[1] = $mid;       // Narrow the range to lower half
                }
            } else {
                // Latitude refinement: Divide range in half
                $mid = ($latRange[0] + $latRange[1]) / 2;
                if ($latitude >= $mid) {
                    $ch |= (1 << (4 - $bit));  // Set the corresponding bit to 1
                    $latRange[0] = $mid;       // Narrow the range to upper half
                } else {
                    $latRange[1] = $mid;       // Narrow the range to lower half
                }
            }

            $even = !$even;  // Alternate between refining longitude and latitude

            if ($bit < 4) {
                $bit++;  // Move to the next bit position in the 5-bit chunk
            } else {
                // Convert the 5-bit chunk to a Base32 character
                $geohash .= self::$BASE32[$ch];
                $bit = 0;  // Reset bit counter for the next character
                $ch = 0;   // Reset chunk storage
            }
        }

        return $geohash;
    }

    // Haversine formula to calculate the distance between two geographical points
    public static function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Radius of Earth in kilometers
        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);

        $latDiff = $latTo - $latFrom;
        $lngDiff = $lngTo - $lngFrom;

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lngDiff / 2) * sin($lngDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Returns the distance in kilometers
    }
}
