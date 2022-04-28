<?php 
 session_start();
 $connection = pg_connect(getenv("DATABASE_URL"));
 if (!$connection)
 { echo("error connecting..."); }
 include('checkAccess.php');
?>
<!DOCTYPE html> 
<html>
    <head>
        <title>Teacher Classrooms Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="script.js"></script>
    </head>
<body>
    <div class=".bg-light">
    <nav class="navbar bg-light">
        <div class="container">
            <ul class="nav nav-pills nav-justified">

                <li class="nav-item">
                    <a class="navbar-brand" href="teacherLoadIn.php">FlashClash Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="teacherCreateClassrooms.php">Create a classroom</a>
                </li>
                <!-- The following is just a sample header on the nav bar -->
                <li class="nav-item">
                    <a class="nav-link" href="teacherCreateClassrooms(2).php">Create a classroom</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="teacherClassrooms.php">See my classrooms</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="teacherLoadIn.php">Create Flashcards</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="teacherViewEditCards.php">View and Edit my Cards</a>
                </li>
                <button class="btn btn-primary" type="button">
                    <a class="link-light" href="logout.php">
                    Log me out
                    </a>
                </button>
            </ul>
        </div>
        </nav>
        <div class="card">
            <div class="card-header">
            <div class="card-body">Basic card</div>
        </div>
</body>
</html>

<?php

?>
