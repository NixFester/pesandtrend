<?php

namespace App\Services;

class NearbySortService
{
    /**
     * Build a raw SQL Haversine formula for distance calculation.
     */
    public static function haversineRaw(float $lat, float $lng): string
    {
        $lat = (float) $lat;
        $lng = (float) $lng;

        return "(6371 * acos(cos(radians({$lat})) * cos(radians(latitude)) * cos(radians(longitude) - radians({$lng})) + sin(radians({$lat})) * sin(radians(latitude))))";
    }
}
