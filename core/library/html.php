<?php
/*
 bounce Framework - html.php

 Copyright (C) 2012  Terry Burns-Dyson

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class Html
{
    public static function HideIfEmpty($content, $conditional = false) {
        return empty($content) && !$conditional ? "hide" : "";
    }

    public static function Coalesce($array) {
        return array_filter($array);
    }

    public static function addElementFromCollection($element, $array, $index, $editing = false, $dataType = "", $styles = array())
    {
        $html = "<${element}";

        if( sizeof($styles) > 0) {
            $html .= " class=\"";

            $styles = Html::Coalesce($styles);

            foreach( $styles as $style) {
                $html .= " ${style}";
            }

            $html .= "\"";
        }

        if( $editing)
            $html .= " data-key=\"${index}\" data-type=\"${dataType}\">";
        else
            $html .= ">";

        $html .= $array[$index];
        $html .= "</${element}>";

        return $html;
    }

    public static function addFieldFromCollection($array, $index, $editing = false, $dataType = "", $styles = array()) {
        return Html::addElementFromCollection("div", $array, $index, $editing, $dataType, $styles);
    }

    public static function StarRating($count, $name, $readonly = false) {
        $html = "";

        for($rating = 0; $rating < 5; $rating++) {
            $checked = $count <= $rating ? "checked" : '';
            $html .= "<input type=\"radio\" name=\"${name}\" value=\"${rating}\" class=\"star {split:2}\" ${checked}/>\r\n";
        }

        return $html;
    }
}
