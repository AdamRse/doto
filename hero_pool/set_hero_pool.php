<?php
ini_set("session.use_cookies", 1);
session_start();

if(!empty($_SERVER['HTTP_SEC_FETCH_SITE'])&& $_SERVER['HTTP_SEC_FETCH_SITE'] == "same-origin" && !empty($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE']=="cors"){
    $retour = false;
    if(!empty($_SESSION['membre']['id']) && !empty($_GET['hero']) && isset($_GET['val'])){
        $retour = true;
        $sql="SELECT id FROM membres WHERE id_membres = '".$_SESSION['membre']['id']."'";
        $msqli=new mysqli("localhost", "raspi", "", "doto");
        $check=$msqli->query("SELECT id_membres FROM hero_pool WHERE id_membres = '".$_SESSION['membre']['id']."' AND hero = ".$_GET['hero']);
        $update = ($check->num_rows>0)?true:false;
        if($_GET['val']==2){
            if($update)
                $sql="UPDATE hero_pool SET comfortable = '1' WHERE id_membres = '".$_SESSION['membre']['id']."' AND hero = ".$_GET['hero'];
            else
                $sql="INSERT INTO hero_pool (id_membres, hero, comfortable) VALUES ('".$_SESSION['membre']['id']."', '".$_GET['hero']."', '1')";
        }
        elseif($_GET['val']==1){
            if($update)
                $sql="UPDATE hero_pool SET comfortable = '0' WHERE id_membres = '".$_SESSION['membre']['id']."' AND hero = ".$_GET['hero'];
            else
                $sql="INSERT INTO hero_pool (id_membres, hero, comfortable) VALUES ('".$_SESSION['membre']['id']."', '".$_GET['hero']."', '0')";
        }
        elseif ($_GET['val']==0){
            $sql="DELETE FROM hero_pool WHERE id_membres = '".$_SESSION['membre']['id']."' AND hero = ".$_GET['hero'];
        }
        if(!$msqli->query($sql))
            $retour = "Erreur de requete : $sql";
    }
    echo $retour;
}
else
    echo "Service indisponnible";