<?php/* * UserTest - model for storing users * * */class UserTest extends PHPUnit_Framework_TestCase{	    public function test	public function testUpdate()	{		$this->connection->expects($this->any())						 ->method('prepare')						 ->will($this->returnValue($this->stmt));								$objUser = $this->getMock('User', array(), array($this->connection));				$objExpectedUser 			 = new StdClass();		$objExpectedUser->id          = 1;		$objExpectedUser->title       = 'UserTest is usergy!';		$objExpectedUser->description = 'I tried to run this test and it FAILED';		$objExpectedUser->userID      = 1;				$objUser->expects($this->any())			    ->method('Update')			    ->with($objExpectedUser)			    ->will($this->returnValue(true));				$this->assertTrue($objUser->Update($objExpectedUser));	}		public function testAdd()	{		$this->connection->expects($this->any())						 ->method('prepare')						 ->will($this->returnValue($this->stmt));		$this->connection->expects($this->any())						 ->method('lastInsertId')						 ->will($this->returnValue(1));								$objUser = $this->getMock('User', array(), array($this->connection));				$objExpectedUser 			  = new StdClass();		$objExpectedUser->login       = 'PrgmrBill';		$objExpectedUser->password	  = 'NotAVerySecurePassword';				$objUser->expects($this->any())			    ->method('Add')			    ->with($objExpectedUser)			    ->will($this->returnValue(1));				$this->assertSame(1, $objUser->Add($objExpectedUser));	}		public function testGetUserByID()	{		$this->connection->expects($this->any())						 ->method('prepare')						 ->will($this->returnValue($this->stmt));		$objUser = $this->getMock('User', array(), array($this->connection));				$objExpectedUser 			  = new StdClass();		$objExpectedUser->id       	  = 1;		$objExpectedUser->login       = 'PrgmrBill';				$objUser->expects($this->any())			    ->method('GetUserByID')			    ->with(1)			    ->will($this->returnValue($objExpectedUser));			   		$this->assertSame($objExpectedUser, $objUser->GetUserByID($objExpectedUser->id));	}	    public function testGetUsers()	{		$objExpectedUser              = new StdClass();		$objExpectedUser->id       	  = 1;		$objExpectedUser->login       = 'PrgmrBill';				$expectedUsers     = array($objExpectedUser);				$this->connection->expects($this->any())						 ->method('prepare')						 ->will($this->returnValue($this->stmt));					$objUser = $this->getMock('User', array(), array($this->connection));				$objUser->expects($this->any())			   ->method('GetUsers')			   ->will($this->returnValue($expectedUsers));			   		$this->assertSame($expectedUsers, $objUser->GetUsers());	}	    public function setUp()    {		$this->connection = $this->getMock('MockPDO');		$this->stmt 	  = $this->getMock('PDOStatement');    }}