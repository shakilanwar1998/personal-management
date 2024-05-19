<?php

namespace App\Services\Traits;
use DateTime;
use Exception;

trait DateFormat
{
    public function formatMonth($date): string
    {
        try {
            $dateObject = new DateTime($date);
            return $dateObject->format('M');
        } catch (Exception $exception){
            return "";
        }
    }
}
