<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class UriUtils
{
    function BuildSearchUrl($cat=0, $dist=0, $key='', $offset=null)
    { 
        $key = urldecode($key);      
        if ($offset = null)
            return base_url().'home/search/'.$cat.'/'.$dist.'/'.$key.'/';
        return base_url().'home/search/'.$cat.'/'.$dist.'/'.$key.'/'.$offset;
    }     
    function BuildViewResUrl($resId=0)
    {                    
        return base_url().'restaurant/view/'.$resId;       
    }  
       
    function BuildViewNewsUrl($id=0)
    {                    
        return base_url().'new-details/'.$id;       
    }  
          
    function BuildMenuStaticUrl($cate=0)
    {
        $url = base_url();
        switch($cate){
            case 0:
                $url.= 'tat-ca';
                break;
            case 31:
                $url.= 'tin-cap-nhat';
                break;
            case 32:
                $url.= 'su-kien';
            break;
            case 33:
                $url.= 'khuyen-mai';
            break;
            case 34:
                $url.= 'trai-nghiem';
            break;
            case 41:
                $url.= 'huong-dan';
            break;
            case 51:
                $url.= 'gioi-thieu';
            break;
        }
        return $url;       
    } 	
}

?>