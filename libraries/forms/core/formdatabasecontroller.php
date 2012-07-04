<?php
	class FormDatabaseController extends FormController
	{
		protected $_database;
		
		public function __construct(  )
		{
			parent::__construct( );
			
			$this->_database = Database::Connection( );
		}
	}
?>