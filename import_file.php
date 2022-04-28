<?php
if(isset($_POST["submit_file"]))
{
 $file = $_FILES["file"]["tmp_name"];
 $file_open = fopen($file,"r");
 while(($csv = fgetcsv($file_open, 1000, ",")) !== false)
 {
  $classStudents_id = $csv[0];
  $user_id = $csv[1];
  $course_id = $csv[2];
  mysql_query("INSERT INTO classstudents VALUES ('$classStudents_id','$user_id','$course_id')");
 }
}
?>