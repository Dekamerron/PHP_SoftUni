<?php

class AccountsModel extends BaseModel {
	
	public function register($username, $password) {
		$statement = self::$db->prepare("SELECT Id FROM users WHERE username = ?");
		$statement->bind_param("s", $username);
		$statement->execute();

		$result = $statement->get_result()->fetch_assoc();

		if($result) {
			return false;
		}

		$username = mysql_real_escape_string($username);
		$hash_pass = password_hash($password, PASSWORD_BCRYPT);

		$registerStatement = self::$db->prepare("INSERT INTO users (username, pass_hash) VALUES(?, ?)");
        $registerStatement->bind_param("ss", $username, $hash_pass);
        $registerStatement->execute();

		return true;
	}

	public function login($username, $password) {
		$statement = self::$db->prepare("SELECT username, pass_hash, is_admin FROM users WHERE username = ?");
		$statement->bind_param("s", $username);
		$statement->execute();
		
		$result = $statement->get_result()->fetch_assoc();
		if(password_verify($password, $result['pass_hash'])) {
			if ($result['is_admin'] == 1) {
				$_SESSION['isAdmin'] = true;
			}
			else {
				unset($_SESSION['isAdmin']);
			}
			
			return true;
		}

		return false;
	}

	public function getCurrentUserId($username) {
		$username = mysql_real_escape_string($username);

		$statement = self::$db->prepare("SELECT id FROM users WHERE username = ?");
		$statement->bind_param("s", $username);
		$statement->execute();

		$result = $statement->get_result()->fetch_assoc();
		
		return $result['id'];
	}
}