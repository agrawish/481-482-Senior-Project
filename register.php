<?php
//this part needs to be on every page (connection to remote db)
$connection = pg_connect(getenv("DATABASE_URL"));
if ($connection)
{ echo("Connected successfully"); }
else 
{ echo("error connecting..."); }
//this part needs to be on every page (connection to remote db)

$name = strtolower(trim($_POST['name']));
$email = strtolower(trim($_POST['schoolEmail']));
$passwordHashed = password_hash((trim($_POST['password'])), PASSWORD_DEFAULT);

$pwd = $_POST['password'];
$pwdConfirm = $_POST['confirmPassword'];
if ($pwd != $pwdConfirm) {
    //passwords don't match
    echo('
        <script>
        alert("Your passwords do not match, please register again");
        //window.location.href = "registerPage.php";
        </script>
        ');
}

$role = '';

//set role based on email
if(strstr($email,'@nthurston.k12.wa.us') != FALSE)
{
    $role = 'teacher';
}
else if(strstr($email,'@stu.nthurston.k12.wa.us') != FALSE)
{
    $role = 'student';
}
else
{
    header("Location: registerPage.php");
}

//prepare a statement to check how many accounts exist with this Email Address
//$checkUsernameQuery = "SELECT email FROM users WHERE email= $1";
$usernamePrepStmt = pg_prepare($connection, "checkUsernameQuery", "SELECT email FROM users WHERE email= $1");
$usernamePrepStmt = pg_execute($connection, "checkUsernameQuery", array($email));

//mysqli_stmt_execute($usernamePrepStmt);
//mysqli_stmt_store_result($usernamePrepStmt);
$usernameMatches = pg_num_rows($usernamePrepStmt);
//echo($usernameMatches);

//$insertQuery = "INSERT into users (name, email, password, role) VALUES (?, ?, ?, ?)";
$insertPrepStmt = pg_prepare($connection, "insertQuery", "INSERT into users (name, email, password, role) VALUES ($1, $2, $3, $4)");
//$insertPrepStmt = pg_execute($connection, "insertQuery", array($name, $email, $passwordHashed, $role));


 if ($usernameMatches == 0) { //unique account - insert the data
    $insertPrepStmt = pg_execute($connection, "insertQuery", array($name, $email, $passwordHashed, $role));
    if (  (pg_affected_rows($insertPrepStmt)) == 1 ) {
        echo('
        <script>
        alert("You have successfully created an account");
        window.location.href = "loginPage.php";
        </script>
        ');
    } 
    else
    {
        echo('
        <script>
        alert("Issue creating account, please register again");
        //window.location.href = "registerPage.php";
        </script>
        '); 
    }
}
else 
{ //account already exisits
    echo('
        <script>
        alert("This email/account already exists, please log in instead");
        window.location.href = "loginPage.php";
        </script>
        ');
}
//mysqli_stmt_close($insertPrepStmt);
//mysqli_stmt_close($usernamePrepStmt);
pg_close($connection);
?>
