<?php
class UserControllerTest extends BaseControllerTestCase {
	
	public function testShouldLogin() {
		$this->requestAction ( 'GET', 'UserController@getLogin' );
		$this->assertRequestOk ();
	}
	
	public function testShouldDoLogin() {
		$credentials = array (
			'username' => 'admin',
			'password' => 'admin',
			'csrf_token' => Session::getToken () 
		);
		$this->withInput ( $credentials )->requestAction ( 'POST', 'UserController@postLogin' );
		$this->assertRedirection ( route('adminDashboardGetIndex') );
	}
	
	public function testShouldNotDoLoginWhenWrong() {
		$credentials = array (
			'username' => 'someone',
			'password' => 'wrong',
			'csrf_token' => Session::getToken () 
		);
		$this->withInput ( $credentials )->requestAction ( 'POST', 'UserController@postLogin' );
		$this->assertRedirection ( route('userGetLogin') );
	}
	
	/**
	 * @expectedException \Illuminate\Session\TokenMismatchException
	 */
	public function testShouldNotDoLoginWhenTokenWrong() {
		$credentials = array (
			'username' => 'admin',
			'password' => 'admin',
			'csrf_token' => '' 
		);
		$this->withInput ( $credentials )->requestAction ( 'POST', 'UserController@postLogin' );
		$this->assertRedirection ( route('userGetLogin') );
	}
	
	/**
	 * Testing redirect with logged in user.
	 */
	public function testLoginShouldRedirectUser() {
		$this->be(User::find(1));
		$this->assertTrue(Auth::check());
		$this->requestAction ( 'GET', 'UserController@getLogin' );
		$this->assertRedirection ( route('adminDashboardGetIndex') );
	}
}
