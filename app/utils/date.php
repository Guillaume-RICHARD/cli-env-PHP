<?php

namespace App\utils;

class date
{
    /**
     * path constructor.
     * @param string $path
     */
    public function __construct() {}

    /**
     * @param $month
     * @return string $month_dec
     */
    public function switchTo($month): string {
        switch ($month) {
            case "Janvier":
                $month_dec = "01";
                break;
            case "Février":
                $month_dec = "02";
                break;
            case "Mars":
                $month_dec = "03";
                break;
            case "Avril":
                $month_dec = "04";
                break;
            case "Mai":
                $month_dec = "05";
                break;
            case "Juin":
                $month_dec = "06";
                break;
            case "Juillet":
                $month_dec = "07";
                break;
            case "Août":
                $month_dec = "08";
                break;
            case "Septembre":
                $month_dec = "09";
                break;
            case "Octobre":
                $month_dec = "10";
                break;
            case "Novembre":
                $month_dec = "11";
                break;
            case "Décembre":
                $month_dec = "12";
                break;
        }
        return $month_dec;
    }
}