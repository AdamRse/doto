var eTable = document.getElementById("liste_heros");
var rappel = document.getElementsByClassName("rappel");
var radD = document.getElementsByClassName("radD");
var eRadAll = document.querySelectorAll("input");
for(var i = 0;i<eRadAll.length;i++){
    eRadAll[i].onclick = function(e){
        changerHero(e.target);
    }
};
for(var i = 0;i<radD.length;i++){
    radD[i].checked=true;
}
var rq = new XMLHttpRequest();
rq.open("GET", "./hero_pool/get_hero_pool.php");
rq.onload = function(){
    if(this.readyState === 4){
        if(this.response){
            var rep = JSON.parse(this.response);
            if(rep.length > 0){
                for(var i = 0;i<rep.length;i++){
                    document.getElementById("rad"+rep[i].comfortable+"-"+rep[i].hero).checked = true;
                }
            }
            eTable.style.display="block";
        }
        else{
            console.log("erreur, aucune réponse du script get_hero_pool");
        }
    }
}
rq.send();

function changerHero(obj){
    var classe = obj.className;
    var hero = classe.split("-")[1];
    var val = obj.value;
    var rqGet = "./hero_pool/set_hero_pool.php?hero="+hero+"&val="+val;
    lockRadio(classe);
    console.log("RQ :", rqGet);
    var rq = new XMLHttpRequest();
    rq.open("GET", rqGet);
    rq.onload = function(){
        if(this.readyState === 4){
            if(this.response == 1){
                lockRadio(classe, false);
            }
            else{
                alert("L'enregistrement a échoué.\nRequête tentée : "+rqGet+"\nRéponse serveur : "+this.response)
                console.log("erreur, le script set_hero_pool renvoie une erreur", this.response);
            }
        }
    }
    rq.send();
}
function lockRadio(classe, desactive = true){
    classe=(classe.split(" ")[1]==null)?classe:classe.split(" ")[1];
    var e = document.getElementsByClassName(classe);
    for(var i = 0; i<e.length;i++)
        e[i].disabled = desactive;
}
function clicTd(idRad){
    document.getElementById(idRad).click();
}
