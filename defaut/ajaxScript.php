<?php
ini_set("session.use_cookies", 1);
session_start();

if(!empty($_SERVER['HTTP_SEC_FETCH_SITE'])&& $_SERVER['HTTP_SEC_FETCH_SITE'] == "same-origin" && !empty($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE']=="cors"){
    
}
else
    echo "service indisponnible";