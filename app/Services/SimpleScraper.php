<?php

namespace App\Services;

class SimpleScraper
{
    public function getData($url, $pattern = '', $deltab = []) {
        $options = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
            ]
        ];
        $context = stream_context_create($options);
        $str = file_get_contents($url,false,$context);
        if(!empty($deltab)) $str = str_replace($deltab,'',$str);
        if(!empty($pattern)){
            if (preg_match_all($pattern, $str, $matches)) {
                return $matches[0];
            }
            return "Nie znaleziono żadnych dopasowań.";
        }
        return $str;
    }
}