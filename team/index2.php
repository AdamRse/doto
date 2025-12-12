<?php
require_once "../init.php";
if(membre){
    
    $rPool=$msqli->query("SELECT m.nom, m.pos1, m.pos2, hp.hero, hp.comfortable FROM membres m LEFT JOIN hero_pool hp ON m.id = hp.id_membres WHERE m.pos1 < 6 ORDER BY nom ASC");
    $tabPool=array();
    while($row=$rPool->fetch_assoc()){
        $nom = $row['nom'];
        unset($row['nom']);
        if(empty($tabPool[$nom]['pos1'])){
            $tabPool[$nom]["pos1"]=$row['pos1'];
            $tabPool[$nom]["pos2"]=$row['pos2'];
        }
        $tabPool[$nom][]=array("hero" => $row['hero'], "confort" => $row['comfortable']);
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
    <title>Team</title>
    <link rel="stylesheet" href="css.css">
    <link rel="icon" type="image/png" href="../icon.png" />
</head>
<body>
    <h1>Info picks</h1>
    <div id="divPool">
        <?php
        foreach ($tabPool as $nom => $tabHero) {
            ?>
            <div class="membre">
                <div class="nom"><?php echo $nom." - ".nomRoles($tabHero['pos1'])." / ".nomRoles($tabHero['pos2']) ?></div>
                <div class="cPicks confort">
                    <div class="desc">Pick de confort</div>
                    <?php
                    unset($tabHero['pos1']);
                    unset($tabHero['pos2']);
                    foreach ($tabHero as $v) {
                        if($v['confort']==1){
                        ?>
                        <div class="cHero">
                            <img src="<?php echo $heros[$v['hero']]['img'] ?>" />
                            <div class="nomHero"><?php echo $heros[$v['hero']]['nom'] ?></div>
                        </div>
                        <?php
                        }
                    }
                    ?>
                </div>
                <div class="cPicks jouable">
                    <div class="desc">Jouable</div>
                    <?php
                    foreach ($tabHero as $v) {
                        if($v['confort']==0){
                        ?>
                        <div class="cHero">
                            <img src="<?php echo $heros[$v['hero']]['img'] ?>" />
                            <div class="nomHero"><?php echo $heros[$v['hero']]['nom'] ?></div>
                        </div>
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html> 
<?php
}
else
    header("location: ".$_SERVER['SERVER_NAME']);