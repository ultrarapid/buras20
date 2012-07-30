<?php
class Seasonteamtable extends App_Model
{

  /**
   * Time in seconds to cache the table of the active season
   * @var int
   */

	private $activeSeasonCacheTime = 43200; // 60 * 60 * 12;

  /**
   * Time in seconds to cache the table of the active season
   * @var int
   */
	private $historicSeasonCacheTime = 3153600000; //60 * 60 * 24 * 365 * 100;

	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->usefk 	= false;
		$this->table	= 'bik_season_team_tables';		
	}	

  protected function SetRelations()
	{	
		$this->relations['manyToOne'] = array('seasons' => array('class' => 'Season', 'fk' => 'season_id'), 'teams' => array('class' => 'Team', 'fk' => 'team_id'));
	}

  public function GetTable($teamID, $seasonID = 0)
	{
		if ( $seasonID == 0 ) {
      $season = $this->Season->GetActiveSeason();
			$seasonID = $season['Season']['id'];
		}		
    return $this->GetCachedTable($seasonID, $teamID);
	}
	
	public function GetTableBySeasonTeam($seasonID, $teamID)
	{
		$conditions = $this->conditions;
		$this->conditions = array(0 => array('field' => 'season_id', 'value' => $seasonID), 
															1 => array('field' => 'team_id', 'value' => $teamID));
		$seasonTeamTable = current($this->Get());
		$this->conditions = $conditions;
		return $seasonTeamTable;		
	}
	
	private function GetCachedTable($seasonID, $teamID)
	{
		$cacheName = Anchors::Internal('cache') . DS . 'table' . $seasonID . '-' . $teamID;
		
		$cacheTime = $this->Season->IsPastSeason($seasonID) ? $this->historicSeasonCacheTime : $this->activeSeasonCacheTime;

		if ( file_exists($cacheName) && ( time() - $cacheTime < filemtime($cacheName) ) ) {
			$tableArray = unserialize(file_get_contents($cacheName));
		} else {
			$seasonTeamTable = $this->GetTableBySeasonTeam($seasonID, $teamID);
			$tableUrl = $seasonTeamTable['Seasonteamtable']['url'];

			$gibfService = new GibfService();
			$tableArray = $gibfService->GetTable($tableUrl);
			if ( !empty($tableArray) ) {
				file_put_contents($cacheName, serialize($tableArray));
			}						
		}
		return $tableArray;		
	}

	private function GetUnCachedTable($seasonID, $teamID)
	{
		$seasonTeamTable = $this->GetTableBySeasonTeam($seasonID, $teamID);
		$tableUrl = $seasonTeamTable['Seasonteamtable']['url'];

		$gibfService = new GibfService();
		$tableArray = $gibfService->GetTable($tableUrl);

		return $tableArray;		
	}
}