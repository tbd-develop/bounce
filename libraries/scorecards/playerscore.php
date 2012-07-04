<?php
class PlayerScore
{
private $_total;
private $_scores;
private $_playerId;

public function __construct( $id, $event, $score)
{
$this->_playerId = $id;
$this->_scores[ $event] = $score;
$this->_total += $score;
}

public function AddScore( $event, $score)
{
$this->_scores[ $event] = $score;
$this->_total += $score;
}

public function SortScores( )
{
$this->_scores = asort( $this->_scores, SORT_NUMERIC);
}

public function GetScore( $event )
{
return $this->_scores[ $event];
}

public function GetSum( )
{
return $this->_total;
}

public function Id( )
{
return $this->_playerId;
}

public static function Sort( $a, $b)
{
return $a->GetSum( ) > $b->GetSum( ) ? - 1 : 1;
}
}