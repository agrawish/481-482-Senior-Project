<?php
$connection = pg_connect(getenv("DATABASE_URL"));

if ($connection) {
    header("Location: registerPage.php"); //this is the default page
}
else 
{
    echo("error connecting...");
}
?>