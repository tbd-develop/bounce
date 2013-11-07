<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Terry
 * Date: 10/20/13
 * Time: 8:38 PM
 * To change this template use File | Settings | File Templates.
 */
class Helper
{
    public function Load($file)
    {
        include_once($file);
    }

    /*
     * Credit Chad Birch http://stackoverflow.com/questions/853813/how-to-create-a-random-string-using-php
     * Easy method, but wanted to get something working first
     */
    public static function get_random_string($valid_chars, $length)
    {
        $random_string = "";

        $num_valid_chars = strlen($valid_chars);

        for ($i = 0; $i < $length; $i++) {
            $random_pick = mt_rand(1, $num_valid_chars);

            $random_char = $valid_chars[$random_pick - 1];

            $random_string .= $random_char;
        }

        return $random_string;
    }
}