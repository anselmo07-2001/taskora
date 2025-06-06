<?php

namespace App\Support;

class SessionService {
     
    public static function startSessionIfNotStarted() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function setSessionValue(string $key, mixed $value) {
        self::startSessionIfNotStarted();
        $_SESSION[$key] = $value;
        session_regenerate_id(true);
    }


    public static function getSessionKey(string $key, mixed $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }


    public static function removeAllSessionData() {
        self::startSessionIfNotStarted();
        session_regenerate_id(true);
        session_unset();
        session_destroy();
    }



    public static function setAlertMessage(string $status, string $message) {
        self::startSessionIfNotStarted();
        $_SESSION[$status] = $message;
    }

    public static function getAlertMessage(string $status) {
        $message = self::getSessionKey($status);
        
        if (isset($_SESSION[$status])) {
            unset($_SESSION[$status]);
        }

        return $message;
    }
}