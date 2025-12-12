var sNom = document.getElementById("sNom");
var eTable = document.getElementById("liste_heros");
var rappel = document.getElementsByClassName("rappel");
sNom.onchange = function(){
    if(this.value!=0){
        this.disabled=true;
        eTable.style.display="none";
        changeNomRappel();
        var radD = document.getElementsByClassName("radD");
        for(var i = 0;i<radD.length;i++){
            radD[i].checked=true;
        }
        var rq = new XMLHttpRequest();
        rq.open("GET", "/doto/get_hero_pool.php?nom="+sNom.value);
        rq.onload = function(){
            if(this.readyState === 4){
                if(this.response){
                    var rep = JSON.parse(this.response);
                    if(rep.length > 0){
                        for(var i = 0;i<rep.length;i++){
                            console.log("rad"+rep[i].comfortable+"-"+rep[i].hero)
                            document.getElementById("rad"+rep[i].comfortable+"-"+rep[i].hero).checked = true;
                        }
                    }
                    sNom.disabled=false;
                    eTable.style.display="block";
                }
                else{
                    console.log("erreur, aucune réponse du script get_hero_pool");
                }
            }
        }
        rq.send();
    }
};

function changerHero(obj){
    var classe = obj.className;
    var hero = classe.split("-")[1];
    var val = obj.value;
    var rqGet = "/doto/set_hero_pool.php?nom="+sNom.value+"&hero="+hero+"&val="+val;
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
function changeNomRappel(){
    console.log("changement");
    var nom = document.getElementById("sNom").value;
    for(var i = 0; i<rappel.length;i++)
        rappel[i].innerHTML = "( "+nom+" )";
}