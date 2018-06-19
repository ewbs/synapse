<?php
use Woodling\Woodling;
class UserTest extends TestCase {
	
	public function testUsername() {
		$user = Woodling::retrieve ( 'UserAdmin' );
		$this->assertEquals ( $user->username, 'admin' );
	}
	
	public function testIsConfirmedByEmail() {
		$user = Woodling::retrieve ( 'UserAdmin' );
		$this->assertEquals ( $user->isConfirmed (['email' => 'mgrenson@defimedia.be']), true );
	}
	
	public function testIsConfirmedByEmailFail() {
		$user = Woodling::retrieve ( 'UserAdmin' );
		$this->assertNotEquals ( $user->isConfirmed (['email' => 'non-user@example.org']), true );
	}
	
	public function testIsConfirmedByUsername() {
		$user = Woodling::retrieve ( 'UserAdmin' );
		$this->assertEquals ( $user->isConfirmed (['username' => 'admin']), true );
	}
	
	public function testIsConfirmedByUsernameFail() {
		$user = Woodling::retrieve ( 'UserAdmin' );
		$this->assertNotEquals ( $user->isConfirmed (['username' => 'non-user']), true );
	}
	
	public function testGetByUsername() {
		$user = Woodling::retrieve ( 'UserAdmin' );
		$this->assertNotEquals ( $user->getUserByUsername ( 'admin' ), false );
	}
	
	public function testGetByUsernameFail() {
		$user = Woodling::retrieve ( 'UserAdmin' );
		$this->assertEquals ( $user->getUserByUsername ( 'non-user' ), false );
	}
}
