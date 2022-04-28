<?php
 session_start();
 $connection = pg_connect(getenv("DATABASE_URL"));
 if (!$connection)
 { echo("error connecting..."); }
if(isset($_SESSION["userid"]))
{
    //get all the classes a given user is in
    $userID = intval($_SESSION["userid"]);
    //var_dump($userID);
    $course = trim($_POST['course']);
    //echo($course);

}




$coursePrepStmt = pg_prepare($connection, "checkcourseQuery", "SELECT course_name FROM course WHERE course_name= $1");
$coursePrepStmt = pg_execute($connection, "checkcourseQuery", array($course));

$CourseMatches = pg_num_rows($coursePrepStmt);

$insertcoursePrepStmt = pg_prepare($connection, "insertcourseQuery", "INSERT into course (course_name, course_teacher) VALUES ($1, $2)");

if ($CourseMatches == 0) 
{
	$insertcoursePrepStmt = pg_execute($connection, "insertcourseQuery", array($course, $userID));
    if (  (pg_affected_rows($insertcoursePrepStmt)) == 1 ) 
    {
        
        echo('
        
        <script>
        if (confirm("You have successfully added a class")) {
            window.location.href = "teacherLoadIn.php";
          } else {
            window.location.href = "teacherLoadIn.php";
          }
       
        </script>
        ');
        //header("location:teacherCreateClassrooms.php");
    }
    else
    {
        echo('
       
        <script>
        if (confirm("Issue adding class")) {
            window.location.href = "teacherCreateCourse.php";
          } else {
            window.location.href = "teacherCreateCourse.php";
          }
       
        </script>
        
        '); 
    }
}
else
{
    echo('
        
        <script>
        if (confirm("This class already exists")) {
            window.location.href = "teacherLoadIn.php";
          } else {
            window.location.href = "teacherLoadIn.php";
          }
       
        </script>
        
        ');
}
?>