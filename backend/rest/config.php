<?php

ini_set('display_errore', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config{
    public static function DB_NAME(){
        return Config::get_env("DB_NAME", "");
    }

    public static function DB_PORT(){
        return Config::get_env("DB_PORT", "");
    }

    public static function DB_USER(){
        return Config::get_env("DB_USER", "");
    }

    public static function DB_PASSWORD(){
        return Config::get_env("DB_PASSWORD", "");
    }

    public static function DB_HOST(){
        return Config::get_env("DB_HOST", "");
    }

    public static function JWT_SECRET(){
        return Config::get_env("JWT_SECRET", "enis");
    }

    public static function get_env($name, $default){
        return isset($_ENV[$name]) && trim($_ENV[$name]) != "" ? $_ENV[$name] : $default;
    }
}
