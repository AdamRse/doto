<?php
require_once "../init.php";
if(membre){
    $tabPool=array();
    $rPool=$msqli->query("SELECT m.nom, m.pos1, m.pos2 FROM membres m WHERE m.pos1 < 6 ORDER BY nom ASC");
    while($row=$rPool->fetch_assoc()){
        $tabPool[$row['nom']]=array("pos1" =>$row['pos1'], "pos2" =>$row['pos2'], "hero_pool_confort" => null, "hero_pool_non_confort" => null);
    }
    
    $rPool=$msqli->query("SELECT m.nom, hp.hero, hp.comfortable FROM membres m LEFT JOIN hero_pool hp ON m.id = hp.id_membres WHERE m.pos1 < 6 ORDER BY nom ASC");
    while($row=$rPool->fetch_assoc()){
        if($row['comfortable']=="0")
            $tabPool[$row['nom']]['hero_pool_non_confort'][]=$row['hero'];
        elseif($row['comfortable']=="1")
            $tabPool[$row['nom']]['hero_pool_confort'][]=$row['hero'];
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
                    foreach ($tabHero['hero_pool_confort'] as $v) {
                        ?>
                        <div class="cHero">
                            <img src="<?php echo $heros[$v]['img'] ?>" />
                            <div class="nomHero"><?php echo $heros[$v]['nom'] ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="cPicks jouable">
                    <div class="desc">Jouable</div>
                    <?php
                    foreach ($tabHero['hero_pool_non_confort'] as $v) {
                        ?>
                        <div class="cHero">
                            <img src="<?php echo $heros[$v]['img'] ?>" />
                            <div class="nomHero"><?php echo $heros[$v]['nom'] ?></div>
                        </div>
                        <?php
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