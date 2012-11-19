<?php
class Season extends App_Model
{
	public function __construct($bound = array())
	{
		parent::__construct($bound);
		$this->ordercol = 'startdate';
		$this->order 	= 'DESC';
		$this->usefk 	= true;
		$this->table	= 'bik_seasons';
	}
	
	protected function SetRelations()
	{
		$this->relations['oneToMany'] = array(
											'game' => array('class' => 'Game', 'fk' => 'season_id'));
		//$this->relations['oneToMany']  = array('playersetting' => array('class' => 'Playersetting', 'fk' => 'game_id'));											
		
	}
	
	public function GetActiveSeason()
	{
		$conditions = $this->conditions;
		$this->conditions = array(0 => array('field' => 'startdate', 'separator' => '<', 'value' => "'" . date('Y-m-d') . "'"), 1 => array('field' => 'enddate', 'separator' => '>', 'value' => "'" . date('Y-m-d') . "'"));
		$season = current($this->Get());
		$this->conditions = $conditions;
		return $season;
	}

	public function GetPastSeasons()
	{
		$conditions = $this->conditions;
		$this->conditions = array(0 => array('field' => 'startdate', 'separator' => '<', 'value' => "'" . date('Y-m-d') . "'"), 1 => array('field' => 'visible', 'value' => 1));
		$season = $this->Get();
		$this->conditions = $conditions;
		return $season;	
	}

	public function GetSeasonByYears($startYear, $endYear)
	{
		$conditions = $this->conditions;
		$this->conditions = array(0 => array('field' => 'startdate', 'value' => "'" . $startYear . "-07-01" .  "'"), 1 => array('field' => 'enddate', 'value' => "'" . $endYear . "-06-30" . "'"));
		$season = current($this->Get());
		$this->conditions = $conditions;
		return $season;
	}

	public function GetSeasonBySeasonYears($seasonYears)
	{
		/* alla = 0, år = getid, '' = active */
		$s = array('Season' => array('id' => 0));
		if ( $seasonYears == '' ) {
			$s = $this->GetActiveSeason();
		} else if ( $seasonYears != 'alla' ) {
			$s = $this->GetSeasonByYears(substr($seasonYears, 0, 4), substr($seasonYears, 5, 4));
		}
		return $s;
	}

	public function IsPastSeason($seasonID)
	{
		$controlSeason = current($this->GetById($seasonID));
		return date('Y-m-d') > $controlSeason['Season']['enddate'];
	}
	
}