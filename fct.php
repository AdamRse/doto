<?php
function nomRoles($pos){
    $retour = "Erreur, rôle inconnu";
    if($pos==1) $retour="Core Safelane (position 1)";
    elseif($pos==2) $retour="Core Midlane (position 2)";
    elseif($pos==3) $retour="Core Offlane (position 3)";
    elseif($pos==4) $retour="Support Offlane (position 4)";
    elseif($pos==5) $retour="Support Safelane (position 5)";
    elseif($pos==6) $retour="Coach";
    return $retour;
}