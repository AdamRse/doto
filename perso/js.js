var divPseudo = document.getElementById("divPseudo");
var iPseudo = document.getElementById("iPseudo");
var btModif = document.getElementById("btModif");
var sRep = document.getElementById("reponse");

btModif.onclick = function(){
    iPseudo.classList.remove("hidden");
    divPseudo.classList.add("hidden");
    iPseudo.focus();
};
iPseudo.onkeypress = function(ev){
    if(ev.keyCode == 13){
        iPseudo.disabled = true;
        var newPseudo = this.value;
        if(newPseudo != divPseudo.innerHTML){
            var rq = new XMLHttpRequest();
            rq.open("GET", "./modifierPseudo.php?ps="+newPseudo);
            rq.onload = function(){
                if(this.readyState === 4){
                    if(this.response == "1"){
                        document.getElementById("divPseudo").innerHTML=newPseudo;
                        sReponse("OK", 1000);
                    }
                    else if(this.response == "2") sReponse("Aucun paramètre reçu", 3000, false);
                    else if(this.response == "3") sReponse("Erreur de reqête", 3000, false);
                }
            }
            rq.send();
        }
        else{
            sReponse("OK", 1000);
        }
    }
}
function sReponse(txt, t = 2000, n = true){
    
    sRep.innerHTML = txt;
    iPseudo.disabled=false;
    iPseudo.value = "";
    iPseudo.classList.add("hidden");
    divPseudo.classList.remove("hidden");
    if(n) sRep.classList.remove("erreur");
    else  sRep.classList.add("erreur");
    setTimeout(function(){
        sRep.innerHTML = "";
    }, t);
}