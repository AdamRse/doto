<?php
ini_set("session.use_cookies", 1);
session_start();
if(!empty($_SERVER['HTTP_SEC_FETCH_SITE'])&& $_SERVER['HTTP_SEC_FETCH_SITE'] == "same-origin" && !empty($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE']=="cors"){
    $retour = false;
    if(!empty($_SESSION['membre']['nom'])){
        $retour = array();
        $msqli=new mysqli("localhost", "raspi", "", "doto");
        $r = $msqli->query("SELECT * FROM hero_pool WHERE id_membres = '".$_SESSION['membre']['id']."'");
        while($row = $r->fetch_assoc()){
            $retour[]=array("hero" => $row['hero'], "comfortable" => $row['comfortable']);
        }
    }
    echo json_encode($retour);
}
else
    echo 'Service indisponnible';