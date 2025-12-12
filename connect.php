<?php
ini_set("session.use_cookies", 1);
session_start();
$limiteRqParMin=3;
$now=time();

if(!empty($_SERVER['HTTP_SEC_FETCH_SITE'])&& $_SERVER['HTTP_SEC_FETCH_SITE'] == "same-origin" && !empty($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE']=="cors"){
    if(!empty($_GET['id'])){
        if(!isset($_SESSION['last_limit']) || $_SESSION['last_limit']-$now>600){
            $clean=true;
            if(!empty($_SESSION['last'])){
                $c=0;
                $_SESSION['last'][]=$now;
                foreach($_SESSION['last'] as $v){
                    if(date("m", $v) == date("m", $now)) $c++;
                }
                if($c>=$limiteRqParMin){
                    $clean=false;
                    if(!isset($_SESSION['last_limit'])) $_SESSION['last_limit']=$now;
                }
            }
            else
                $_SESSION['last']=array($now);

            if($clean){
                if(strlen($_GET['id'])==5){
                    if(preg_match("/[0-9][0-9][0-9][a-z][a-z]/i", $_GET['id'])===1){
                        $msqli=new mysqli("localhost", "raspi", "", "doto");
                        $r = $msqli->query("SELECT * FROM membres WHERE code = '".$_GET['id']."'");
                        $membre=$r->fetch_assoc();
                        if(!empty($membre)){
                            $_SESSION['membre']=$membre;
                            $_SESSION['coach']=($membre['pos1']=="6")?true:false;
                            if(isset($_SESSION['last'])) unset($_SESSION['last']);
                            if(isset($_SESSION['last_limit'])) unset($_SESSION['last_limit']);
                            echo '1';
                            
                            $bddCo=$msqli->query("SELECT nom FROM membres WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND user_agent = '".$_SERVER['HTTP_USER_AGENT']."'");
                            if($bddCo->num_rows==0)
                                $msqli->query("UPDATE membres SET ip = '".$_SERVER['REMOTE_ADDR']."', user_agent = '".$_SERVER['HTTP_USER_AGENT']."' WHERE nom = '".$membre['nom']."'");
                            
                            $msqli2=new mysqli("localhost", "raspi", "", "raspi_general");
                            $list=$msqli2->query("SELECT ip, user_agent FROM liste_nolog_acces WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND user_agent = '".$_SERVER['HTTP_USER_AGENT']."'");
                            if($list->num_rows==0)
                                $msqli2->query("INSERT INTO liste_nolog_acces (ip, user_agent) VALUES('".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."')");
                            
                        }
                        else
                            echo "7";
                    }
                    else
                        echo "4";
                }
                else
                    echo "3";
            }
            else
                echo "5";
        }
        else
            echo "6";
    }
    else
        echo "2";
}
else
    echo "Service indisponnible.";