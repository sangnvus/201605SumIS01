<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class DateUtils
{
    function ConvertToDatetime($vnDate, $format='d/m/Y')
    { 
        $timezone = new DateTimeZone('UTC');                                                        
        return date_create_from_format($format, $vnDate, $timezone);
    } 	
    
    function VnStrDatetimeToDb($vnDateString, $fomat = 'Y-m-d H:i:s')
    {                                                           
        $timezone = new DateTimeZone('UTC');                                                        
        $date = date_create_from_format('d/m/Y', $vnDateString, $timezone);
        return date_format($date, 'Y-m-d H:i:s');
    } 
    
    function DatetimeToDb($datetime, $fomat = 'Y-m-d H:i:s')
    { 
        return date_format($datetime, $fomat);
    } 

    function FormatVnDatetimeFromDb($dbDate, $format='d/m/Y')
	{                                                                                               
        $date = new DateTime($dbDate);
        return date_format($date, $format);
	} 
}

?>