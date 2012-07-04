<?php
/*
	bounce Framework - MysqliConnector
	
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
	class ResultSet 
	{
	  	private $_data;
		
		public function __construct( $data) {
			$this->_data = $data;		
		}
		
		public function first( ) {
			if( sizeof($this->_data) >= 1) {
				return (object)$this->_data[0];
			} else {
				throw new Exception( "First expects at least one result, none were found.");
			}
		}
		
		public function firstOrDefault( ) {
			if( sizeof( $this->_data) >= 1) {
				return (object)$this->_data[0];
			} else if( sizeof( $this->_data) == 0 ) {
				return null;
			} 
		}
		
		public function single() {
			if( sizeof($this->_data) == 1) {
				return (object)$this->_data[0];
			} else {
				throw new Exception("Single expects only one result, received many.");
			}
		}

        public function singleAs($modelType) {
            if( sizeof($this->_data) == 1)
                return $this->allAs($modelType);
            else
                throw new Exception("Single expects only one result, received many");
        }
		
		public function singleOrDefault() {
			if( sizeof( $this->_data) == 1 ) {
				return (object)$this->_data[0];
			} else {
				return null;
			}
		}

        public function any() {
            return sizeof($this->_data) > 0;
        }
		
		public function count() {
			return sizeof($this->_data);
		}
		
		public function all( ) {
			$results = array();

            foreach($this->_data as $element)
                array_push($results, (object)$element);

            return $results;
		}

        public function allAs($modelType) {
            $typeArg = new ReflectionClass($modelType);

            $properties = $typeArg->getProperties(ReflectionProperty::IS_PUBLIC);

            $elements = array_keys($this->_data[0]);

            $availableProperties = array();

            /*
             * Weed out the conflicting properties
             */
            foreach ($properties as $property)
            {
                foreach($elements as $key => $propertyName)
                {
                    if( $property->name == $propertyName)
                        array_push($availableProperties, $property->name);
                }
            }

            /*
             *  Go ahead and map the results to the model array
             */

            $results = array();

            foreach( $this->_data as $key => $value) {
                $classInstance = $typeArg->newInstance();

                foreach( $availableProperties as $propertyName)
                {
                    $property = $typeArg->getProperty($propertyName);

                    $property->setValue($classInstance, $value[$propertyName]);
                }

                array_push($results, $classInstance);
            }

            return $results;
        }
	  }
?>