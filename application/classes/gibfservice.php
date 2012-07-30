<?php
class GibfService {
	
	private $team = 'Burås IK'; // team to look for on gibf
	private $tableClass = 'tableList'; // class of table on gibf (if structure on gibf changes, the query and nodevalues will have to be redone)
	private $yqlUrl = "http://query.yahooapis.com/v1/public/yql?q="; // yahoo query language service url
	
	/*
	params:
	$url = url of full schedule on gibf
	$season (optional) = season array, restricting retrieved data. Only is displayed if between dates	
	*/
	
	public function GetGameEventsByGameId($gameID)
	{
		return $this->GetGameEvents('http://www.innebandy.se/Templates/IDA/MatchInfo.aspx?PageID=106388&MatchID=' . $gameID . '&epslanguage=SV');
	}
	
	public function GetGameEventsByUrl($url)
	{
		return $this->GetGameEvents($url);
	}
	
	public function GetPenaltyCodes()
	{
		return array(
			201 => 'Slag', 
			202 => 'Låsning av klubba', 
			203 => 'Lyftning av klubba', 
			204 => 'Otillåten spark', 
			205 => 'Hög spark', 
			206 => 'Hög klubba', 
			207 => 'Otillåten trängning', 
			208 => 'Hårt spel 2 min', 
			209 => 'Fasthållning', 
			210 => 'Obstruktion', 
			211 => 'Felaktigt avstånd', 
			212 => 'Liggande spel', 
			213 => 'Hands', 
			214 => 'Nick', 
			215 => 'Felaktigt byte', 
			216 => 'För många spelare på plan', 
			217 => 'Upprepade förseelser', 
			218 => 'Fördröjande av spelet', 
			219 => 'Protest mot domslut', 
			220 => 'Felaktigt beträdande av spelplanen', 
			221 => 'Felaktig utrustning', 
			222 => 'Mätning av klubba', 
			223 => 'Felaktig numrering', 
			224 => 'Spel utan klubba', 
			225 => 'Ej avlägsnat avslagna klubbdelar', 
			226 => 'Straff pga hopp', 
			227 => 'Straff pga betr av målvaktsområde', 
			501 => 'Våldsamt slag', 
			502 => 'Farligt spel', 
			503 => 'Hakning', 
			504 => 'Hårt spel, 5 min', 
			505 => 'Upprepade förseelser', 
			101 => 'Osportsligt uppträdande', 
			301 => 'Matchstraff 1', 
			302 => 'Matchstraff 2', 
			303 => 'Matchstraff 3');
	}
	
	public function GetScheduleInSeason($url, $season = array())
	{

		$gibfXml   = $this->GetGibfXml("select * from html where url = '" . $url . "' AND xpath=\"//table[@class='" . $this->tableClass . "']/tr/td[a='" . $this->team . "']/..\"");
		$rows      = $gibfXml->documentElement->getElementsByTagName("results")->item(0)->getElementsByTagName("tr");
		$gibfArray = array();		
		foreach ( $rows as $k => $row ) {			
			$date = $this->ExtractFormattedGameDate($row);
			if ( empty($season) || ( $date >= $season['Season']['startdate'] && $date <= $season['Season']['enddate'] ) ) {
				$hometeam = $this->ExtractGameHomeTeam($row);
				$awayteam = $this->ExtractGameAwayTeam($row);
				$result = $this->ExtractFormattedGameResult($row);
			  $gibfArray[$k]['gamedate'] = $date . ' ' . $this->ExtractFormattedGameTime($row);				
				if ( $hometeam == $this->team ) {					
					$gibfArray[$k]['homegame']   = 1;
					$gibfArray[$k]['opponent']   = $awayteam;
					$gibfArray[$k]['ourscore']   = substr($result, 0, strpos($result, '-'));
					$gibfArray[$k]['theirscore'] = substr($result, strpos($result, '-') + 1);					
				} else if ( $awayteam == $this->team ) {					
					$gibfArray[$k]['homegame']   = 0;
					$gibfArray[$k]['opponent']   = $hometeam;
					$gibfArray[$k]['theirscore'] = substr($result, 0, strpos($result, '-'));
					$gibfArray[$k]['ourscore']   = substr($result, strpos($result, '-') + 1);
				}	
				$gibfArray[$k]['ibfid']    = $this->ExtractGameID($row);
				$gibfArray[$k]['location'] = $this->ExtractFormattedGameLocation($row);
			}
		}
		return $gibfArray;
	}
	
