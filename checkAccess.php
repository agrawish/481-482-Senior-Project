<?php
//adding a source for the code between the two comments
ini_set('session.save_handler', 'memcached');
ini_set('session.save_path', getenv('MEMCACHIER_SERVERS'));
if(version_compare(phpversion('memcached'), '3', '>=')) {
    ini_set('memcached.sess_persistent', 1);
    ini_set('memcached.sess_binary_protocol', 1);
} else {
    ini_set('session.save_path', 'PERSISTENT=myapp_session ' . ini_get('session.save_path'));
    ini_set('memcached.sess_binary', 1);
}
ini_set('memcached.sess_sasl_username', getenv('MEMCACHIER_USERNAME'));
ini_set('memcached.sess_sasl_password', getenv('MEMCACHIER_PASSWORD'));

//code up to this point was directly from : https://devcenter.heroku.com/articles/memcachier#php


if(!isset($_SESSION["userid"])) //if not logged in
{
    header("Location: loginPage.php");
}
else if(isset($_SESSION["role"]) && $_SESSION["role"] == "student")
{
    
    if(strpos($_SERVER['REQUEST_URI'], "teacher") != false) //if trying to access any teacher page
    {
        header("Location: loginPage.php");
    }
    
} 

?>