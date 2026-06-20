<?php

if (!function_exists('formatIndonesianDate')) {
    function formatIndonesianDate($date, $format = 'full')
    {
        if (!$date) return '-';
        
        $formats = [
            'full' => 'dddd, D MMMM YYYY HH:mm',        // Senin, 17 Juni 2024 14:30
            'long' => 'D MMMM YYYY HH:mm',              // 17 Juni 2024 14:30
            'medium' => 'D MMM YYYY HH:mm',             // 17 Jun 2024 14:30
            'short' => 'D/M/YYYY HH:mm',                // 17/6/2024 14:30
            'date' => 'D MMMM YYYY',                    // 17 Juni 2024
            'time' => 'HH:mm',                          // 14:30
            'time_seconds' => 'HH:mm:ss',               // 14:30:45
            'datetime_seconds' => 'D MMMM YYYY HH:mm:ss', // 17 Juni 2024 14:30:45
            'full_seconds' => 'dddd, D MMMM YYYY HH:mm:ss', // Senin, 17 Juni 2024 14:30:45
        ];
        
        return $date->locale('id')->isoFormat($formats[$format] ?? $formats['full']);
    }
}

if (!function_exists('formatIndonesianCurrency')) {
    function formatIndonesianCurrency($amount)
    {
        return 'Rp. ' . number_format($amount, 0, ',', '.');
    }
}