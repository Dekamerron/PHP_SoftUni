<?php

class AccountsController extends BaseController {
	private $accountsModel;

	public function onInit() {
		$this->accountsModel = new AccountsModel();
	}

	public function index () {
		$this->redirect("accounts", "login");
	}

	public function register() {
		$this->title = "Register";
		
		if ($this->isPost()) {
			$username = $_POST['username'];
			if ($username === null || $username === '' || strlen($username) < 3 || strlen($username) > 20) {
				$this->addErrorMessage("Username must be in range[3...20] characters.");
				$this->redirect("accounts", "register");
			}

			$password = $_POST['password'];
			if ($password == null || strlen($password) < 3 || strlen($password) > 20) {
				$this->addErrorMessage("Password must be in range[3...20] characters.");
				$this->redirect("accounts", "register");
			}

			$isRegister = $this->accountsModel->register($username, $password);

			if ($isRegister) {
				unset($_SESSION['isAdmin']);
				$_SESSION['username'] = $username;
				$_SESSION['userId'] = $this->accountsModel->getCurrentUserId($username);
				
				$this->addInfoMessage("Successfull registration.");
				$this->redirect("home", "index");
			}
			else {
				$this->addErrorMessage("Register failed.");
			}
		}

		$this->renderView(__FUNCTION__);
	}

	public function login() {
		$this->title = "Login";
		

		if ($this->isPost()) {
			$username = $_POST['username'];
			$password = $_POST['password'];

			$isLogedIn = $this->accountsModel->login($username, $password);

			if ($isLogedIn) {
				$_SESSION['username'] = $username;
				$_SESSION['userId'] = $this->accountsModel->getCurrentUserId($username);

				$this->addInfoMessage("Successfull login.");
				$this->redirect("home", "index");
			}
			else {
				$this->addErrorMessage("Login failed.");
			}
		}

		$this->renderView(__FUNCTION__);
	}

	public function logout() {
		$this->authorize();
		unset($_SESSION['username']);
		unset($_SESSION['isAdmin']);
		$this->isLogedIn = false;
		$this->isAdmin = false;
		$this->addInfoMessage("Successfull logout.");
		$this->redirect("home", "index");
	}
}