	public function GetPenaltyNameByCode($code)
	{

	}
	
	public function GetTable($url)
	{
		$gibfArray = array();

		if (  ( $gibfXml = $this->GetGibfXml("select * from html where url = '" . $url . "' AND xpath=\"//div[not(@id='divTopScorers')]/div[@class='tableContent']/table[@class='" . $this->tableClass . "']/tr[not(@class='dividerRegular')]/td/..\"") ) !== false ) {

			$rows = $gibfXml->documentElement->getElementsByTagName("results")->item(0)->getElementsByTagName("tr");
		
			foreach ( $rows as $k => $row ) {
				$gibfArray[$k]['position']   = $this->ExtractTablePosition($row);
				$gibfArray[$k]['team']       = $this->ExtractTableTeam($row);
				$gibfArray[$k]['matches']    = $this->ExtractTableMatches($row);
				$gibfArray[$k]['victories']  = $this->ExtractTableVictories($row);
				$gibfArray[$k]['draws']      = $this->ExtractTableDraws($row);
				$gibfArray[$k]['defeats']    = $this->ExtractTableDefeats($row);
				$gibfArray[$k]['plusgoals']  = $this->ExtractTablePlusgoals($row);
				$gibfArray[$k]['minusgoals'] = $this->ExtractTableMinusgoals($row);
				$gibfArray[$k]['points']     = $this->ExtractTablePoints($row);
			}

		} else {



		}
		
		return $gibfArray;
	}
	
	private function ConvertToGametime($time, $period)
	{
		return (int)substr($time, 0, 1) + ($period - 1) * 2 * ( (int)substr($time, 0, 1) > 1 ? 0 : 1 ) . str_replace('.', ':', substr($time, 1));
	}

	private function ExtractEventName($row)
	{
		return $row->firstChild->nextSibling->firstChild->nodeValue;
	}

	private function ExtractEventTeam($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->firstChild->nodeValue;
	}
	
	private function ExtractEventTime($row)
	{
		return $row->firstChild->getElementsByTagName('p')->item(0)->nodeValue;
	}
		
	private function ExtractFormattedGameDate($row)
	{
		$date = $this->ExtractGameDate($row);
		return '20' . substr($date, 0, 2) . '-' . substr($date, 2, 2) . '-' . substr($date, 4, 2);
	}

	private function ExtractFormattedGameLocation($row)
	{
		$location = $this->ExtractGameLocation($row);
		if ( $location == 'Svingelns Sporthall' ) {
			return 'Svingeln';
		} else {
			return $location;
		}
	}

	private function ExtractFormattedGameResult($row)
	{
		return Formatter::StripResult($row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->getElementsByTagName("a")->item(0)->firstChild->nextSibling->nextSibling->nodeValue);
	}
	
	private function ExtractFormattedGameTime($row)
	{
		$time = $this->ExtractGameTime($row);
		return substr($time, 0, 2) . ':' . substr($time, 3, 2) . ':00';
	}

	private function ExtractGameAwayTeam($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->getElementsByTagName("a")->item(0)->nodeValue;
	}
	
	private function ExtractGameDate($row)
	{
		return $row->firstChild->getElementsByTagName("a")->item(0)->nodeValue;
	}
	
	private function ExtractGameHomeTeam($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->getElementsByTagName("a")->item(0)->nodeValue;
	}

  private function ExtractGameID($row)
	{
		$href = $row->firstChild->getElementsByTagName("a")->item(0)->getAttribute('href');
		return substr($href, strpos($href, 'MatchID=') + 8, strpos($href, "&epslanguage") - strpos($href, 'MatchID=') - 8);	
	}

	private function ExtractGameLocation($row)
	{
    return $row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->getElementsByTagName("p")->item(0)->nodeValue;
	}

	private function ExtractGameTime($row)
	{
		return $row->firstChild->nextSibling->getElementsByTagName("p")->item(0)->nodeValue;
	}
	
