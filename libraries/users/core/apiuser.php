<?php

class ApiUser extends User {
    private $_apiKey;
    private $_authenticationKey;
    private $_headers;

    public function __construct() {
        $this->_headers = getallheaders();

        parent::__construct();

        if(!$this->_loggedIn) {
            if( $this->isAuthenticatedWithApiKey()){
                $this->LoginViaApiKey($this->_headers['api-key'], $this->_headers['auth-key']);
            }
        }
    }

    protected function isAuthenticatedWithApiKey() {
        return isset($this->_headers['api-key']) && isset( $this->_headers['auth-key']);
    }

    protected function loginViaApiKey($apiKey, $authKey) {
        $authenticationResult = $this->_database->ExecuteQuery(
            "SELECT * FROM Users WHERE ApiKey = ? AND LastAuthId = ?",
            $apiKey, $authKey);

        $user = $authenticationResult->firstOrDefault();

        if( $user != null) {
            $this->_model = new UserDTO($user->Id, $user->Login, $user->Password, $user->Role);
            $this->_role = $user->Role;
            $this->_id = $user->Id;
            $this->_login = $user->Login;
            $this->_loggedIn = true;
            $this->_apiKey = $user->ApiKey;
            $this->_authenticationKey = $authKey;
        }
    }

    public function Login($username, $password = "") {
        if( strlen($username) && isset($this->_headers['api-request'])) {
            $userQuery = $this->_database->ExecuteQuery(
                "SELECT * FROM Users WHERE Login = ? AND Password = PASSWORD(?)",
                $username, $password);

            $user = $userQuery->firstOrDefault();

            if( $user != null && $user->Active) {
                $this->_model = new UserDTO($user->Id, $user->Login, $user->Role);

                $this->_role = $user->Role;
                $this->_id = $user->Id;
                $this->_login = $user->Login;
                $this->_loggedIn = true;

                if( !isset( $user->ApiKey))
                    $user->ApiKey = Helper::get_random_string("123456790abcde", 32);

                $lastAuthId = Helper::get_random_string("1234567890abcdef", 32);

                $this->_database->ExecuteQuery("UPDATE Users SET LastAuthId = ?, LastLogin = NOW(), ApiKey = ? WHERE Id = ?",
                    $lastAuthId, $user->ApiKey, $user->Id
                );

                $this->_apiKey = $user->ApiKey;
                $this->_authenticationKey = $lastAuthId;

                return $this->_loggedIn;
            }
        }

        return parent::Login($username, $password);
    }

    public function ApiKey () {
        return $this->_apiKey;
    }

    public function Authentication() {
        return $this->_authenticationKey;
    }
}