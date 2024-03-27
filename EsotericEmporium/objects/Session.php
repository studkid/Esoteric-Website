<?php

class Session
{
    protected $username;
    protected $role;
    protected $hash;

    public function __construct()
    {
        session_start();
        $this->username = $_SESSION['username'] ?? '';
        $this->role = $_SESSION['role'] ?? 'Visitor';
        $this->hash = $_SESSION['hash'] ?? "";
    }

    public function create( $username, $role )
    {
        session_regenerate_id(true);
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
    }

    public function shutDown()
    {
        $_SESSION = [];
        $params = session_get_cookie_params();
        setcookie( session_name(), '', time()-2400, $params['path'], $params['domain'], $params['secure'], $params['httponly'] );
        session_destroy();
    }
}
?>