<?php
/**
 * Created by PhpStorm.
 * User: Cristian
 * Date: 5/7/2017
 * Time: 11:29 PM
 */

require_once "functions.php";
global $conn;
$allowed_actions = array('checkUsernameExists'); //daca actiunea nu este aici atunci va murii
if(isset($_POST['action']) && in_array($_POST['action'], $allowed_actions)){
    if($_POST['action'] == 'checkUsernameExists'){
        if(isset($_POST['value']) && strlen(trim($_POST['value'])) > 0 && isset($_POST['field']) && strlen(trim($_POST['field'])) > 0){
            //cand verificam utilizatorul putem sa o facem dupa 3 capuri: id, utilizator, email
            $allowed_fields = array('id', 'username', 'email');
            if(in_array($_POST['field'],$allowed_fields)){
                $sql = "SELECT * FROM eb_users WHERE `".$_POST['field']."` = :value";
                $stmt = $conn->prepare($sql);
                $stmt->execute(array(":value" => $_POST['value']));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if($result){
                    echo 1;
                } else{
                    echo 0;
                }
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }
} else{
    die('This action is not allowed. Killing all requests.');
}
die();