var dStrat = document.querySelectorAll(".dStrat");
for(var i = 0;i<dStrat.length;i++){
    dStrat[i].querySelector(".bandeau").onclick = function(e){
        if(e.target.nodeName!="BUTTON"){
            var parent=e.target.parentNode;
            if(parent.id=="") parent=e.target.parentNode.parentNode;
            var idStrat=parent.id.split("-")[1];
            if(idStrat!=undefined){
                if(document.getElementById("sCont-"+idStrat).classList.toggle("hidden")){
                    document.getElementById("indic-"+idStrat).innerHTML = "↓";
                }
                else{
                    document.getElementById("indic-"+idStrat).innerHTML = "↑";
                }
            }
        }
    };
}


//        AJOUTER UNE STRAT


var tpDivHero = document.querySelector("#divHeroTp .uHero");

function clickImg(img){
    var nsPick = img.parentNode.parentNode;
    
    if(img.className=="oblige"){//Set dans optionnel
        img.className="optionel";
        
        var situation = nsPick.querySelector(".resume .situation");
        
        //Ajouter l'image dans optionel
        var nvDivHero = tpDivHero.cloneNode(true);
        nvDivHero.dataset.hid = img.dataset.hid;
        nvDivHero.querySelector("img").src=img.src;
        nvDivHero.querySelector(".nom").innerHTML = img.dataset.nomhero;
        situation.appendChild(nvDivHero);
        
        //On vire l'image qui était dans oblige
        var obligeChilds = nsPick.querySelector(".resume .oblige").childNodes;
        for(var i = 0;i<obligeChilds.length;i++){
            if(obligeChilds[i].dataset.hid == img.dataset.hid){
                obligeChilds[i].remove();
            }
        }
    }
    else if(img.className=="optionel"){//Annulation
        img.classList.remove("optionel");
        
        var optionel = nsPick.querySelector(".resume .situation").childNodes;
        for(var i = 0;i<optionel.length;i++){
            if(optionel[i].dataset.hid == img.dataset.hid){
                optionel[i].remove();
            }
        }
    }
    else{//Set dans oblige
        img.className="oblige";
        
        var oblige = nsPick.querySelector(".resume .oblige");
        
        var nvDivHero = tpDivHero.cloneNode(true);
        nvDivHero.dataset.hid = img.dataset.hid;
        nvDivHero.querySelector("img").src=img.src;
        nvDivHero.querySelector(".nom").innerHTML = img.dataset.nomhero;
        oblige.appendChild(nvDivHero);
    }
}

function ajouterStrat(bt){
    var newStrat=bt.parentNode;
    newStrat.classList.toggle("hidden");
    
    var pRq = "";
    pRq += "titre="+encodeURIComponent(newStrat.querySelector(".nsTitre").value);
    pRq += "&desc="+encodeURIComponent(newStrat.querySelector(".nsDesc").value);
    pRq += "&picks="+encodeURIComponent(getIdh(newStrat.querySelectorAll(".nsPicks .resume .oblige .uHero"))+";"+getIdh(newStrat.querySelectorAll(".nsPicks .resume .situation .uHero")));
    pRq += "&bans="+encodeURIComponent(getIdh(newStrat.querySelectorAll(".nsBans .resume .oblige .uHero"))+";"+getIdh(newStrat.querySelectorAll(".nsBans .resume .situation .uHero")));
    pRq += "&items="+encodeURIComponent(getIdh(newStrat.querySelectorAll(".nsItems .resume .oblige .uHero"))+";"+getIdh(newStrat.querySelectorAll(".nsItems .resume .situation .uHero")));
    pRq += "&ft="+encodeURIComponent(newStrat.querySelector(".ft").value);
    pRq += "&fs="+encodeURIComponent(newStrat.querySelector(".fs").value);
    if(newStrat.id!="newStrat"){ pRq += "&id="+newStrat.id.split("-")[1]; }
    
    var rq = new XMLHttpRequest();
    rq.open('POST', './strats/ajouterStrat_1.php', true);

    //Send the proper header information along with the request
    rq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    rq.onreadystatechange = function() {//Call a function when the state changes.
        if(rq.readyState == 4 && rq.status == 200) {
            if(rq.responseText == "1") location.reload();
            else alert(rq.responseText);
        }
    }
    rq.send(pRq);
}

