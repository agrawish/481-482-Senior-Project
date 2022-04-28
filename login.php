<?php
$connection = pg_connect(getenv("DATABASE_URL"));
if ($connection)
{ echo("Connected successfully"); }
else 
{ echo("error connecting..."); }


$email = strtolower(trim($_POST['email']));
$password = trim($_POST['password']);
$passwordHashed = '';
$name = '';
$role = '';

//prepare a statement to check how many accounts exist with this Email Address

$usernamePrepStmt = pg_prepare($connection, "checkUsernameQuery", "SELECT user_id, name, email, password, role FROM users WHERE email= $1");
$usernamePrepStmt = pg_execute($connection, "checkUsernameQuery", array($email));
$usernameMatches = pg_num_rows($usernamePrepStmt);

if($usernameMatches == 0)//need to create an account
  { 
    echo('
        <script>
        alert("Your email is either incorrect or you need to create an account with this email first");
        window.location.href = "registerPage.php";
        </script>
        ');
  }
    
while ($row = pg_fetch_row($usernamePrepStmt))
{
  if ($usernameMatches == 1) //make sure user exists with that email before checking for password match
  {
    //bind parameters manually
    $name = $row[1];
    $passwordHashed = $row[3];
    $role = $row[4];
    $id = $row[0];
    if (password_verify($password, $passwordHashed)) 
    {
      //mysqli_stmt_close($usernamePrepStmt);
      session_start();
      $_SESSION["userid"] = $id;
      if ($role == 'teacher')
        { 
          $_SESSION["role"] = $role;
          header("Location: teacherLoadIn.php");
        }
        else //student
        {
          $_SESSION["role"] = $role;
          header("Location: studentHome.php");
        } 
    } else
    {
      echo('
      <script>
      alert("Wrong Password, please login again");
      window.location.href = "loginPage.php";
      </script>
      ');
    }
    
  }
  else
  {
    echo("We ran into an error, please contact the IT staff or try again later");
  }
  
}

//$checkUsernameQuery = "SELECT name, email, password, role FROM users WHERE email=?";
//$usernamePrepStmt = mysqli_prepare($connection, $checkUsernameQuery);
//mysqli_stmt_bind_param($usernamePrepStmt, "s", $email);
//mysqli_stmt_execute($usernamePrepStmt);

//mysqli_stmt_store_result($usernamePrepStmt);
//mysqli_stmt_bind_result($usernamePrepStmt, $name, $email, $passwordHashed, $role);

  //echo(' name: ' . $row[0]); 
  //echo(' email:  ' . $row[1]);
  //echo(' passwordHashed:  ' . $row[2]);
  //echo(' role:  ' . $row[3]); 

?>


