<?php
/*
	bounce Framework - TextEmail - For sending basic text in email form
	
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

	class TextEmail implements IEmail
	{
		private $_header;
		private $_subject;
		private $_content;
        private $_configuration;
		
		public function __construct( $subject, $content, $from = null, $replyto = null )
		{
			$configuration = SimpleConfiguration::GetInstance( );
            $this->_configuration = $configuration;
			
			if( $configuration->HasSetting('email'))
			{
				$sender = $from == null ? $configuration[ 'email'][ 'fromemail'] : $from;
				$reply = $replyto == null ? $configuration[ 'email'][ 'replyto'] : $replyto;
				
				$this->_header = "From: {$sender}\r\nReply-To: {$reply}\r\nX-Mailer: PHP/" . phpversion( ) . "\r\n";
				$this->_subject = $subject;
				$this->_content = $content;
			} else
				throw new Exception( );
		}
		
		public function Send( $to = null )
		{
			return mail( isset( $to) ? $to : $this->_configuration->GetSetting('email', 'submitemail'), $this->_subject, $this->_content, $this->_header);
		}
	}
?>