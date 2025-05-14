<?php

/**
 * Authentication class
 */
class Auth
{
    public static function authenticate($row)
    {
        $_SESSION['DONPUBUSER'] = $row;
    }

    public static function logout()
    {
        if (isset($_SESSION['DONPUBUSER'])) {
            if (isset($_SESSION['seasondata'])) {
                unset($_SESSION['seasondata']);
            }
            unset($_SESSION['DONPUBUSER']);
            session_unset();
            session_destroy();
        }
    }

    public static function logged_in()
    {
        if (isset($_SESSION['DONPUBUSER'])) {
            return true;
        }
        return false;
    }
    public static function user()
    {
        if (isset($_SESSION['DONPUBUSER'])) {
            return $_SESSION['DONPUBUSER']->firstname;
        }
        return false;
    }

    public static function get_image()
    {
        if (isset($_SESSION['DONPUBUSER'])) {
            return $_SESSION['DONPUBUSER']->imagelink;
        }
        return false;
    }

    public static function comparepass()
    {
        //$user = password_hash($_SESSION['DONPUBUSER']->username, PASSWORD_DEFAULT);
        if (password_verify($_SESSION['DONPUBUSER']->username, $_SESSION['DONPUBUSER']->password)) {
            return true;
        }
        return false;
    }

    public static function __callStatic($method, $params)
    {
        $prop = strtolower(str_replace("get", "", $method));
        if (isset($_SESSION['DONPUBUSER']->$prop)) {
            return $_SESSION['DONPUBUSER']->$prop;
        }
        return "Unknown";
    }

    public static function access($rank = 'marketer')
    {
        if (!isset($_SESSION['DONPUBUSER'])) {
            return false;
        }
        $logged_in_rank = $_SESSION['DONPUBUSER']->rank; //the rank for current user
        $RANK['director']      = ['director', 'g-account', 'account', 'verification', 'stores', 'marketer'];
        $RANK['g-account']      = ['g-account', 'account', 'verification', 'stores', 'marketer', 'marketer'];
        $RANK['auditor']      = ['g-account', 'account', 'verification', 'stores', 'marketer'];
        $RANK['account']      = ['account', 'verification', 'stores'];
        $RANK['verification']      = ['verification'];
        $RANK['stores']      = ['stores'];
        $RANK['marketer']      = ['marketer'];

        if (!isset($RANK[$logged_in_rank])) {
            return false;
        }

        if (in_array($rank, $RANK[$logged_in_rank])) {
            return true;
        }
        return false;
    }
}
