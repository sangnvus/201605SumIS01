<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Xulychuoi
{
	function GetIntroText($chuoi)
	{
		$textArray = explode('<!-- pagebreak -->', $chuoi);
		$introText = $textArray[0];
		return strip_tags($introText);
	}
	function GetContent($content)
	{
		$text = preg_replace('/<!-- pagebreak -->/', '', $content);
		return $text;
	}
	function GetTitleContact($content)
	{
		$content = strip_tags(ltrim($content));
		if (strlen($content) > 60)
		{
			$opt = 50;
			for ($i=50; $i < 55; $i++)
			{
				if ($content[$i] ==' ')
				{
					$opt = $i;
					break;
				}
				else
				{
					continue;
				}
			}
			return substr($content, 0, $opt);
		}
		return $content;
		
	}
	function GetTitleReadmost($content)
	{
		$content = ltrim($content);
		if (strlen($content) > 20)
		{
			$opt = 12;
			for ($i=12; $i < 20; $i++)
			{
				if ($content[$i] ==' ')
				{
					$opt = $i;
					break;
				}
				else
				{
					continue;
				}
			}
			return substr($content, 0, $opt);
			break;
		}
		return $content;
		
	}
    //Lay title (hay phan gioi thieu) cho bai viet
	function GetTitleNew($content, $maxlen = 200)
	{
		$content = strip_tags(ltrim($content));
		if(strlen($content) > $maxlen)
		{
			$opt = 200;
			for ($i=$maxlen-10; $i < $maxlen; $i++)
			{
				if ($content[$i] ==' ')
				{
					$opt = $i;
					break;
				}
				else
				{
					continue;
				}
			}
			return substr($content, 0, $opt);
			break;
		}
		else
		{
			return $content;
		}
	}
	    //Lay title (hay phan gioi thieu) cho bai viet
	function GetTitleAbout($content)
	{
		$content = strip_tags(ltrim($content));
		if(strlen($content) > 400)
		{
			$opt = 400;
			for ($i=400; $i < 410; $i++)
			{
				if ($content[$i] ==' ')
				{
					$opt = $i;
					break;
				}
				else
				{
					continue;
				}
			}
			return substr($content, 0, $opt);
			break;
		}
		else
		{
			return $content;
		}
	}
    //Tra ra duong dan anh dau tien trong bai viet 
	function getImageThambnail($content)
	{
		preg_match_all("/<\s*(img|IMG)[^>]*(src|SRC)\s*=\s*[\'\"]([^\'\"]*)\s*[\'\"][^>]*>/s", $content, $matches); 
		if($matches[3])
		{
			
			$temp = $matches[3][0];
			$array = explode('../', $temp, 4);
			$n = count($array);
			if ($n == 1)
			{
				return $temp;
				break;
				
			}
			else 
			{
				return base_url().$array[$n-1];
				break;
			}
		}
		else
		{
			return base_url().'images/site/sp.gif';
			break;
		}
	}
    function FixLinksImage($baseUrl, $content)
    {
        $pattern = '/\s*..\//s';
        $arr = array();
        if(preg_match($pattern, $content))
        {
            $arr = explode('../', $content);
            $n = count($arr);
            $c = (trim($arr[0]) !='') ? $arr[0] : '';
            for($i = 1; $i < $n ; $i++)
            {
                if(trim($arr[$i]) != '')
                {
                    $c .= $baseUrl.$arr[$i]; 
                }
            }
            $content = $c;
        }
        return $content;
    }
    //Tao ra url than thien
	function AliasGenerate($title)
	{
		$marTViet=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","","ẽ","ê","ề","ế","ệ","ể","ễ","ì","í","ị","ỉ","ĩ","ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ờ","ớ","ợ","ở","ỡ","ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ","ỳ","ý","ỵ","ỷ","ỹ","đ","À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă","Ằ","Ắ","Ặ","Ẳ","Ẵ","È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ","Ì","Í","Ị","Ỉ","Ĩ","Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ","Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ","Ỳ","Ý","Ỵ","Ỷ","Ỹ","Đ");
		$marKoDau=array("a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","e","e","e","","e","e","e","e","e","e","e","i","i","i","i","i","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","u","u","u","u","u","u","u","u","u","u","u","y","y","y","y","y","d","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","E","E","E","E","E","E","E","E","E","E","E","I","I","I","I","I","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","U","U","U","U","U","U","U","U","U","U","U","Y","Y","Y","Y","Y","D");
		$title = ltrim($title);
		$title = rtrim($title);
		$delimiter = array("-",",",".");
		$links = str_replace($marTViet,$marKoDau,$title);
		$links = str_replace($delimiter,' ',$links);
		$arr = explode(" ", $links);
		$array = '';
		if(count($arr) > 0)
		{
			for($i =0; $i < count($arr); $i++)
			{
				if($arr[$i] != ""){ $array =($array !="") ? $array."-".$arr[$i]:$arr[$i];}
			}
		}
		return $array;
	}
    //Dau vao la thoi gian dang 2009-10-17 08:46:27 tra ra dang 08:46:27 17/10/2009
    function GetDateFriendly($string)    
    {
        $array  = split(' ', $string);
        $string = '';
        $date   = split('-',$array[0]);
        $string = $array[1].' '.$date[2].'/'.$date[1].'/'.$date[0];
        return $string;
    }
	function ValidMail($email)
	{
		$email = strtolower($email);
		$pattern = '/^[\\w\\.-]+@([\\w\\-]+\\.)+[a-zA-Z]{2,3}$/';
        $true = preg_match($pattern, $email);
		if(!$true)
		{
			return false;
		}
		return true;
	}
    function ValidPhone($phone)
    {
		$pattern = '/^\\(?0(\\d{1,3})\\)?[- ]?(\\d{3,4})[- ]?(\\d{4})$/';
        $true = preg_match($pattern, $phone);
        if(!$true)
        {
            return false;
        }
        return true;
    }
	function GetLinksYoutube($links)
	{
		if(preg_match('/\s*watch\?v=/s', $links))
		{
			$links = str_replace('watch?v=', 'v/', $links);
			$arr = split('&', $links);
			$links = $arr[0];
			return $links;
		}
		else
		{
			$pr = split('\?', $links);
			$pr = split(',',$pr[0]);
			$id = $pr[count($pr)-1];
			$links = 'http://clip.vn/w/'.$id;
			return $links;
		}
		return $links;
	}
}

?>