<?php

/**
 * Description of UserSession
 *
 * @author juanjo
 */
class SessionHelper {

    /*admin username, cleartext*/
    public static $adminUser = "administrator";
    /*admin password, password_hash(cleartext,  PASSWORD_BCRYPT)*/
    public static $adminPassword = '$2y$10$D7FE2iJ8F7pPgz5rDerap.Mpm6dSFcpoWd2Pg299q6g7kxCSKHcwy';
    //Cookie name for the session, overwriting "PHPSESSID"
    private static $_SessionName = "nulluploadsession";
    //Where to store the usersession object in the session
    private static $_SessionVar = "userobject";

    /* session lifetime */
    public static $maxSessionLifetime = 48 * 60 * 60;

    public static function session_start() {
        session_name(self::$_SessionName);
        session_start();
    }

    public static function init() {

        ini_set("session.gc_maxlifetime", self::$maxSessionLifetime);
        ini_set("session.use_strict_mode", "1");
        ini_set("session.cookie_httponly", "1");
        ini_set("session.cookie_lifetime", self::$maxSessionLifetime);
        ini_set("session.sid_bits_per_character", "5");
        ini_set("session.sid_length", "32");
        ini_set("session.cookie_samesite", "Strict");

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
            ini_set("session.cookie_secure", "1");
        } else {
            ini_set("session.cookie_secure", "0");
        }
    }

    /**
     * 
     * @param int $userid User id
     * @param int $lifetime Time in seconds for the session cookie
     * @param boolean $secure Secure cookie flag (only httpS connections can access this cookie)
     * @return \session
     */
    public static function newAuthSession($userid, $lifetime = null, $secure = false) {

        //session_set_cookie_params($lifetime, null, null, $secure, true);
        //session_write_close(); //with this regenerate works
        session_regenerate_id(true);

        $session = new session(self::_getClientIP(), self::_getClientUserAgent(), $userid, time());

        $_SESSION[self::$_SessionVar] = $session;

        return $session;
    }

    public static function setAdminSession($isAdmin) {
        if ($isAdmin) {
            $_SESSION['isAdmin'] = "yeah";
        } else {
            $_SESSION['isAdmin'] = null;
            unset($_SESSION['isAdmin']);
        }
    }

    public static function isAdminSession() {
        if (self::isActiveSession() && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] != null && $_SESSION['isAdmin'] == "yeah") {
            return true;
        }
        return false;
    }

    /** @param session $session Session object after login * */
    public static function setSession($session) {
        if ($session instanceof session) {
            $_SESSION[self::$_SessionVar] = $session;
        }
    }

    /** @return session Return the session object * */
    public static function getSession() {
        return $_SESSION[self::$_SessionVar];
    }

    public static function deleteSession() {
        unset($_SESSION[self::$_SessionVar]);
        session_unset();
        session_destroy();
    }

    /**
     * 
     * @param boolean $checkIP default=false
     * @param boolean $checkUserAgent default=true
     * @param boolean $checkExpirationTime default=true
     * @return boolean
     */
    public static function isActiveSession($checkIP = false, $checkUserAgent = true, $checkExpirationTime = true) {
        if (isset($_SESSION[self::$_SessionVar]) && !empty($_SESSION[self::$_SessionVar]) && $_SESSION[self::$_SessionVar] instanceof session) {

            if ($checkIP) {
                if (self::getSession()->get_ip() != self::_getClientIP()) {
                    return false;
                }
            }

            if ($checkUserAgent) {
                if (self::getSession()->get_useragent() != self::_getClientUserAgent()) {
                    return false;
                }
            }

            if ($checkExpirationTime) {
                if (time() > (self::getSession()->get_created() + self::$maxSessionLifetime)) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

//    /** @return string Returns the client IP* */
//    private static function _getClientIP() {
//
//        //Cloudflare compatibility
//        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
//            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
//        }
//
//        $ipaddress = '';
//        if (getenv('HTTP_CLIENT_IP'))
//            $ipaddress = getenv('HTTP_CLIENT_IP');
//        else if (getenv('HTTP_X_FORWARDED_FOR'))
//            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
//        else if (getenv('HTTP_X_FORWARDED'))
//            $ipaddress = getenv('HTTP_X_FORWARDED');
//        else if (getenv('HTTP_FORWARDED_FOR'))
//            $ipaddress = getenv('HTTP_FORWARDED_FOR');
//        else if (getenv('HTTP_FORWARDED'))
//            $ipaddress = getenv('HTTP_FORWARDED');
//        else if (getenv('REMOTE_ADDR'))
//            $ipaddress = getenv('REMOTE_ADDR');
//        else
//            $ipaddress = 'UNKNOWN';
//        return $ipaddress;
//    }

    /** @return string Returns the client IP* */
    private static function _getClientIP() {

        return $_SERVER['REMOTE_ADDR'];
    }

    /** @return string Returns the client User Agent * */
    private static function _getClientUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

}

class session {

    private $_ip; //Client IP
    private $_useragent; //Client UserAgent
    private $_userid; //User id
    private $_created; //time() when was created the session

    /**
     * 
     * @param type $_ip
     * @param type $_useragent
     * @param type $_userid
     * @param type $_created
     */

    function __construct($_ip, $_useragent, $_userid, $_created) {
        $this->_ip = $_ip;
        $this->_useragent = $_useragent;
        $this->_userid = $_userid;
        $this->_created = $_created;
    }

    function get_ip() {
        return $this->_ip;
    }

    function get_useragent() {
        return $this->_useragent;
    }

    function get_userid() {
        return $this->_userid;
    }

    function get_created() {
        return $this->_created;
    }

    function set_ip($_ip) {
        $this->_ip = $_ip;
    }

    function set_useragent($_useragent) {
        $this->_useragent = $_useragent;
    }

    function set_userid($_userid) {
        $this->_userid = $_userid;
    }

    function set_created($_created) {
        $this->_created = $_created;
    }

}
