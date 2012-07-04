<?php
/*
 bounce Framework - transactionalsavecontroller.php

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
class TransactionalSaveController extends FormDatabaseController
{
    public function Save(FieldUpdateModel $model, $table, $idColumn = "Id") {
        if( is_string($model->Value))
            $set = $model->Field . "= '" . $model->Value . "'";
        else
            $set = $model->Field . " = " . $model->Value;

        $query = "UPDATE ${table} SET ${set} WHERE ${idColumn} = ?";

        $this->_database->ExecuteQuery( $query, $model->Id);

        return true;
    }

    protected function prepareCheckBoxValues(FieldUpdateModel $model, $checkboxes) {
        if( in_array($model->Field, $checkboxes)) {
                $model->Value = $model->Value == "Yes" ? 1 : 0;
        }
    }

    public function prepareDisplayValue($modelValue) {
        if(is_bool($modelValue)) {
            if( $modelValue == 1 || $modelValue == 0) {
                return $modelValue == 1 ? "Yes" : "No";
            }
        }

        return $modelValue;
    }
}
