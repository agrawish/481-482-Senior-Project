<?php 
 session_start();
 $connection = pg_connect(getenv("DATABASE_URL"));
 if (!$connection)
 { echo("error connecting..."); }
 include('checkAccess.php');
 include('navBar.php');
?>
</br>
<h3>Create a Classroom</h3>
     
 <?php
if(isset($_SESSION["userid"]))
{
    //get all the classes a given user is in
    $userID = $_SESSION["userid"];

        $teacherClassesPrepStmt = pg_prepare($connection, "teacherClassesQuery", "SELECT course_id, course_name FROM course where course_teacher = $1");
        $teacherClassesPrepStmt = pg_execute($connection, "teacherClassesQuery", array($userID));
        $teacherClassID = '';
        $teacherClassName = '';
        $teacherClassesArray = array();
        while($teacherClass= pg_fetch_row($teacherClassesPrepStmt))
        {
            $teacherClassID = $teacherClass[0]; //course id
            $teacherClassName = $teacherClass[1]; //course name
            $teacherClassesArray[$teacherClassID] = $teacherClassName;    
        }
        //var_dump($teacherClassesArray);


    echo('<form action="addStudentsScript.php" name="createClassroomForm" id="createClassroom" method="post" class= "rounded-lg w-50 h-50 mx-auto shadow p-4 bg-primary text-white rounded">
    
    <select class="form-select mx-auto" aria-label="selectClass" name="selectClass" id="selectClass">
    <option>Choose class to add students to: </option>');

    foreach ($teacherClassesArray as $key => $value) 
    {
    //key is the course id, value is the course name, which is in the dropdown       
        echo('<option
        id="' . "$key" . '"
        name="' . "$value" . '"
        value="' . "$key" . '"
        >
        ' . "$value" .'
        </option>
        ');
    }

    echo
    ('</select>
        <legend>Add Students:</legend>
             <input type = "textarea" id="students" class ="form-control" name = "students" maxlength="5000" minlength="1" required placeholder="Ex.: Student Name"/> 
             <br/>
             <input type = "submit" class = "bg-success rounded text-center" value = "Add Students"/>
        </form>
    </form>
    ');
}
?>
             

</body>
</html>

