<?php 
 session_start();
 $connection = pg_connect(getenv("DATABASE_URL"));
 if (!$connection)
 { echo("error connecting..."); }
 include('checkAccess.php');
 include('navBar.php');
?>
<div class="container">
<br/>
<form action="courseScript.php" method="post">
            <div class="container rounded-lg w-50 h-50 mx-auto shadow p-4 bg-primary text-white rounded">
                <legend>Add Course:</legend>
                <input type = "text" id="course" class ="form-control" name = "course" maxlength="50" minlength="1" required placeholder="Ex.: Biology"/> 
                <br/>
                <input type = "submit" class = "bg-success rounded" value = "Add Course"/>
                <br/>
             </div>
            <br/>
        </form>
        <br/>
</div>

