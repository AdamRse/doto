<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("session.use_cookies", 1);
session_start();

function interpreterListe($str, $msql, $table, $idStrat){
    $retour = true;
    if(!empty($str)){
        $sql="INSERT INTO $table SET id_strats = $idStrat";
        $col=($table=="strats_items")?"id_item":"hero";
        $ex1=explode(";", $str, 2);
        //var_dump($table, $ex1);
        if(!empty($ex1[0])){
            $ex2= explode("-", $ex1[0]);
            //var_dump($ex2);
            foreach ($ex2 as $value) {
                if(!empty($value)){
                    //echo $sql.", $col = $value, obligatoire = 1\n";
                    $r=$msql->prepare($sql.", $col = ?, obligatoire = 1");
                    $r->bind_param('i', $value);
                    if(!$r->execute()){
                        echo $msql->error.'\n';
                        $retour=false;
                    }
                }
            }
        }
        if(!empty($ex1[1])){
            $ex2= explode("-", $ex1[1]);
            //var_dump($ex2);
            foreach ($ex2 as $value) {
                if(!empty($value)){
                    $r=$msql->prepare($sql.", $col = ?, obligatoire = 0");
                    $r->bind_param('i', $value);
                    if(!$r->execute()){
                        echo $msql->error.'\n';
                        $retour=false;
                    }
                }
            }
        }
    }
   return $retour;
}
function verifInjectHTML($txt){
    return preg_replace("/(<iframe.+iframe *>)|(<meta.+\>)|(<a.+a *>)|(<script.+script *>)|(<html.+html *>)|(<body.+body *>)/", "",$txt);
}

if(!empty($_SERVER['HTTP_SEC_FETCH_SITE'])&& $_SERVER['HTTP_SEC_FETCH_SITE'] == "same-origin" && !empty($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE']=="cors"){
    if(!empty($_POST['titre'])){
        if(!empty($_POST['desc'])){
            if(!empty($_POST['picks']) && $_POST['picks']!=";"){
                $msqli=new mysqli("localhost", "raspi", "", "dev_doto");
                $titre = htmlspecialchars($_POST['titre']);
                $desc = nl2br(verifInjectHTML($_POST['desc']));
                $ft=nl2br(verifInjectHTML($_POST['ft']));
                $fs=nl2br(verifInjectHTML($_POST['fs']));
                
                $sql=(empty($_POST['id']))?"INSERT INTO strats (nom, description, forces_timings, faiblesses_solutions) VALUES (?, ?, ?, ?)":"UPDATE strats SET nom = ?, description = ?, forces_timings = ?, faiblesses_solutions = ? WHERE id = ?";
                $rNewStrat=$msqli->prepare($sql);
                if(!empty($_POST['id'])){ $rNewStrat->bind_param('ssssi', $titre, $desc, $ft, $fs, $_POST['id']); }
                else { $rNewStrat->bind_param('ssss', $titre, $desc, $ft, $fs); }
                if($rNewStrat->execute()){
                    if(!empty($_POST['id'])){
                        $msqli->query("DELETE FROM strats_pick WHERE id_strats = ".$_POST['id']);
                        $msqli->query("DELETE FROM strats_ban WHERE id_strats = ".$_POST['id']);
                        $msqli->query("DELETE FROM strats_items WHERE id_strats = ".$_POST['id']);
                        $idBddStrat=$_POST['id'];
                    }
                    else{ $idBddStrat=$msqli->insert_id; }
                    if(interpreterListe($_POST['picks'], $msqli, "strats_pick", $idBddStrat) &&
                    interpreterListe($_POST['bans'], $msqli, "strats_ban", $idBddStrat) &&
                    interpreterListe($_POST['items'], $msqli, "strats_items", $idBddStrat)){
                        echo "1";
                    }
                    else{
                        $msqli->query("DELETE FROM strats WHERE id = $idBddStrat");
                        $msqli->query("DELETE FROM strats_pick WHERE strats_id = $idBddStrat");
                        $msqli->query("DELETE FROM strats_ban WHERE strats_id = $idBddStrat");
                        $msqli->query("DELETE FROM strats_items WHERE strats_id = $idBddStrat");
                    }
                }
                else
                    echo "Erreur d'écriture en base de données.\n".$msqli->error;
            }
            else
                echo "Impossible de créer une strat sans pick";
        }
        else
            echo "La description est vide";
    }
    else
        echo 'Le titre est vide';
}
else
    echo "service indisponnible";