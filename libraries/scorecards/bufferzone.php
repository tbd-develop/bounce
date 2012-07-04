<?php
/*
*
*  � Terry Burns-Dyson 2012
*
*  You are licensed to use the code contained within this page (PHP / HTML / MySQL) in the capacity of the
*  current site and have agree you shall not alter, remove or distribute the code without the express permission of the author.
*
*  This code has been modified from its original form for use solely on Mortongolf.info and is not transferrable.
*  All IP remains the property of Terry Burns-Dyson who retains the rights to use any/all code for the production of
*  other sites without notification.
*
*/

class BufferZone
{
    var $_description;
    var $_lowerBound;
    var $_upperBound;
    var $_zone;
    var $_reduction;
    var $_increase;

    function BufferZone( $desc, $lowerBound, $upperBound, $zone, $reduction, $increase)
    {
        $this->_description = $desc;
        $this->_lowerBound = $lowerBound;
        $this->_upperBound = $upperBound;
        $this->_zone = $zone;
        $this->_reduction = $reduction;
        $this->_increase = $increase;
    }

    function Description( )
    {
        return $this->_description;
    }

    function LowerBound( )
    {
        return $this->_lowerBound;
    }

    function UpperBound( )
    {
        return $this->_upperBound;
    }

    function Zone( )
    {
        return $this->_zone;
    }

    function Reduction( )
    {
        return $this->_reduction;
    }

    function Increase( )
    {
        return $this->_increase;
    }
}

class BufferZoneCalc
{
    var $Zone1;
    var $Zone2;
    var $Zone3;
    var $Zone4;
    var $Zone5;

    function BufferZoneCalc( )
    {
        $this->Zone1 = new BufferZone( "Zone 1", 0.1, 5.4, 1, -0.1, 0.1);
        $this->Zone2 = new BufferZone( "Zone 2", 5.5, 12.4, 2, -0.2, 0.1);
        $this->Zone3 = new BufferZone( "Zone 3", 12.5, 20.4, 3, -0.3, 0.1);
        $this->Zone4 = new BufferZone( "Zone 4", 20.5, 28.4, 4, -0.4, 0.1);
        $this->Zone5 = new BufferZone( "Zone 5", 28.5, 999, 5, -0.5, 0.1);
    }

    function GetBuffer( $handicap)
    {
        $buffer = $this->Zone5;

        if( ( $handicap > $this->Zone1->LowerBound()) && ($handicap < $this->Zone1->UpperBound()))
        {
            $buffer = $this->Zone1;
        } else if( ($handicap > $this->Zone2->LowerBound()) && ( $handicap < $this->Zone2->UpperBound()))
        {
            $buffer = $this->Zone2;
        } else if( ($handicap > $this->Zone3->LowerBound()) && ( $handicap < $this->Zone3->UpperBound()))
        {
            $buffer = $this->Zone3;
        } else if( ($handicap > $this->Zone4->LowerBound()) && ( $handicap < $this->Zone4->UpperBound()))
        {
            $buffer = $this->Zone4;
        }

        return $buffer;
    }
}
?>