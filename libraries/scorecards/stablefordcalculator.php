<?php
/*
*
*  © Terry Burns-Dyson 2012
*
*  You are licensed to use the code contained within this page (PHP / HTML / MySQL) in the capacity of the
*  current site and have agree you shall not alter, remove or distribute the code without the express permission of the author.
*
*  This code has been modified from its original form for use solely on Mortongolf.info and is not transferrable.
*  All IP remains the property of Terry Burns-Dyson who retains the rights to use any/all code for the production of
*  other sites without notification.
*
*/

class StableFordCalculator
{
    var $_handicap;
    var $_stablefordScore;

    function StableFordCalculator( $handicap )
    {
        $this->_handicap = round( $handicap);
        $this->_stablefordScore = 0;
    }

    function AddScore( $parValue, $siValue, $playerScore)
    {
        if( $playerScore == 0)
            return 0;

        $adjustByTwo = ( $this->_handicap - 18) > 0 ? $this->_handicap - 18 : 0;
        $stableFord = 0;

        if( $this->_handicap >= $siValue)
        {
            if( $adjustByTwo >= $siValue)
            {
                $adjust = 2;
            } else
                $adjust = 1;
        } else
        {
            $adjust = 0;
        }


        switch( $adjust)
        {
            case 0:
                {
                if( $playerScore >= $parValue + 2)
                    $stableFord = 0;
                if( $playerScore == $parValue + 1)
                    $stableFord = 1;
                if( $playerScore == $parValue)
                    $stableFord = 2;
                if( $playerScore == $parValue - 1)
                    $stableFord = 3;
                if( $playerScore == $parValue - 2)
                    $stableFord = 4;
                if( $playerScore == $parValue - 3)
                    $stableFord = 5;
                }break;
            case 1:
                {
                if( $playerScore == $parValue + 2)
                    $stableFord = 1;
                if( $playerScore == $parValue + 1)
                    $stableFord = 2;
                if( $playerScore == $parValue)
                    $stableFord = 3;
                if( $playerScore == $parValue - 1)
                    $stableFord = 4;
                if( $playerScore == $parValue - 2)
                    $stableFord = 5;
                if( $playerScore == $parValue - 3)
                    $stableFord = 6;
                }break;
            case 2:
                {
                if( $playerScore == $parValue + 3)
                    $stableFord = 1;
                if( $playerScore == $parValue + 2)
                    $stableFord = 2;
                if( $playerScore == $parValue + 1)
                    $stableFord = 3;
                if( $playerScore == $parValue)
                    $stableFord = 4;
                if( $playerScore == $parValue - 1)
                    $stableFord = 5;
                if( $playerScore == $parValue - 2)
                    $stableFord = 6;
                }break;
        }

        $this->_stablefordScore += $stableFord;

        return $stableFord;
    }

    function Score( )
    {
        return $this->_stablefordScore;
    }

}
?>