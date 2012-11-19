<?php
abstract class StatsService implements ISTatsService
{
	/* 
	 * uncomment all abstract methods if php get upgraded past 5.3.9
	 *
	 */
	
	//abstract public function GetGameEventsByGameId($gameID);

	//abstract public function GetGameEventsByUrl($url);

	//abstract public function GetPlayersByGameId($gameID);
	
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

	//abstract public function GetScheduleInSeason($url, $season = array());

	//abstract public function GetTable($url);
	
}