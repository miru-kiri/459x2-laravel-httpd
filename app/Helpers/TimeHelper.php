<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    public static function isWithinWorkingHours($open_time, $close_time)
    {
        $open_minutes = (int)substr($open_time, 0, 2) * 60 + (int)substr($open_time, 2, 2);
        $close_minutes = (int)substr($close_time, 0, 2) * 60 + (int)substr($close_time, 2, 2);

        $now = now();
        $current_minutes = $now->hour * 60 + $now->minute;

        if ($close_minutes < $open_minutes) {
            return ($current_minutes >= $open_minutes) || ($current_minutes < $close_minutes);
        }

        return ($current_minutes >= $open_minutes) && ($current_minutes < $close_minutes);
    }

    public static function isWithinClosingHours($open_time, $close_time, $timestamp)
    {
        if (!$timestamp) {
            return false;
        }

        if (!($timestamp instanceof Carbon)) {
            $timestamp = Carbon::parse($timestamp);
        }

        $open_minutes = (int)substr($open_time, 0, 2) * 60 + (int)substr($open_time, 2, 2);
        $close_minutes = (int)substr($close_time, 0, 2) * 60 + (int)substr($close_time, 2, 2);

        $target_minutes = $timestamp->hour * 60 + $timestamp->minute;

        if ($close_minutes === 0) {
            $close_minutes = 24 * 60;
        }

        if ($close_minutes < $open_minutes) {
            return ($target_minutes < $close_minutes) || ($target_minutes >= $open_minutes);
        }

        return ($target_minutes < $open_minutes) || ($target_minutes >= $close_minutes);
    }

    public static function isWithinWorkingHoursTimestamp($open_time, $close_time, $timestamp)
    {
        if (!$timestamp) {
            return false;
        }

        if (!($timestamp instanceof Carbon)) {
            $timestamp = Carbon::parse($timestamp);
        }

        $open_minutes = (int)substr($open_time, 0, 2) * 60 + (int)substr($open_time, 2, 2);
        $close_minutes = (int)substr($close_time, 0, 2) * 60 + (int)substr($close_time, 2, 2);

        if ($close_minutes === 0) {
            $close_minutes = 24 * 60;
        }

        $target_minutes = $timestamp->hour * 60 + $timestamp->minute;

        if ($close_minutes > $open_minutes) {
            return ($target_minutes >= $open_minutes && $target_minutes < $close_minutes);
        }

        return ($target_minutes >= $open_minutes || $target_minutes < $close_minutes);
    }
}