<?php
$msqli=new mysqli("localhost", "raspi", "", "doto");
$rMembres = $msqli->query("SELECT nom FROM membres ORDER BY nom ASC");
$rHeros = $msqli->query("SELECT id, nom, img FROM hero_list");
$membres = array();
while($row = $rMembres->fetch_assoc()){
    $membres[]=$row['nom'];
}
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
    <link rel="icon" type="image/png" href="img/icon.png" />
</head>
<body>
    <h1>Doto</h1>
    <p>Choisissez votre héro pool, entrez votre nom</p>
    <select id="sNom">
        <option value="0">Sélectionnez votre nom</option>
        <?php
        foreach ($membres as $value) {
            echo '<option value="'.$value.'">'.$value.'</option>';
        }
        ?>
    </select>
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
            echo '<td onclick="clicTd(\'rad1-'.$value['id'].'\')" class="confort cl"><input onchange="changerHero(this)" class="rh-'.$value['id'].'" id="rad1-'.$value['id'].'" type="radio" name="select-'.$value['id'].'" value="2"/></td>';
            echo '<td onclick="clicTd(\'rad0-'.$value['id'].'\')" class="jouable cl"><input onchange="changerHero(this)" class="rh-'.$value['id'].'" id="rad0-'.$value['id'].'" type="radio" name="select-'.$value['id'].'" value="1"/></td>';
            echo '<td onclick="clicTd(\'radD-'.$value['id'].'\')" class="nonJ cl tdD-'.$value['id'].'"><input onchange="changerHero(this)" class="radD rh-'.$value['id'].'" id="radD-'.$value['id'].'" type="radio" name="select-'.$value['id'].'" checked="1" value="0"/></td>';
            echo "</tr>";
            if($iForeach%15==0){
                echo '<tr><td colspan="4" class="rappel"></td></tr>';
                echo '<tr class="titre"><td>Héro</td><td>Pick de confort</td><td>Jouable</td><td>Non-jouable</td></tr>';
            }
            $iForeach++;
        }
        ?>
    </table>
    <div style="display: none"><?php
        foreach ($heroList as $value) {
            echo "<img src='".$value['img']."' />";
        }
    ?></div>
    <script src="js.js"></script>
</body>
</html> 