	private function ExtractPenalty($row)
	{
		if ( substr($this->ExtractPenaltyName($row), 0, 11) == 'Matchstraff' ) {
			return 'U5';
		} else {
			return $row->firstChild->nextSibling->nextSibling->firstChild->nodeValue;
		}
		//print_r(' ' . $row->firstChild->nextSibling->nextSibling->firstChild->nodeValue . ' time: ' . mktime());
	}

	private function ExtractPenaltyName($row)
	{
		return str_replace(')', '', str_replace('(', '', $row->firstChild->nextSibling->nextSibling->nextSibling->firstChild->nextSibling->firstChild->nodeValue));
	}

	private function ExtractPenaltytimePlayer($row)
	{
		$penalty = $this->ExtractPenalty($row);
		if ( strpos($penalty, '+') ) {
			return substr($penalty, strpos($penalty, '+') + 1);			
		} else {
			return substr($penalty, 1, 1);
		}
	}
	
	private function ExtractPenaltytimeTeam($row)
	{
		return substr($this->ExtractPenalty($row), 1, 1);
	}
	
	private function ExtractPeriod($row)
	{
		return substr($row->firstChild->firstChild->nodeValue, 7, 1);
	}
	
	private function ExtractPrimaryID($row)
	{
  	$href = $row->firstChild->nextSibling->nextSibling->nextSibling->firstChild->firstChild->getAttribute('href');
		return substr($href, strpos($href, 'PlayerID=') + 9, strpos($href, "&epslanguage") - strpos($href, 'PlayerID=') - 9);		
	}

	private function ExtractPrimaryName($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->firstChild->firstChild->nodeValue;
	}

	private function ExtractScoreboard($row)
	{
		return $row->firstChild->nextSibling->nextSibling->firstChild->nodeValue;
	}

	private function ExtractSecondaryID($row)
	{
		$href = $row->firstChild->nextSibling->nextSibling->nextSibling->firstChild->nextSibling->firstChild->getElementsByTagName('a')->item(0)->getAttribute('href');
		return substr($href, strpos($href, 'PlayerID=') + 9, strpos($href, "&epslanguage") - strpos($href, 'PlayerID=') - 9);
	}

	private function ExtractSecondaryName($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->firstChild->nextSibling->firstChild->getElementsByTagName('a')->item(0)->nodeValue;
	}

	private function ExtractTableDefeats($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->getElementsByTagName("p")->item(0)->nodeValue;
	}

	private function ExtractTableDraws($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->getElementsByTagName("p")->item(0)->nodeValue;		
	}

	private function ExtractTableMatches($row)
	{
		return $row->firstChild->nextSibling->nextSibling->getElementsByTagName("p")->item(0)->nodeValue;
	}

	private function ExtractTableMinusgoals($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->getElementsByTagName("p")->item(0)->nodeValue;
	}

	private function ExtractTablePlusgoals($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->getElementsByTagName("p")->item(0)->nodeValue;
	}

	private function ExtractTablePoints($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->nextSibling->getElementsByTagName("p")->item(0)->nodeValue;
	}

	private function ExtractTablePosition($row)
	{
		return trim($row->firstChild->getElementsByTagName("p")->item(0)->nodeValue, '.');
	}
	
	private function ExtractTableTeam($row)
	{
		return $row->firstChild->nextSibling->getElementsByTagName("a")->item(0)->nodeValue;
	}
	
	private function ExtractTableVictories($row)
	{
		return $row->firstChild->nextSibling->nextSibling->nextSibling->getElementsByTagName("p")->item(0)->nodeValue;
	}
	
