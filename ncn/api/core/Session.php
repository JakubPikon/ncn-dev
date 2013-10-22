<?php
 
class Session {

    private $_sessionStarted = false;

    public function start() {
        if ($this->_sessionStarted) {
            return; // already started
        }

        if (headers_sent($filename, $linenum)) {
            throw new Exception('Session must be started before any output has been sent to the browser; output started in {' . $filename . '}/{' . $linenum . '}');
        }

        if (defined('SID')) {
            throw new Exception('Session has already been started by session.auto-start or session_start()');
        }

        // destroy session after closing the browser window
        session_set_cookie_params(0);

        session_start();

        $expTokenName = '6LA_Dfg47';
        if (!isset($_COOKIE[$expTokenName])) {
            $_SESSION = array();
            session_destroy();

            //regenerate session for new user
            session_start();
            session_regenerate_id(true);
        }

        // regenerate expiration cookie
        $time = time();
        setcookie($expTokenName, $time, $time + SESSION_EXPIRATION_TIME, '/');
        $this->_sessionStarted = true;
    }

    public function regenerate() {
        session_regenerate_id(true);
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function pop($key) {
        $value = null;
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
        }
        return $value;
    }

    public function remove($key) {
        if (isset($_SESSION[$key])) unset($_SESSION[$key]);
    }

    public function close() {
        if ($this->_sessionStarted) {
            session_write_close();
            $this->_sessionStarted = false;
        }
    }
}
