<?php
/* Avem nevoie de datele de conectare nu le putem pur si simplu hardcoda aici
 * pentru ca avem nevoie de ele in mai multe locuri si nu e normal sa le scriem de fiecare data */
require_once 'settings.inc.php';
class User{
    /* Setari */
    /**
     * Baza de date care va fi folosita (putem sa folosim baze de date diferite pentru diferite informatii asa ca nu suntem obligati sa avem aceleasi tabele peste tot)
     * var string
     */
    var $dbName = _DATABASE_NAME_;
    /**
     * Serverul pentru baza de date
     * var string
     */
    var $dbHost = _SERVER_NAME_;
    /**
     * Portul de conectare la baza de date in principiu nu se schimba mai niciodata dar pe serverul de test m-am lovit de faptul ca era schimbat asa ca este bine sa il avem ...
     * var int
     */
    var $dbPort = 3306;
    /**
     * Utilizatorul de conectare la baza de date
     * var string
     */
    var $dbUser = _DATABASE_USERNAME_;
    /**
     * Parola pentru baza de date
     * var string
     */
    var $dbPass = _DATABASE_PASSWORD_;
    /**
     * Tabelul in care sunt tinute informatiile despre utilizatori
     * var string
     */
    var $dbTable  = 'eb_users';
    /**
     * Numele variabilei de sesiune in care storam informatiile despre utilizatorul curent
     * var string
     */
    var $sessionVariable = 'userSessionValue';
    /**
     * Campuri din tabelul de utilizatori structura este de felul urmator "tip_camp" => "nume_camp"
     * var array
     */
    var $tbFields = array(
        'userID'=> 'id',
        'login' => 'username',
        'email' => 'email',
        'pass'  => 'password',
        'firstname' => 'firstname',
        'lastname'=> 'lastname',
        'active' => 'active',
        'admin' => 'admin',
    );
    /**
     * Timpul de retentie al informatiilor in cookie in caz ca utilizatorul doreste asta.
     * var int
     */
    var $remTime = 2592000;//O luna
    /**
     * Numele cookie-ului folosit
     * var string
     */
    var $remCookieName = 'ebetonPass';
    /**
     * Numele domeniului
     * var string
     */
    var $remCookieDomain = 'ebeton.ro';
    /**
     * The method used to encrypt the password. It can be sha1, md5 or nothing (no encryption)
     * var string
     */
    var $passMethod = 'sha1';
    /**
     * Display errors? Set this to true if you are going to seek for help, or have troubles with the script
     * var bool
     */
    var $errors = array();
    /** @var array */
    var $displayErrors = true;
    /*Do not edit after this line*/
    var $userID;
    var $dbConn;
    var $userData=array();
    /**
     * Class Constructure
     *
     * @param string $dbConn
     * @param array $settings
     * @return void
     */
    function flexibleAccess($dbConn = '', $settings = '')
    {
        if ( is_array($settings) ){
            foreach ( $settings as $k => $v ){
                if ( !isset( $this->{$k} ) ) die('Property '.$k.' does not exists. Check your settings.');
                $this->{$k} = $v;
            }
        }
        $this->remCookieDomain = $this->remCookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->remCookieDomain;
        $this->dbConn = ($dbConn=='')? mysql_connect($this->dbHost.':'.$this->dbPort, $this->dbUser, $this->dbPass):$dbConn;
        if ( !$this->dbConn ) die(mysql_error($this->dbConn));
        mysql_select_db($this->dbName, $this->dbConn)or die(mysql_error($this->dbConn));
        if( !isset( $_SESSION ) ) session_start();
        if ( !empty($_SESSION[$this->sessionVariable]) )
        {
            $this->loadUser( $_SESSION[$this->sessionVariable] );
        }
        //Maybe there is a cookie?
        if ( isset($_COOKIE[$this->remCookieName]) && !$this->is_loaded()){
            //echo 'I know you<br />';
            $u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
            $this->login($u['uname'], $u['password']);
        }
    }

