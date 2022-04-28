<?php
    //add students to class via the php/postgres method HERE

    //Connecting to Database
    $connection = pg_connect(getenv("DATABASE_URL"));
    //Checks connection
    if ($connection)
        { echo("Connected successfully"); }
    else 
        { echo("error connecting..."); }

    $studentname =  strtolower(trim($_POST['students']));
    $studentnamePrepStmt = pg_prepare($connection, "studentnameQuery", "SELECT student_name FROM classstudents WHERE student_name = $1");
    $studentnamePrepStmt = pg_execute($connection, "studentnameQuery", array($studentname));
    $studentnameMatches = pg_num_rows($studentnamePrepStmt);

    $insertstudentnamePrepStmt = pg_prepare($connection, "insertstudentnameQuery", "INSERT into classstudents (student_name,course_id) VALUES ($1,$2)");

        if ($studentnameMatches == 0) 
        {
            $insertstudentnamePrepStmt = pg_execute($connection, "insertstudentnameQuery", array($studentname,$course));
            if ((pg_affected_rows($insertstudentnamePrepStmt) ) == 1 ) 
            {
                echo('
                <script>
                alert("You have successfully added the $studentname to the class");
                window.location.href = "teacherCreateClassrooms.php";
                </script>
                ');
            }
            else
            {
                echo('
                <script>
                alert("Issue adding student to the class, please try again");
                window.location.href = "teacherCreateClassrooms.php";
                </script>
                '); 
            }
        }
        else 
        { //name is already added
            echo('
                <script>
                alert("This name has already been added to this class");
                window.location.href = "teacherCreateClassrooms.php";
                </script>
            ');
        }
?>
