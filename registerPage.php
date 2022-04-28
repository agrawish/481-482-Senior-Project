<?php
$connection = pg_connect(getenv("DATABASE_URL"));
if ($connection)
{ 
    

}
else
{
    echo("error connecting");

}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Registration Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="script.js">

        </script>
        <script>
            const currentURL = location.href;
            const errorURL = currentURL.replace("registerPage.php", "error.php");
            function changeVisibilityOnOK()
            {  
                if 
                (
                    confirm("This site uses cookies for user experience. You must click OK to accept and use this site.") == true) 
                    {
                        document.getElementById("registerFormVisibility").className = "container visible";
                        //alert(document.getElementById("registerFormVisibility").className);
                    }
                    else 
                    {
                        location.assign(errorURL);
                    }
            }
        </script>
    </head>

<body class="bg-primary" onload="changeVisibilityOnOK()">
    
    <div id="registerFormVisibility" class="container invisible">
       
        <form action="register.php" method="post" class="was-validated" onsubmit="return validateRegistration()">
           
                <div class="container rounded-lg w-50 h-50 mx-auto shadow p-4 bg-light .text-dark rounded">
                   
                    <legend>Register:</legend>
            
                        <label for="name">Name</label> 
                        <input type="text" id="name" class="form-control" name="name" maxlength="50" minlength="1" required placeholder="John Doe"/> 
                        <br/>
        
                        <label for="schoolEmail">School Email</label> 
                        <input type="email" id="schoolEmail" class="form-control" name="schoolEmail" maxlength="50" required placeholder="DoeJohn@nthurston.k12.wa.us"/>
                        <br/>
        
                        
                        <label for="password">School Password</label> 
                        <input type="password" id="password" class="form-control" name="password" minlength="8" maxlength="30" required placeholder="JD_12345"/>
                        <br/> 
                        
                        <label for="confirmPassword">Confirm Password</label> 
                        <input type="password" id="confirmPassword" class="form-control" name="confirmPassword" minlength="8" maxlength="30" required placeholder="JD_12345"/>
                        <br/> 
                            
                        <input type="submit" class="bg-success rounded" value="Create my account"/>

                        <a href="loginPage.php" target="_blank"> 
                            <button type="button" class="btn btn-outline-primary btn-block">
                                Click here to Login
                            </button>
                        </a>
                </div>
            <br/> 
            </form>
        </div>  
            
            
    
</body>
</html>