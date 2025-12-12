<?php
require_once "../init.php";
if(membre){
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="cache_control" content="no-cache" />
    <title>Info personnelles</title>
    <link rel="stylesheet" href="css.css">
    <link rel="icon" type="image/png" href="../icon.png" />
</head>
<body>
    <h1>Info personnelles</h1>
    <table>
        <tr>
            <td>Pseudo : </td>
            <td><div id="divPseudo"><?php echo membre['nom'] ?> <button id="btModif">Modifier</button></div>
                <input id="iPseudo" class="hidden" placeholder="[Entrée] pour valider" type="text" />
                <span id="reponse"></span>
            </td>
        </tr>
        <tr>
            <td>Rôle<?php echo (coach)?'':' primaire' ?> : </td>
            <td><div id="divPos1"><?php echo nomRoles(membre['pos1']) ?></div></td>
        </tr>
        <?php
        if(!coach){
        ?>
        <tr>
            <td>Rôle Secondaire : </td>
            <td><div id="divPos2"><?php echo nomRoles(membre['pos2']) ?></div></td>
        </tr>
        <?php
        }
        ?>
    </table>
    <script type="text/javascript" src="js.js"></script>
</body>
</html> 
<?php
}
else
    header("location: ".$_SERVER['SERVER_NAME']);