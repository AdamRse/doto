<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../init.php";
if(membre){
    $rStrats = $msqli->query("SELECT * FROM strats");
    $strats=array();
    if($rStrats->num_rows>0){
        while($qStrat=$rStrats->fetch_assoc()){
            $strats[$qStrat['id']]=array("nom" => $qStrat['nom'], "forces_timings" => $qStrat['forces_timings']
                    , "faiblesses_solutions" => $qStrat['faiblesses_solutions'], "description" => $qStrat['description']
                    , "ft" => $qStrat['forces_timings'], "control" => $qStrat['control'], "massive" => $qStrat['massive']
                    , "nb_games" => $qStrat['nb_games'], "nb_victoires" => $qStrat['nb_victoires']
                    , "dt_creation" => $qStrat['dt_creation']);
            $rPicks=$msqli->query("SELECT hero, obligatoire, commentaire FROM strats_pick WHERE id_strats = ".$qStrat['id']);
            if($rPicks->num_rows>0){
                while($rowPicks=$rPicks->fetch_assoc()){
                    $strats[$qStrat['id']]["picks"][$rowPicks['hero']]=array("oblige" => $rowPicks['obligatoire'], "commentaire" => $rowPicks['commentaire']);
                }
            }
            $rBans=$msqli->query("SELECT hero, obligatoire, commentaire FROM strats_ban WHERE id_strats = ".$qStrat['id']);
            if($rBans->num_rows>0){
                while($rowBans=$rBans->fetch_assoc()){
                    $strats[$qStrat['id']]["bans"][$rowBans['hero']]=array("oblige" => $rowBans['obligatoire'], "commentaire" => $rowBans['commentaire']);
                }
            }
            $rIrems=$msqli->query("SELECT id_item, obligatoire, commentaire FROM strats_items WHERE id_strats = ".$qStrat['id']);
            if($rIrems->num_rows>0){
                while($rowItems=$rIrems->fetch_assoc()){
                    $strats[$qStrat['id']]["items"][$rowItems['id_item']]=array("oblige" => $rowItems['obligatoire'], "commentaire" => $rowItems['commentaire']);
                }
            }
            $rIrems=$msqli->query("SELECT id_item, obligatoire, commentaire FROM strats_items WHERE id_strats = ".$qStrat['id']);
            if($rIrems->num_rows>0){
                while($rowItems=$rIrems->fetch_assoc()){
                    $strats[$qStrat['id']]["items"][$rowItems['id_item']]=array("oblige" => $rowItems['obligatoire'], "commentaire" => $rowItems['commentaire']);
                }
            }
        }
    }
    
    $rItems = $msqli->query("SELECT * FROM item_list WHERE (categorie = 'Upgraded Items' OR id IN (50,51,253)) AND id NOT IN (67,68,69,74,75,76,83,84,114) ORDER BY nom ASC;");
    $items = array();
    while($row=$rItems->fetch_assoc()){
        $id = $row['id'];
        unset($row['id']);
        $items[$id]=$row;
    }
    $rHeros = $msqli->query("SELECT * FROM hero_list");
    $heros = array();
    while($row=$rHeros->fetch_assoc()){
        $id = $row['id'];
        unset($row['id']);
        $heros[$id]=$row;
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="cache_control" content="no-cache" />
    <title>Strats</title>
    <link rel="stylesheet" href="css.css">
    <link rel="icon" type="image/png" href="../icon.png" />
</head>
<body>
    <h1>Élaboration des strats</h1>
    <button id="btNewStrat" onclick="document.getElementById('newStrat').classList.toggle('hidden')">Proposer un nouvelle strat</button>
    <div id="listeStrats">
        <div id="newStrat" class="dStrat newStrat hidden">
            <div class="bandeau" style="cursor: default">
                <div class="indic">↓</div>
                <div class="titre"><input class="nsTitre" type="text" placeholder="Titre de la strat"/></div>
                <div class="desc"><textarea class="nsDesc" rows="2" placeholder="donner une description de la strat ici"></textarea></div>
            </div>
            <div class="contenu">
                <h1 style="border-top: none">Picks</h1>
                <div class="nsPicks nsp">
                    <div style="font-size: 1rem;">Héros triés par ordre alphabétique.<br/>1 clic, pick recommandé &nbsp; | &nbsp; 2 clics : pick situationnel &nbsp; | &nbsp; 3 clics : annuler</div>
                    <div class="tableau">
                        <?php
                        foreach ($heros as $id => $tab){
                            echo "<img class='h-$id' data-hid='$id' data-nomhero=\"".$tab['nom']."\" src='".$tab['img']."' onclick='clickImg(this)'/>";
                        }
                        ?>
                    </div>
                    <div class="resume">Sélection :
                        <div class="oblige"><span class="sTitre">Stratégique : </span></div>
                        <div class="situation"><span class="sTitre">Situationnel : </span></div>
                    </div>
                </div>



                <h1>Bans</h1>
                <div class="nsBans nsp">
                    <div style="font-size: 1rem;">Héros triés par ordre alphabétique.<br/>1 clic, pick recommandé &nbsp; | &nbsp; 2 clics : pick situationnel &nbsp; | &nbsp; 3 clics : annuler</div>
                    <div class="tableau">
                        <?php
                        foreach ($heros as $id => $tab){
                            echo "<img class='h-$id' data-hid='$id' data-nomhero=\"".$tab['nom']."\" src='".$tab['img']."' onclick='clickImg(this)'/>";
                        }
                        ?>
                    </div>
                    <div class="resume">Sélection :
                        <div class="oblige"><span class="sTitre">Stratégique : </span></div>
                        <div class="situation"><span class="sTitre">Situationnel : </span></div>
                    </div>
                </div>



                <h1>Items</h1>
                <div class="nsItems nsp">
                    <div style="font-size: 1rem;">Items triés par ordre alphabétique.<br/>1 clic, recommandé &nbsp; | &nbsp; 2 clics : situationnel &nbsp; | &nbsp; 3 clics : annuler</div>
                    <div class="tableau">
                        <?php
                        foreach ($items as $id => $tab){
                            echo "<img class='h-$id' data-hid='$id' data-nomhero=\"".$tab['nom']."\" src='".$tab['img']."' onclick='clickImg(this)'/>";
                        }
                        ?>
                    </div>
                    <div class="resume">Sélection :
                        <div class="oblige"><span class="sTitre">Stratégique : </span></div>
                        <div class="situation"><span class="sTitre">Situationnel : </span></div>
                        <div id="divHeroTp" class="hidden">
                            <div class="uHero">
                                <img src=""/>
                                <div class="nom"></div>
                            </div>
                        </div>
                    </div>
                </div>


                <h1>Forces et timings</h1>
                <p>Balises Html autorisées</p>
                <textarea class="ft" rows="12" style="border-left: 2px solid #393;" placeholder="Rédiger ici"></textarea>
                <h1>Faiblesses et solutions</h1>
                <p>Balises Html autorisées</p>
                <textarea class="fs" rows="12" style="border-left: 2px solid #933;" placeholder="Rédiger ici"></textarea>
            </div>
            <button class="validStrat" onclick="ajouterStrat(this)">Créer</button>
        </div>
        
        
        <!--
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        
        /////////////////////////////////////////// SELECT STRAT ///////////////////////////////////////////
        
        ////////////////////////////////////////////////////////////////////////////////////////////////////
        -->
        
        
        <?php
        foreach ($strats as $idStrat => $vstrats) {
            ?>
        <div id="editStrat-<?php echo $idStrat ?>" class="hidden"></div>
        <div id="s-<?php echo $idStrat ?>" class="dStrat">
            <div class="bandeau">
                <div id="indic-<?php echo $idStrat ?>" class="indic">↓</div>
                <div class="titre">
                    <span><?php echo $vstrats['nom'] ?></span>
                    <button onclick="clickModifierStrat(<?php echo $idStrat ?>)">Modifier</button>
                </div>
                <div class="desc"><?php echo $vstrats['description'] ?></div>
            </div>
            <div id="sCont-<?php echo $idStrat ?>" class="contenu hidden">
                <h1 style="border-top: none">Picks</h1>
                <?php
                //////////////////////////////////////////////PICKS
                if(!empty($vstrats["picks"])){
                    $herosObligatoire = array();
                    $herosFacultatif = array();
                    foreach ($vstrats["picks"] as $idHero => $tabValue){
                        if($tabValue['oblige']==1) $herosObligatoire[]=$idHero;
                        elseif($tabValue['oblige']==0) $herosFacultatif[]=$idHero;
                    }
                    if(sizeof($herosObligatoire)>0){
                        ?>
                        <div class="oblige op"><span class="sTitre">Stratégique : </span>
                        <?php
                            foreach ($herosObligatoire as $idHero) {
                                ?>
                                <div class="uHero" data-hid="<?php echo $idHero ?>">
                                    <img src="<?php echo $heros[$idHero]['img'] ?>"/>
                                    <div class="nom"><?php echo $heros[$idHero]['nom'] ?></div>
                                </div>
                                <?php
                            }
                        ?></div><?php
                    }
                    if(sizeof($herosFacultatif)>0){
                        ?>
                        <div class="situation sp"><span class="sTitre">Situationnel : </span>
                        <?php
                            foreach ($herosFacultatif as $idHero) {
                                ?>
                                <div class="uHero" data-hid="<?php echo $idHero ?>">
                                    <img src="<?php echo $heros[$idHero]['img'] ?>"/>
                                    <div class="nom"><?php echo $heros[$idHero]['nom'] ?></div>
                                </div>
                                <?php
                            }
                        ?></div><?php
                    }
                }
                else
                    echo "Aucun pick n'est attaché à cette strat (incomplète)";
                ////////////////////////////////////////////BANS
                ?>
                <h1>Bans</h1>
                <?php
                if(!empty($vstrats["bans"])){
                    $herosObligatoire = array();
                    $herosFacultatif = array();
                    foreach ($vstrats["bans"] as $idHero => $tabValue){
                        if($tabValue['oblige']==1) $herosObligatoire[]=$idHero;
                        elseif($tabValue['oblige']==0) $herosFacultatif[]=$idHero;
                    }
                    if(sizeof($herosObligatoire)>0){
                        ?>
                        <div class="oblige ob"><span class="sTitre">Stratégique : </span>
                        <?php
                            foreach ($herosObligatoire as $idHero) {
                                ?>
                                <div class="uHero" data-hid="<?php echo $idHero ?>">
                                    <img src="<?php echo $heros[$idHero]['img'] ?>"/>
                                    <div class="nom"><?php echo $heros[$idHero]['nom'] ?></div>
                                </div>
                                <?php
                            }
                        ?></div><?php
                    }
                    if(sizeof($herosFacultatif)>0){
                        ?>
                        <div class="situation sb"><span class="sTitre">Situationnel : </span>
                        <?php
                            foreach ($herosFacultatif as $idHero) {
                                ?>
                                <div class="uHero" data-hid="<?php echo $idHero ?>">
                                    <img src="<?php echo $heros[$idHero]['img'] ?>"/>
                                    <div class="nom"><?php echo $heros[$idHero]['nom'] ?></div>
                                </div>
                                <?php
                            }
                        ?></div><?php
                    }
                }
                else
                    echo "Aucun ban.";
                /////////////////////////////////////////////////////////ITEMS
                ?>
                <h1>Items</h1>
                <?php
                if(!empty($vstrats["items"])){
                    $itemsObligatoire = array();
                    $itemsFacultatif = array();
                    foreach ($vstrats["items"] as $idItem => $tabValue){
                        if($tabValue['oblige']==1) $itemsObligatoire[]=$idItem;
                        elseif($tabValue['oblige']==0) $itemsFacultatif[]=$idItem;
                    }
                    if(sizeof($itemsObligatoire)>0){
                        ?>
                        <div class="oblige oi"><span class="sTitre">Stratégique : </span>
                        <?php
                            foreach ($itemsObligatoire as $idItem) {
                                ?>
                                <div class="uHero" data-hid="<?php echo $idItem ?>">
                                    <img src="<?php echo $items[$idItem]['img'] ?>"/>
                                    <div class="nom"><?php echo $items[$idItem]['nom'] ?></div>
                                </div>
                                <?php
                            }
                        ?></div><?php
                    }
                    if(sizeof($itemsFacultatif)>0){
                        ?>
                        <div class="situation si"><span class="sTitre">Situationnel : </span>
                        <?php
                            foreach ($itemsFacultatif as $idItem) {
                                ?>
                                <div class="uHero" data-hid="<?php echo $idItem ?>">
                                    <img src="<?php echo $items[$idItem]['img'] ?>"/>
                                    <div class="nom"><?php echo $items[$idItem]['nom'] ?></div>
                                </div>
                                <?php
                            }
                        ?></div><?php
                    }
                }
                else
                    echo "Aucun item obligatoire ou conseillé"
                ?>
                <h1>Forces et timings</h1>
                <p class="cft"><?php echo (empty($vstrats['forces_timings']))?"Non renseigné":$vstrats['forces_timings'] ?></p>
                <h1>Faiblesses et solutions</h1>
                <p class="cfs"><?php echo (empty($vstrats['faiblesses_solutions']))?"Non renseigné":$vstrats['faiblesses_solutions'] ?></p>
            </div>
        </div>
            <?php
        }
        ?>
    </div>
    <script type="text/javascript" src="js-A.js"></script>
</body>
</html> 
<?php
}
else
    header("location: ".$_SERVER['SERVER_NAME']);