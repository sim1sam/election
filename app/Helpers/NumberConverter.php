<?php

namespace App\Helpers;

class NumberConverter
{
    /**
     * Convert Bangla digits to English digits
     * 
     * @param string $text
     * @return string
     */
    public static function banglaToEnglish($text)
    {
        if (empty($text)) {
            return $text;
        }
        
        $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        
        return str_replace($banglaDigits, $englishDigits, $text);
    }
    
    /**
     * Convert English digits to Bangla digits
     * 
     * @param string $text
     * @return string
     */
    public static function englishToBangla($text)
    {
        if (empty($text)) {
            return $text;
        }
        
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        
        return str_replace($englishDigits, $banglaDigits, $text);
    }
    
    /**
     * Convert date string from Bangla to English digits
     * Handles dates in formats like dd/mm/yyyy or dd-mm-yyyy
     * 
     * @param string $dateString
     * @return string
     */
    public static function convertDateToEnglish($dateString)
    {
        if (empty($dateString)) {
            return $dateString;
        }
        
        return self::banglaToEnglish($dateString);
    }
}

