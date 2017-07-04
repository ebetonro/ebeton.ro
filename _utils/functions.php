<?php
/**
 * Created by PhpStorm.
 * User: Cristian
 * Date: 5/3/2017
 * Time: 12:23 AM
 */

require_once ('settings.inc.php');
require_once ('template_settings.php');

/* Hope that one day we will use multiple servers ... at the moment nu prea avem de ce*/
try {
    $conn = new PDO("mysql:host="._SERVER_NAME_.";dbname="._DATABASE_NAME_, _DATABASE_USERNAME_, _DATABASE_PASSWORD_);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Conexiunea cu baza de date a esuat: " . $e->getMessage();
}


function verify_user_login($username, $password){

}
