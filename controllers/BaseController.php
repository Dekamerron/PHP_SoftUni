<?php

abstract class BaseController {
    protected $controller;
    protected $action;
    protected $layout = DEFAULT_LAYOUT;
    protected $viewBag = [];
    protected $viewRendered = false;
    protected $user;
    protected $isLoggedIn;
    protected $isAdmin;

    public function __construct($controller, $action) {
        $this->controller = $controller;
        $this->action = $action;

        if ($this->isLoggedIn()) {
            $this->isLoggedIn = true;
        }

        if ($this->isAdmin()) {
            $this->isAdmin = true;
        }

        $this->onInit();

    }

    public function __get($name) {
        if (isset($this->viewBag[$name])) {
            return $this->viewBag[$name];
        }
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }

    public function __set($name, $value) {
        $this->viewBag[$name] = $value;
    }

    protected function onInit() {
    }

    public function index() {
        $this->renderView();
    }

    public function renderView($viewName = null, $isPartial = false) {
        if (!$this->viewRendered) {
            if ($viewName == null) {
                $viewName = $this->action;
            }

            if (!$isPartial) {
                include_once('views/layouts/' . $this->layout . '/header.php');
            }

            include_once('views/' . $this->controller . '/' . $viewName . '.php');

            if (!$isPartial) {
                include_once('views/layouts/' . $this->layout . '/footer.php');
            }

            $this->viewRendered = true;
        }
    }

    protected function redirectToUrl($url) {
        header("Location: $url");
        die;
    }

    protected function redirect($controller = null, $action = null, $params = []) {
        if ($controller == null) {
            $controller = $this->controller;
        }

        $url = "/$controller/$action";
        $paramsUrlEncoded = array_map('urlencode', $params);
        $paramsJoined = implode('/', $paramsUrlEncoded);
        if ($paramsJoined != '') {
            $url .= '/' . $paramsJoined;
        }

        $this->redirectToUrl($url);
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    private function addMessage($msgSessionkey, $msgText) {
        if (!isset($_SESSION[$msgSessionkey])) {
            $_SESSION[$msgSessionkey] = [];
        }

        array_push($_SESSION[$msgSessionkey], $msgText);
    }

    protected function addErrorMessage($errorMsg) {
        $this->addMessage(ERROR_MESSAGES_SESSION_KEY, $errorMsg);
    }

    protected function addInfoMessage($infoMsg) {
        $this->addMessage(INFO_MESSAGES_SESSION_KEY, $infoMsg);
    }

    protected function isLoggedIn() {
        return isset($_SESSION['username']);
    }

    protected function isAdmin() {
        return isset($_SESSION['isAdmin']);
    }

    protected function authorize() {
        if (! $this->isLoggedIn()) {
            $this->addErrorMessage("Please login first.");
            $this->redirect("accounts", "login");
        }
    }

    protected function authorizeAdmin() {
        if (! $this->isAdmin()) {
            $this->addErrorMessage("Not authorized.");
            $this->redirect("home", "index");
        }
    }

    protected function curentUserId() {
        return $_SESSION['userId'];
    }
}
