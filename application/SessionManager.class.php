<?php/* * Handles basic session functionality * */abstract class SessionManager{	const SESSION_NAMESPACE = 'SessionManager';	protected $data = array();		public function __construct()	{		if( ! headers_sent() )		{			if( ! isset($_SESSION[self::SESSION_NAMESPACE]) )			{				$_SESSION[self::SESSION_NAMESPACE] = array();			}		}	}		public function Add($key, $value)	{		$this->data[$key] = $value;	}		public function Get($key)	{		return isset($this->data[$key]) ? $this->data[$key] : '';	}}