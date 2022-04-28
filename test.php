<!DOCTYPE html>
<html>
    <head>
        <title>Flashcard home page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="script.js"></script>
    </head>
        <?php  
         session_start();
        $connection = pg_connect(getenv("DATABASE_URL"));
        if (!$connection)
        { echo("error connecting..."); }
        include('checkAccess.php');

        echo("The only cookie on here is");
        print_r($_COOKIE);
        ?>
    </div>
</body>
</html>


       

        

       

