<?php
/**
 * Created by JetBrains PhpStorm.
 * User: terry
 * Date: 2/5/12
 * Time: 12:19 PM
 * To change this template use File | Settings | File Templates.
 */

class PageControls
{
    private $_links;
    private $_user;

    public function __construct(IUser $user)
    {
        $this->_user = $user;
        $this->_links = array();
    }

    public function AddLink($text, $url, $requiresRole) {
        $this->_links[] = array( "text" => $text,
                                 "url" => $url,
                                 "role" => $requiresRole);
    }

    public function GetLinks()
    {
        $results = array();

        foreach( $this->_links as $link) {
            if( $this->_user->IsPermitted($link["role"]))
                $results[] = array( "url" => $link["url"], "text" => $link["text"]);
        }

        return $results;
    }
}
