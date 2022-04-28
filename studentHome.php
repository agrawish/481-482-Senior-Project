<?php
session_start(); 
$connection = pg_connect(getenv("DATABASE_URL"));
if (!$connection)
{ echo("error connecting..."); }
include('checkAccess.php');
include('studentNavBar.php');
?>
</body>
</html>
