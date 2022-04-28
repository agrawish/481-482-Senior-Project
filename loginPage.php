<!DOCTYPE html>
<html>
    <head>
        <title>Registration Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="script.js"></script>
    </head>

<body class="bg-primary">
    <div class="container">
       
        <form action="login.php" method="post" class="was-validated">
           
                <div class="container rounded-lg w-50 h-50 mx-auto shadow p-4 bg-light .text-dark rounded">
                   
                    <legend>Login:</legend>
            
                        <label for="email">School Email</label> 
                        <input type="text" id="email" class="form-control" name="email" required placeholder="JohnDoe@nthurston.k12.wa.us"/>
                        <br/>
        
                        <label for="password">Password</label> 
                        <input type="password" id="password" class="form-control" name="password" required placeholder="JD_12345"/>
                        <br/>

                        <input type="submit" class="bg-primary" value="Login">
                        
                        <a href="registerPage.php" target="_blank"> 
                            <button type="button" class="btn btn-outline-primary btn-block">
                                Don't have an account? Click here to Register
                            </button>
                        </a>
                </div>
                <br/>  
                </form>
        </div>  
            
            
    
</body>
</html>