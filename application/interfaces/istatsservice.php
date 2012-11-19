<?php
interface IStatsService
{
	public function GetGameEventsByGameId($gameID);

	public function GetGameEventsByUrl($url);

	public function GetPlayersByGameId($gameID);
	
	public function GetPenaltyCodes();

	public function GetScheduleInSeason($url, $season = array());

	public function GetTable($url);
}