    /**
     * Login function
     * @param string $uname
     * @param string $password
     * @param bool $loadUser
     * @return bool
     */
    function login($uname, $password, $remember = false, $loadUser = true)
    {
        $uname    = $this->escape($uname);
        $password = $originalPassword = $this->escape($password);
        switch(strtolower($this->passMethod)){
            case 'sha1':
                $password = "SHA1('$password')"; break;
            case 'md5' :
                $password = "MD5('$password')";break;
            case 'nothing':
                $password = "'$password'";
        }
        $res = $this->query("SELECT * FROM `{$this->dbTable}` 
		WHERE `{$this->tbFields['login']}` = '$uname' AND `{$this->tbFields['pass']}` = $password LIMIT 1",__LINE__);
        if ( @mysql_num_rows($res) == 0)
            return false;
        if ( $loadUser )
        {
            $this->userData = mysql_fetch_array($res);
            $this->userID = $this->userData[$this->tbFields['userID']];
            $_SESSION[$this->sessionVariable] = $this->userID;
            if ( $remember ){
                $cookie = base64_encode(serialize(array('uname'=>$uname,'password'=>$originalPassword)));
                $a = setcookie($this->remCookieName,
                    $cookie,time()+$this->remTime, '/', $this->remCookieDomain);
            }
        }
        return true;
    }

    /**
     * Logout function
     * param string $redirectTo
     * @return bool
     */
    function logout($redirectTo = '')
    {
        setcookie($this->remCookieName, '', time()-3600);
        $_SESSION[$this->sessionVariable] = '';
        $this->userData = '';
        if ( $redirectTo != '' && !headers_sent()){
            header('Location: '.$redirectTo );
            exit;//To ensure security
        }
    }
    /**
     * Function to determine if a property is true or false
     * param string $prop
     * @return bool
     */
    function is($prop){
        return $this->get_property($prop)==1?true:false;
    }

    /**
     * Get a property of a user. You should give here the name of the field that you seek from the user table
     * @param string $property
     * @return string
     */
    function get_property($property)
    {
        if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
        if (!isset($this->userData[$property])) $this->error('Unknown property <b>'.$property.'</b>', __LINE__);
        return $this->userData[$property];
    }
    /**
     * Is the user an active user?
     * @return bool
     */
    function is_active()
    {
        return $this->userData[$this->tbFields['active']];
    }

    /**
     * Is the user loaded?
     * @ return bool
     */
    function is_loaded()
    {
        return empty($this->userID) ? false : true;
    }
    /**
     * Activates the user account
     * @return bool
     */
    function activate()
    {
        if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
        if ( $this->is_active()) $this->error('Allready active account', __LINE__);
        $res = $this->query("UPDATE `{$this->dbTable}` SET {$this->tbFields['active']} = 1 
	WHERE `{$this->tbFields['userID']}` = '".$this->escape($this->userID)."' LIMIT 1");
        if (@mysql_affected_rows() == 1)
        {
            $this->userData[$this->tbFields['active']] = true;
            return true;
        }
        return false;
    }
    /*
     * Creates a user account. The array should have the form 'database field' => 'value'
     * @param array $data
     * return int
     */
    function insertUser($data){
        if (!is_array($data)) $this->error('Data is not an array', __LINE__);
        switch(strtolower($this->passMethod)){
            case 'sha1':
                $password = "SHA1('".$data[$this->tbFields['pass']]."')"; break;
            case 'md5' :
                $password = "MD5('".$data[$this->tbFields['pass']]."')";break;
            case 'nothing':
                $password = $data[$this->tbFields['pass']];
        }
        foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
        $data[$this->tbFields['pass']] = $password;
        $this->query("INSERT INTO `{$this->dbTable}` (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")");
        return (int)mysql_insert_id($this->dbConn);
    }
    /*
     * Creates a random password. You can use it to create a password or a hash for user activation
     * param int $length
     * param string $chrs
     * return string
     */
    function randomPass($length=10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm'){
        for($i = 0; $i < $length; $i++) {
            $pwd .= $chrs{mt_rand(0, strlen($chrs)-1)};
        }
        return $pwd;
    }
    ////////////////////////////////////////////
    // PRIVATE FUNCTIONS
    ////////////////////////////////////////////

    /**
     * SQL query function
     * @access private
     * @param string $sql
     * @return string
     */
    function query($sql, $line = 'Uknown')
    {
        //if (defined('DEVELOPMENT_MODE') ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
        $res = mysql_db_query($this->dbName, $sql, $this->dbConn);
        if ( !res )
            $this->error(mysql_error($this->dbConn), $line);
        return $res;
    }

    /**
     * A function that is used to load one user's data
     * @access private
     * @param string $userID
     * @return bool
     */
    function loadUser($userID)
    {
        $res = $this->query("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` = '".$this->escape($userID)."' LIMIT 1");
        if ( mysql_num_rows($res) == 0 )
            return false;
        $this->userData = mysql_fetch_array($res);
        $this->userID = $userID;
        $_SESSION[$this->sessionVariable] = $this->userID;
        return true;
    }

    /**
     * Produces the result of addslashes() with more safety
     * @access private
     * @param string $str
     * @return string
     */
    function escape($str) {
        $str = get_magic_quotes_gpc()?stripslashes($str):$str;
        $str = mysql_real_escape_string($str, $this->dbConn);
        return $str;
    }

    /**
     * Error holder for the class
     * @access private
     * @param string $error
     * @param int $line
     * @param bool $die
     * @return bool
     */
    function error($error, $line = '', $die = false) {
        if ( $this->displayErrors )
            echo '<b>Error: </b>'.$error.'<br /><b>Line: </b>'.($line==''?'Unknown':$line).'<br />';
        if ($die) exit;
        return false;
    }

    function validateUsername($string){
        $this->errors['username'] = array();
        if(strlen(trim($string)) > 0){

        } else {

        }
    }
}
