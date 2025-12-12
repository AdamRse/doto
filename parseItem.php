<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$autorisation_strpit=false;
$cheminFichierItem="./items";
$nomDossierImgLocal="./Items - Dota 2 Wiki_fichiers";

if($autorisation_strpit){
    $tabItems=array();
    if(file_exists($cheminFichierItem)){
        if(file_exists($nomDossierImgLocal)){
            $h= fopen($cheminFichierItem, "r");
            $categorie=""; $type; $item=array();
            $ignoreLeReste=false;
            while(($ligne = fgets($h, 4096)) !== false) {
                if(!$ignoreLeReste){
                    if(substr($ligne, 0, 11)=="[Categorie]"){
                        //echo "categorie détectée $ligne<br/>";
                        $oldCat=$categorie;
                        $categorie=trim(str_replace("[Categorie]", "", $ligne));
                        //echo "categorie = $categorie<br/>";
                        if(substr($categorie, 0, 1)=="<" && !empty($item)){
                            $ignoreLeReste=true;
                            $tabItems[$oldCat][$type][]=$item;
                        }
                    }
                    elseif(substr($ligne, 0, 7)=="[type] "){
                        //echo "&nbsp; Type détecté $ligne<br/>";
                        $type=trim(str_replace("[type] ", "", $ligne));
                        //echo "&nbsp; &nbsp; type = $type<br/>";
                    }
                    elseif(trim($ligne)=="-Item-"){
                        if(!empty($item)){
                        //echo "&nbsp; &nbsp; &nbsp; &nbsp; (ITEM CHARGE : [$categorie][$type])<br/>";
                            $tabItems[$categorie][$type][]=$item;
                        }
                        $item=array();
                    }
                    else{
                        //echo "&nbsp; &nbsp; &nbsp; &nbsp; remplissage de l'item : $ligne<br/>";
                        $ligne=trim($ligne);
                        if(empty($item)){ //c'est l'image
                            $item[]=$ligne;
                            if(file_exists("$nomDossierImgLocal/$ligne")){
                                if(!file_exists("./img/items/$ligne")){
                                    if($contentOrFalseOnFailure = file_get_contents("$nomDossierImgLocal/$ligne")){
                                        if(!file_put_contents("./img/items/$ligne", $contentOrFalseOnFailure)){
                                            echo "<p>Impossible de copier l'image ($nomDossierImgLocal/$ligne)</p>";
                                        }
                                    }
                                    else{
                                        echo "<p>Impossible de copier l'image ($nomDossierImgLocal/$ligne)</p>";
                                    }
                                }
                            }
                            else{
                                echo "<p>IMAGE MANQUANTE ($nomDossierImgLocal/$ligne)</p>";
                            }
                        }
                        else{
                            $item[]=($ligne=="undefine")?0:$ligne;
                        }
                    }
                }
            }
            fclose($h);

            $msqli=new mysqli("localhost", "raspi", "", "doto");
            foreach ($tabItems as $categorie => $t1) {
                foreach ($t1 as $type => $items){
                    foreach ($items as $item){
                        var_dump($item);
                        $msqli->query("INSERT INTO item_list (nom, categorie, type, golds, img) VALUES('".$item[2]."', '$categorie', '$type', '".$item[1]."', '".$item[0]."')");
                    }
                }
            }
        }
        else
            echo 'Impossible de trouver le dossier contenant les images des items';
    }
    else
        echo "impossible de trouver le fichier contenant la liste des items.";
}
else
    echo "sécurité, donner l'autorisation au script en dur (ligne 6)";