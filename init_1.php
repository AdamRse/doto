<?php
ini_set("session.use_cookies", 1);
session_start();
include_once 'fct.php';

$msqli=new mysqli("localhost", "raspi", "", "dev_doto");

if(!isset($_SESSION['membre'])){
    $sessionCheck=$msqli->query("SELECT * FROM membres WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND user_agent = '".$_SERVER['HTTP_USER_AGENT']."'");
    if($sessionCheck->num_rows==1){
        $row=$sessionCheck->fetch_assoc();
        if(!empty($row['nom'])){
            $_SESSION['membre']=$row;
            $_SESSION['coach']=($row['pos1']=="6")?true:false;
        }
    }
    else
        $_SESSION['membre']=false;
}

define("membre", $_SESSION['membre']);
define("coach", !empty($_SESSION['coach']));