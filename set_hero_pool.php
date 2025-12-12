<?php
$retour = false;
if(!empty($_GET['nom']) && !empty($_GET['hero']) && isset($_GET['val'])){
    $retour = true;
    $sql="SELECT nom FROM membres WHERE nom = '".$_GET['nom']."'";
    $msqli=new mysqli("localhost", "raspi", "", "doto");
    $check=$msqli->query("SELECT nom FROM hero_pool WHERE nom = '".$_GET['nom']."' AND hero = ".$_GET['hero']);
    $update = ($check->fetch_assoc()["nom"]==$_GET['nom'])?true:false;
    if($_GET['val']==2){
        if($update)
            $sql="UPDATE hero_pool SET comfortable = '1' WHERE nom = '".$_GET['nom']."' AND hero = ".$_GET['hero'];
        else
            $sql="INSERT INTO hero_pool (nom, hero, comfortable) VALUES ('".$_GET['nom']."', '".$_GET['hero']."', '1')";
    }
    elseif($_GET['val']==1){
        if($update)
            $sql="UPDATE hero_pool SET comfortable = '0' WHERE nom = '".$_GET['nom']."' AND hero = ".$_GET['hero'];
        else
            $sql="INSERT INTO hero_pool (nom, hero, comfortable) VALUES ('".$_GET['nom']."', '".$_GET['hero']."', '0')";
    }
    elseif ($_GET['val']==0){
        $sql="DELETE FROM hero_pool WHERE nom = '".$_GET['nom']."' AND hero = ".$_GET['hero'];
    }
    if(!$msqli->query($sql))
        $retour = "Erreur de requête : $sql";
}
echo $retour;