	private function GetGameEvents($url)
	{
		$gameEvents = array();

		if (  ( $gibfXml = $this->GetGibfXml("select * from html where url = '" . $url . "' AND xpath=\"//div/h2[text()='Matchhändelser']/following-sibling::table/tr/following::tr\"") ) !== false ) {
			$rows       = $gibfXml->documentElement->getElementsByTagName('results')->item(0)->getElementsByTagName('tr');
			if ( !empty($rows) ) {			
				$period    = 0;
				$i         = 0;	
				$reverse 	 = 0;		
				foreach ( $rows as $k => $row ) {
					if ( $k == 0 ) {
						$reverse = $this->IsDescendingTime($row);
					}
					if ( $this->IsNewPeriod($row) ) {
						$period = $this->ExtractPeriod($row);
					} else {
						$eventName = $this->ExtractEventName($row);
						$gameEvents[$i]['eventName'] = $eventName;
						$gameEvents[$i]['time'] = $this->ConvertToGametime($this->ExtractEventTime($row), $period);
						$gameEvents[$i]['team'] = $this->ExtractEventTeam($row);
						if ( $eventName == 'Mål' ) {
							$gameEvents[$i] = array_merge($gameEvents[$i], $this->GetGoalData($row));
						} else if ( $eventName == 'Utvisning' ) {
							$gameEvents[$i] = array_merge($gameEvents[$i], $this->GetPenaltyData($row));						
						} else if ( $eventName == 'Time out' ) {
							$gameEvents[$i] = array_merge($gameEvents[$i], $this->GetTimeoutData($row));
						}
						$i++;
					}				
				}
				if ( $reverse ) {
					$gameEvents = array_reverse($gameEvents);
				}
			}
		} 
		return $gameEvents;		
	}
	
	private function GetGibfXml($query)
	{
  	$gibfXml = new DomDocument();
		$gibfXml->preserveWhiteSpace = false;
		if ( @$gibfXml->load($this->yqlUrl . rawurlencode($query)) === false ) {
			//print_r('fel vid gibfuppkoppling');
			return false;
		}

		//print_r($this->yqlUrl . rawurlencode($query));
		return $gibfXml;
	}
	
	private function GetGoalData($row)
	{
		$goalArray = array();
		
		$goalArray['goal']              = 1;
		$goalArray['goal_scoreboard']   = $this->ExtractScoreboard($row);
		$goalArray['goal_primary_name'] = $this->ExtractPrimaryName($row);
		$goalArray['goal_primary_id']   = $this->ExtractPrimaryID($row);
		
		if ( $this->IsAssisted($row) ) {
			$goalArray['goal_secondary_name'] = $this->ExtractSecondaryName($row);
			$goalArray['goal_secondary_id']   = $this->ExtractSecondaryID($row);
		}
		
		return $goalArray;	
	}
	
	private function GetPenaltyCode($pname, $pteamtime)
	{
		$codes = array_flip($this->GetPenaltyCodes());
		if ( $pname == 'Upprepade förseelser' && $pteamtime < 5 ) {
			return 217;
		} else {
			return $codes[$pname];
		}
	}

	private function GetPenaltyData($row)
	{
		$penaltyArray = array();
		
		$penaltyArray['penalty']             = 1;
		$penaltyArray['penalty_time_team']   = $this->ExtractPenaltytimeTeam($row);
		$penaltyArray['penalty_time_player'] = $this->ExtractPenaltytimePlayer($row);
		$penaltyArray['penalty_player_name'] = $this->ExtractPrimaryName($row);
		$penaltyArray['penalty_player_id']   = $this->ExtractPrimaryID($row);
		$penaltyArray['penalty_name']        = $this->ExtractPenaltyName($row);
		$penaltyArray['penalty_code']				 = $this->GetPenaltyCode($penaltyArray['penalty_name'], $penaltyArray['penalty_time_team']);	
		return $penaltyArray;
	}
	
	private function GetTimeoutData($row)
	{
		$timeoutArray = array();
		$timeoutArray['timeout'] = 1;
		return $timeoutArray;
	}
	
	private function IsDescendingTime($row)
	{
		return $row->firstChild->firstChild->nodeValue == 'Period 3';
	}

	private function IsGameHomeTeam($row)
	{
		return ( $this->ExtractGameHomeTeam($row) == $this->team );
	}
	
	private function IsNewPeriod($row)
	{
		return ( $row->firstChild->hasAttribute('class') && $row->firstChild->getAttribute('class') == 'CompetitionRound' );
	}
	
	private function IsAssisted($row)
	{
		return isset($row->firstChild->nextSibling->nextSibling->nextSibling->firstChild->nextSibling);
	}

	private function LocalFilter($value)
	{
		return $value;
	}
}
