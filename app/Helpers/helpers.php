<?php

if (!function_exists('highlightSearch')) {
    function highlightSearch($text, $search)
    {
        if (empty($search) || empty($text)) {
            return $text;
        }
        
        $words = explode(' ', $search);
        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $text = str_ireplace($word, '<span class="highlight">' . $word . '</span>', $text);
            }
        }
        
        return $text;
    }
}