<?php
class Formatter 
{
	
	private function __construct(){}

	/**
	* Replace all linebreaks with <br />
	*
	* @param  string $text Text to convert
	* @return string
	*/
	public static function ConvertLinebreaksToBr($text)
	{
		return nl2br($text);
	}

	/**
	* Finds all href and src in a string starting with '/' (not '//')
	* which is internal link, and adds the root link
	*
	* @param  string $string Text to convert
	* @return string
	*/
	public static function ConvertLocalLinks($string)
	{		
		return preg_replace('/((?:href|src)=[\"\'])([\/][^\/].+?[\"\'])/', '\\1' . Anchors::Refer('base') . '\\2', $string);
	}

	/**
	* Changes format YYYY-MM-DD to day (without leading zeroes), monthname (in swedish), year.
	*
	* @param  string $string Date in textformat YYYY-MM-DD
	* @return string day-monthname-year
	*/
	public static function CreateDateSlug($gamedate)
	{
		$year  = substr($gamedate, 0, 4);
		$month = self::GetMonthName(substr($gamedate, 5, 2));
		$day   = ltrim(substr($gamedate, 8, 2), '0');
		return $day . '-' . $month . '-' . $year;
	}

	/**
	* Removes all chars but a-z and 0-9. Returns as lowercase.
	* Spaces is switched to -
	*
	* @param  string $string Text to convert
	* @return string
	*/
	public static function CreateSlug($string) 
	{
		$encodedString = $string;
		return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), self::RemoveAccents($encodedString)));
	}

	/**
	* Deprecated
	*
	* @param  string $string Text to convert
	* @return string
	*/	
	public static function FilterText($text)
	{
		//return preg_replace_callback('/[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+/', 'self::Obfuscate', $text);
		return $text;
	}

	/**
	* Returns monthname in swedish
	*
	* @param  string $monthNumber with leading zeroText to convert
	* @return string Month name in swedish
	*/
	public static function GetMonthName($monthNumber){
		$monthname = 'testmanad';
		switch ($monthNumber) {
			case '01':
				$monthname = 'januari';
				break;
			case '02':
				$monthname = 'februari';
				break;
			case '03':
				$monthname = 'mars';
				break;
			case '04':
				$monthname = 'april';
				break;
			case '05':
				$monthname = 'maj';
				break;
			case '06':
				$monthname = 'juni';
				break;
			case '07':
				$monthname = 'juli';
				break;
			case '08':
				$monthname = 'augusti';
				break;
			case '09':
				$monthname = 'september';
				break;
			case '10':
				$monthname = 'oktober';
				break;
			case '11':
				$monthname = 'november';
				break;
			case '12':
				$monthname = 'december';
				break;				
		}
		return $monthname;
	}

	/**
	* Returns an obfuscated string
	*
	* @param  string $string To obfuscate
	* @return string
	*/
  public static function Obfuscate($string)
	{
		//$length = ((is_array($string))? count($string): strlen($string));
		$string = ((is_array($string)) ? $string[0] : $string);
		$obfuscatedString = '';
		for ( $i = 0 ; $i < strlen($string) ; $i++ ) {
			if ( rand(0,1) == 1 ) {
				$obfuscatedString .= '&#' . ord($string[$i]) . ';';
			} else {
				$obfuscatedString .= '&#x' . bin2hex($string[$i]) . ';';  
			}
		}
		return $obfuscatedString;
	}

	/**
	* Finds email adresses in a text and calls obfuscate for each entry
	*
	* @param  string $text To find emails in
	* @return string Entire string with obfuscated emails.
	*/
	public static function ObfuscateEmails($text)
	{
		return preg_replace_callback('/[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+/', 'self::Obfuscate', $text);
	}

	/**
	* Changes a date into today, yesterday or the day name if from last week
	* Otherwise returns it as day, monthname, year.
	*
	* @param  date $date Date in YYYY-MM-DD format
	* @return string Date in converted format
	*/
	public static function ReadableDate($date)
	{	
		$year  = substr($date,0,4);
		$month = substr($date,5,2);
		$day   = substr($date,8,2);

		$returnDate = $day . ' ' . substr(self::GetMonthName($month), 0, 3) . ' ' . $year;

		if ( mktime(0,0,0,$month,$day,$year) == mktime(0,0,0,date('m'),date('d'), date('Y')) ) {
			$returnDate = 'idag';
		}
		else if ( mktime(0,0,0,$month,$day,$year) == mktime(0,0,0,date('m'),date('d')-1, date('Y')) ) {
			$returnDate = 'igår';
		}
		else if ( mktime(0,0,0,$month,$day,$year) == mktime(0,0,0,date('m'),date('d')+1, date('Y')) ) {
			$returnDate = 'imorgon';
		}		
		else if ( mktime(0,0,0,$month,$day,$year) > mktime(0,0,0,date('m'),date('d')-7, date('Y')) && mktime(0,0,0,$month,$day,$year) < mktime(0,0,0,date('m'),date('d')+7, date('Y')) ) {
			$weekday = date('w', mktime(0,0,0,$month,$day,$year));
			if ( $weekday == 0 )
				$returnDate = 'söndag';
			else if ( $weekday == 1 )
				$returnDate = 'måndag';
			else if ( $weekday == 2 )
				$returnDate = 'tisdag';	
			else if ( $weekday == 3 )
				$returnDate = 'onsdag';	
			else if ( $weekday == 4 )
				$returnDate = 'torsdag';	
			else if ( $weekday == 5 )
				$returnDate = 'fredag';	
			else
				$returnDate = 'lördag';					
		}
		return $returnDate;		
	}

	/**
	* Changes a date into today, yesterday or the day name if from last week
	* Otherwise returns it as day, monthname, year.
	*
	* @param  date $date Date in YYYY-MM-DD format
	* @return string Date in converted format
	*/
	public static function ReadableDateWithDay($date)
	{	
		$year  = substr($date,0,4);
		$month = substr($date,5,2);
		$day   = substr($date,8,2);

		$returnDate = $day . ' ' . substr(self::GetMonthName($month), 0, 3) . ' ' . $year;

		if ( mktime(0,0,0,$month,$day,$year) == mktime(0,0,0,date('m'),date('d'), date('Y')) ) {
			$returnDate = 'idag';
		}
		else if ( mktime(0,0,0,$month,$day,$year) == mktime(0,0,0,date('m'),date('d')-1, date('Y')) ) {
			$returnDate = 'igår';
		}
		else if ( mktime(0,0,0,$month,$day,$year) == mktime(0,0,0,date('m'),date('d')+1, date('Y')) ) {
			$returnDate = 'imorgon';
		}		
		else if ( mktime(0,0,0,$month,$day,$year) > mktime(0,0,0,date('m'),date('d')-7, date('Y')) && mktime(0,0,0,$month,$day,$year) < mktime(0,0,0,date('m'),date('d')+7, date('Y')) ) {
			$returnDate = self::GetSwedishWeekday($date);			
		} else {
			$returnDate = self::GetSwedishWeekday($date) . ' ' . $returnDate;
		}
		return $returnDate;		
	}

	/**
	* Strips anything that isn't 0-9 or -.
	*
	* @param  string $string
	* @return string
	*/	
	public static function StripResult($string)
	{
		$encodedString = utf8_encode($string);
		return strtolower(preg_replace(array('/[^0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), self::RemoveAccents($encodedString)));		
	}

	/**
	* Replaces html entities with xml entities
	*
	* @param  string $string
	* @return string
	*/		
	public static function XmlEntities($string) 
	{
		$string = htmlentities($string);
    $xml = array('&#34;','&#38;','&#38;','&#60;','&#62;','&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;');
    $html = array('&quot;','&amp;','&amp;','&lt;','&gt;','&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;');
		$string = str_replace($html,  $xml, $string);
		$string = str_ireplace($html, $xml, $string); 
		return $string; 
	}

	/**
	* Returns swedish day of the week
	*
	* @param  string $date 'YYYY-MM-DD'
	* @return string
	*/
	private static function GetSwedishWeekday($date)
	{
		$year  = substr($date,0,4);
		$month = substr($date,5,2);
		$day   = substr($date,8,2);

		$weekday = date('w', mktime(0,0,0,$month,$day,$year));

		$returnDate = '';

		if ( $weekday == 0 ) {
			$returnDate = 'söndag';
		}	else if ( $weekday == 1 ) {
			$returnDate = 'måndag';			
		} else if ( $weekday == 2 ) {
			$returnDate = 'tisdag';
		} else if ( $weekday == 3 ) {
			$returnDate = 'onsdag';
		}	else if ( $weekday == 4 ) {
			$returnDate = 'torsdag';
		}	else if ( $weekday == 5 ) {
			$returnDate = 'fredag';
		} else if ( $weekday == 6 ) {
			$returnDate = 'lördag';
		}

		return $returnDate;
	}

	/**
	* Replaces swedish chars åäö with aao
	*
	* @param  string $string
	* @return string
	*/	
	private static function RemoveAccents($string) 
	{
		//$match = array('/[àâäåãáæ]/ui','/[ÂÄÀÅÃÁÆ]/ui', '/[ß]/ui','/[çÇ]/ui','/[Ð]/ui','/[éèêëÉÊËÈ]/ui','/[ïîìíÏÎÌÍ]/ui','/[ñÑ]/ui','/[öôóòõÓÔÖÒÕ]/ui','/[__]/ui','/[ùûüúÜÛÙÚ]/ui','/[¥_Ý_ýÿ]/ui','/[__]/ui');
		$match = array('/[äåÅÄ]/u', '/[öÖ]/u');
		$replace = array('a', 'o');
	 
		return preg_replace($match, $replace, $string);
	}	 
}