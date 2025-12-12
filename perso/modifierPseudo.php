<?php
ini_set("session.use_cookies", 1);
session_start();

if(!empty($_SERVER['HTTP_SEC_FETCH_SITE'])&& $_SERVER['HTTP_SEC_FETCH_SITE'] == "same-origin" && !empty($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE']=="cors"){
    if(!empty($_GET['ps'])){
        $msqli=new mysqli("localhost", "raspi", "", "doto");
        if($msqli->query("UPDATE membres SET nom = '".$_GET['ps']."' WHERE id = ".$_SESSION['membre']['id'])){
            $_SESSION['membre']['nom']=$_GET['ps'];
            echo "1";
        }
        else
            echo "3";
    }
    else
        echo "2";
}
else
    echo "service indisponnible";