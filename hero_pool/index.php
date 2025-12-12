<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../init.php";
$rHeros = $msqli->query("SELECT id, nom, img FROM hero_list");
$heroList = array();
while($row = $rHeros->fetch_assoc()){
    $heroList[]=$row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="cache_control" content="no-cache" />
    <title>Héros pool</title>
    <link rel="stylesheet" href="css.css">
    <link rel="icon" type="image/png" href="../icon.png" />
</head>
<body>
<?php
if(membre && !coach){
?>
    <noscript>Javascript non détecté, impossible de faire fonctionner la page.</noscript>
    <h1>Gérez votre héro poule</h1>
    <p>Connecté en tant que <b><?php echo membre['nom'] ?></b></p>
    <table id="liste_heros">
        <tr class="titre">
            <td>Héro</td>
            <td class="pick confort">Pick de confort</td>
            <td class="pick jouable">Jouable</td>
            <td class="pick nonJ">Non-jouable</td>
        </tr>
        <?php
        $iForeach=1;
        foreach ($heroList as $value) {
            echo "<tr id='t-".$value['id']."'>";
            echo '<td style="background-image: url(\''. $value['img'].'\')" class="colbg" id="h-'.$value['id'].'" class="hero"><span>'.$value['nom'].'</span></td>';
            echo '<td onclick="clicTd(\'rad1-'.$value['id'].'\')" class="confort cl"><input class="rh-'.$value['id'].'" id="rad1-'.$value['id'].'" type="radio" name="select-'.$value['id'].'" value="2"/></td>';
            echo '<td onclick="clicTd(\'rad0-'.$value['id'].'\')" class="jouable cl"><input class="rh-'.$value['id'].'" id="rad0-'.$value['id'].'" type="radio" name="select-'.$value['id'].'" value="1"/></td>';
            echo '<td onclick="clicTd(\'radD-'.$value['id'].'\')" class="nonJ cl tdD-'.$value['id'].'"><input class="radD rh-'.$value['id'].'" id="radD-'.$value['id'].'" type="radio" name="select-'.$value['id'].'" checked="1" value="0"/></td>';
            echo "</tr>";
            if($iForeach%16==0){
                echo '<tr class="titre"><td>Héro</td><td>Pick de confort</td><td>Jouable</td><td>Non-jouable</td></tr>';
            }
            $iForeach++;
        }
        ?>
    </table>
    <div style="display: none"><?php
        foreach($heroList as $value){
            echo "<img src='".$value['img']."' />";
        }
    ?></div>
    <script type="text/javascript" src="js-B.js"></script>
    <?php
}
else{
    header("location: https://adamrousselle.ddns.net/doto?redirect=hero_pool");
}
?>
</body>
</html> 