<?php
class Format_Date {

	public function __contruct() {
	}

	public function easyDate($date){	
	
		$year = substr($date,0,4);
		$month = substr($date,5,2);
		$day = substr($date,8,2);
		$returnTime = substr($date,11,5);
		$returnDate = $year."-".$month."-".$day;	

		if ( mktime(0,0,0,$month,$day,$year) == mktime(0,0,0,date("m"),date("d"), date("Y")) ) {
			$returnDate = "idag";
		}
		else if ( mktime(0,0,0,$month,$day,$year) == mktime(0,0,0,date("m"),date("d")-1, date("Y")) ) {
			$returnDate = "ig&#229;r";
		}
		else if ( mktime(0,0,0,$month,$day,$year) > mktime(0,0,0,date("m"),date("d")-7, date("Y")) ) {
			$t_date = date("w", mktime(0,0,0,$month,$day,$year));
			if ( $t_date == 0 )
				$returnDate = "s&#246;ndags";
			else if ( $t_date == 1 )
				$returnDate = "m&#229;ndags";
			else if ( $t_date == 2 )
				$returnDate = "tisdags";	
			else if ( $t_date == 3 )
				$returnDate = "onsdags";	
			else if ( $t_date == 4 )
				$returnDate = "torsdags";	
			else if ( $t_date == 5 )
				$returnDate = "fredags";	
			else
				$returnDate = "l&#246;rdags";					
		}
		return $returnDate . " " . $returnTime;		
	}

	public function getMonthName($mthnr){
		$monthname;
		switch ($mthnr) {
			case 1:
				$monthname = "januari";
				break;
			case 2:
				$monthname = "februari";
				break;
			case 3:
				$monthname = "mars";
				break;
			case 4:
				$monthname = "april";
				break;
			case 5:
				$monthname = "maj";
				break;
			case 6:
				$monthname = "juni";
				break;
			case 7:
				$monthname = "juli";
				break;
			case 8:
				$monthname = "augusti";
				break;
			case 9:
				$monthname = "september";
				break;
			case 10:
				$monthname = "oktober";
				break;
			case 11:
				$monthname = "november";
				break;
			case 12:
				$monthname = "december";
				break;				
		}
		return $monthname;
	}

	public function getMonthNr($mthname){
		$monthnr;
		switch ($mthname) {
			case "januari":
				$monthnr = 1;
				break;
			case "februari":
				$monthnr = 2;
				break;
			case "mars":
				$monthnr = 3;
				break;
			case "april":
				$monthnr = 4;
				break;
			case "maj":
				$monthnr = 5;
				break;
			case "juni":
				$monthnr = 6;
				break;
			case "juli":
				$monthnr = 7;
				break;
			case "augusti":
				$monthnr = 8;
				break;
			case "september":
				$monthnr = 9;
				break;
			case "oktober":
				$monthnr = 10;
				break;
			case "november":
				$monthnr = 11;
				break;
			case "december":
				$monthnr = 12;
				break;				
		}
		return $monthnr;
	}

	function autoUrl($intext){
		$uttext = " " .$intext;
		
		// autolänkar länkar från löptext		
		$uttext = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" class=\"extlink\">\\2</a>'", $uttext);
		$uttext = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" class=\"extlink\">\\2</a>'", $uttext);

		// länkar epostadresser
		$uttext = preg_replace("#(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\" class=\"extmail\">\\2@\\3</a>", $uttext);
		
		// länkar hashtags
		$uttext = preg_replace("#(\s)(\#)(\w+)#", "\\1<a href=\"http://search.twitter.com/search?q=%23\\3\" class=\"extlink\">\\2\\3</a>", $uttext);		
		
		// länkar twitterlänkning via @
		$uttext = preg_replace("#(\s)(\@)(\w+)#", "\\1\\2<a href=\"http://twitter.com/\\3\" class=\"extlink\">\\3</a>", $uttext);		

		$uttext = substr($uttext, 1);
		
		return($uttext);
	}
}
?>
