<?php
require_once 'init.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="cache_control" content="no-cache" />
    <title>Hub des Fondants Croustillants</title>
    <link rel="stylesheet" href="css.css">
    <link rel="icon" type="image/png" href="icon.png" />
</head>
<body>
    <h1>Hub des Fondants croustillants</h1>
    <noscript>Javascript non détecté, impossible de faire fonctionner l'application.</noscript>
    <?php
    if(membre){
        if(!coach){
        ?>
        <a href="./hero_pool">Héro pool</a><br/>
        <?php
        }
        ?>
        <a href="./strats">Stratégie</a><br/>
        <a href="./team">Info team</a><br/>
        <a href="./perso">Info personnelles</a>
        <p>Connecté en tant que <b><?php echo membre['nom'] ?></b></p>
        
        <?php
    }
    else{
        ?>
        <h2>Identifiez vous</h2>
        <input id="idf" type="text" placeholder="code d'identification" />
        <div id="dInfo"></div>
        <?php
        if(!empty($_GET['redirect']) && is_dir($_GET['redirect'])){
            echo '<div id="rd">'.htmlentities($_GET['redirect']).'</div>';
        }
    }
    ?>
    <script type="text/javascript">
    var idf;
    var dInfo;
    var rd = false;
    if(document.body.contains(document.getElementById('rd'))) rd = document.getElementById("rd").innerHTML;
    if(document.body.contains(document.getElementById('dInfo'))) dInfo = document.getElementById("dInfo");
    if(document.body.contains(document.getElementById('idf'))){
        idf = document.getElementById("idf");
        idf.oninput = function(){
            var element = this;
            var contenu = element.value;
            if(contenu.length>=5){
                element.disabled = true;
                element.value = "";
                var rq = new XMLHttpRequest();
                rq.open("GET", "./connect.php?id="+contenu);
                rq.onload = function(){
                    if(this.readyState === 4){
                        element.disabled = false;
                        var rp = this.response;
                        if(rp==1) location.reload(false);
                        else if(rp==2) alerteInfo("Erreur ajax, requête vide.");
                        else if(rp==3) alerteInfo("Le code <b>"+contenu+"</b> n'a pas la bonne longueur.");
                        else if(rp==4) alerteInfo("Le code <b>"+contenu+"</b> n'est pas conforme.");
                        else if(rp==5) alerteInfo("Trop de requêtes, patienter quelques une minute pour retenter.");
                        else if(rp==6) alerteInfo("Trop de requêtes, patienter 10 minutes pour retenter.");
                        else if(rp==7) alerteInfo("Code <b>"+contenu+"</b> inconnu.");
                        else console.log(rp);
                    }
                }
                rq.send();
            }
        };
    }
    function alerteInfo(txt, cls = "erreur"){
        dInfo.className = cls;
        dInfo.innerHTML = txt;
    }
    </script>
</body>
</html> 