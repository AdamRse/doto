<?php
require_once "../init.php";
if(membre){
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="cache_control" content="no-cache" />
    <title>titre</title>
    <link rel="stylesheet" href="css.css">
    <link rel="icon" type="image/png" href="../icon.png" />
</head>
<body>
    <h1>Titre</h1>
</body>
</html> 
<?php
}
else
    header("location: ".$_SERVER['SERVER_NAME']);