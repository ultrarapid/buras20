<?php
class StatsServiceProxy implements IStatsService
{
	private $_service;

	public function __construct(IStatsService $service = NULL)
	{
		$this->_service = $service === NULL ? new WebYqlService() : $service;
	}

	public function GetGameEventsByGameId($gameID)
	{
		return $this->_service->GetGameEventsByGameId($gameID);
	}

	public function GetGameEventsByUrl($url)
	{
		return $this->_service->GetGameEventsByUrl($url);
	}

	public function GetPlayersByGameId($gameID)
	{
		return $this->_service->GetPlayersByGameId($gameID);
	}
	
	public function GetPenaltyCodes()
	{
		return $this->_service->GetPenaltyCodes();
	}

	public function GetScheduleInSeason($url, $season = array())
	{
		return $this->_service->GetScheduleInSeason($url, $season);
	}

	public function GetTable($url)
	{
		return $this->_service->GetTable($url);
	}
}