<?php
use Carbon\Carbon;

if (!function_exists('getCurrentDateTime')) {
    function getCurrentDateTime()
    {
        return Carbon::now()->format('Y-m-d H:i:s');
    }
}

if (!function_exists('getFormattedDate')) {
    function getFormattedDate($date, $format = 'Y-m-d')
    {
        return Carbon::parse($date)->format($format);
    }
}

if (!function_exists('getFormattedDateTime')) {
    function getFormattedDateTime($date, $format = 'Y-m-d H:i:s')
    {
        return Carbon::parse($date)->format($format);
    }
}

if (!function_exists('getRemainingDays')) {
    function getRemainingDays($date)
    {
        return Carbon::parse($date)->diffInDays(Carbon::now());
    }
}