function getIdh(selectorAll){
    var t = "";
    for(var i = 0;i<selectorAll.length;i++){
        t+="-"+selectorAll[i].dataset.hid;
    }
    return t;
}

//        MODIFIER UNE STRAT


function clickModifierStrat(idStrat){
    var strat = document.getElementById("s-"+idStrat);
    var editStrat = document.getElementById("editStrat-"+idStrat);
    var editContenu = document.getElementById("newStrat").cloneNode(true);
    editContenu.id = "editStratCont-"+idStrat;
    editContenu.classList.remove("hidden");
    //remplir les cases
    remplirCases(strat, editContenu);
    //Remplir les input
    editContenu.querySelector(".nsTitre").value=strat.querySelector(".bandeau .titre span").innerHTML;
    editContenu.querySelector(".nsDesc").value=strat.querySelector(".bandeau .desc").innerHTML.replace(/<br ?\/?>/gi, "");
    editContenu.querySelector(".ft").value=strat.querySelector(".cft").innerHTML.replace(/<br ?\/?>/gi, "");
    editContenu.querySelector(".fs").value=strat.querySelector(".cfs").innerHTML.replace(/<br ?\/?>/gi, "");
    var btValiderStrat = editContenu.querySelector(".validStrat");
    btValiderStrat.innerHTML = "Éditer la strat<br/><b>"+strat.querySelector(".bandeau .titre span").innerHTML+"</b>";
    editStrat.appendChild(editContenu);
    
    
    strat.className="dStrat hidden";
    editStrat.className="";
}
function remplirCases(copier, cible){
    var listPickO = copier.querySelectorAll(".op .uHero");
    for(var i = 0;i<listPickO.length;i++){
        var hero = listPickO[i].dataset.hid;
        if(hero!=undefined){
            cible.querySelector(".nsPicks .tableau .h-"+hero).className="h-"+hero+" oblige";
            var img = listPickO[i].cloneNode(true);
            cible.querySelector(".nsPicks .resume .oblige").appendChild(img);
        }
    }
    var listPickS = copier.querySelectorAll(".sp .uHero");
    for(var i = 0;i<listPickS.length;i++){
        var hero = listPickS[i].dataset.hid;
        if(hero!=undefined){
            cible.querySelector(".nsPicks .tableau .h-"+hero).className="h-"+hero+" optionel";
            var img = listPickS[i].cloneNode(true);
            cible.querySelector(".nsPicks .resume .situation").appendChild(img);
        }
    }
    ////////////BANS
    var listPickO = copier.querySelectorAll(".ob .uHero");
    for(var i = 0;i<listPickO.length;i++){
        var hero = listPickO[i].dataset.hid;
        if(hero!=undefined){
            cible.querySelector(".nsBans .tableau .h-"+hero).className="h-"+hero+" oblige";
            var img = listPickO[i].cloneNode(true);
            cible.querySelector(".nsBans .resume .oblige").appendChild(img);
        }
    }
    var listPickS = copier.querySelectorAll(".sb .uHero");
    for(var i = 0;i<listPickS.length;i++){
        var hero = listPickS[i].dataset.hid;
        if(hero!=undefined){
            cible.querySelector(".nsBans .tableau .h-"+hero).className="h-"+hero+" optionel";
            var img = listPickS[i].cloneNode(true);
            cible.querySelector(".nsBans .resume .situation").appendChild(img);
        }
    }
    ////////////ITEMS
    var listPickO = copier.querySelectorAll(".oi .uHero");
    for(var i = 0;i<listPickO.length;i++){
        var hero = listPickO[i].dataset.hid;
        if(hero!=undefined){
            cible.querySelector(".nsItems .tableau .h-"+hero).className="h-"+hero+" oblige";
            var img = listPickO[i].cloneNode(true);
            cible.querySelector(".nsItems .resume .oblige").appendChild(img);
        }
    }
    var listPickS = copier.querySelectorAll(".si .uHero");
    for(var i = 0;i<listPickS.length;i++){
        var hero = listPickS[i].dataset.hid;
        if(hero!=undefined){
            cible.querySelector(".nsItems .tableau .h-"+hero).className="h-"+hero+" optionel";
            var img = listPickS[i].cloneNode(true);
            cible.querySelector(".nsItems .resume .situation").appendChild(img);
        }
    }
}