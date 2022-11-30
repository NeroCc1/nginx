<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: welcome.php");
  exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor ingrese su usuario.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingrese su contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "La contraseña que has ingresado no es válida.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No existe cuenta registrada con ese nombre de usuario.";
                }
            } else{
                echo "Algo salió mal, por favor vuelve a intentarlo.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
</head>
<section class="register-account"> 
        <div class="signform">  
        <div class="left">
            <div class="bts">
            <a href="#" class="fblogin social"><i class="fa fa-facebook"></i><span>Inicie Sesion con Facebook</span></a>
            </div>
          </div>
          <div class="right">
              <div class="headit">
            <h2>Login</h2>
              </div>
        <p>Por favor, complete sus credenciales para iniciar sesión.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Ingresar">
            </div>
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate ahora</a>.</p>
        </form>
    </div>    
        </div>
</body>
</section>
</html>

<style>
.register-account{
        background: url(http://cdn.paper4pc.com/images/grunge-texture-wallpaper-5.jpg) no-repeat center top;
        background-color: transparent;
        background-image: url(http://cdn.paper4pc.com/images/grunge-texture-wallpaper-5.jpg);
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center top;
        background-clip: border-box;
        background-origin: padding-box;
        background-size: auto auto;
        background-size: cover;
        padding: 200px 0 150px;
        padding-top: 200px;
        padding-right: 0px;
        padding-bottom: 150px;
        padding-left: 0px;
        width: auto;

}

/*Login Form CSS*/
.login-form input{
  width: 100%;
  border: 1px solid #dddddd;
  padding: 0.7em;
  margin: 0.6em 0;
  border-radius: 0;
  margin-bottom: 15px;
}
.login-form{
  margin-bottom: 30px;
} 
.signform {
  background-color: #FFF;
  padding-bottom: 40px;
  padding-top: 35px;
  margin: 0 auto;
  margin-top: auto;
}
.bts {
  padding: 2em;
  margin: 0.6em 0;
  margin-top: 
}
.bts-a:hover{
  color: #79b42b;
  text-decoration: none;
  background: white;   
}
.bts span {
  text-align: center; 
  font-size: .9em;
  font-family: 'Arimo', sans-serif;
  font-weight: 700;
  font-style: normal;
}
.social{
  transition: background 200ms ease-in-out 0s;
  -webkit-transition: background 200ms ease-in-out 0s;
  -moz-transition: background 200ms ease-in-out 0s;
  -o-transition: background 200ms ease-in-out 0s;
  -ms-transition: background 200ms ease-in-out 0s;
  
  margin-top: 12px;
  -webkit-border-top-left-radius:1px;
  -moz-border-radius-topleft:1px;
  border-top-left-radius:1px;
  -webkit-border-top-right-radius:1px;
  -moz-border-radius-topright:1px;
  border-top-right-radius:1px;
  -webkit-border-bottom-right-radius:1px;
  -moz-border-radius-bottomright:1px;
  border-bottom-right-radius:1px;
  -webkit-border-bottom-left-radius:1px;
  -moz-border-radius-bottomleft:1px;
  border-bottom-left-radius:1px;
  text-indent:0;
  display:block;
  color:#ffffff;
  height:50px;
  line-height:50px;
  width: 100%;
  text-decoration:none;
  text-align:center;
}

.fblogin {   
  background-color:#3b5898;  
}
.fblogin:hover {
  background-color:#5177c2;
}
.fblogin:active {
  position:relative;
  top:1px;
}
.gplogin {  
  background-color:#dd4c39; 
}
.gplogin:hover {
  background-color:#f06e60;
}
.gplogin:active {
  position:relative;
  top:1px;
}
.twlogin { 
  background-color:#00abee;  
}
.twlogin:hover {
  background-color:#4cbde6;
}
.twlogin:active {
  position:relative;
  top:1px;
}
.login-form input:focus, .login-form input:active {
  border-bottom: 2px solid #79B42B;
  outline: none;
}
.subbt:hover {
  background-color: #79b42b;
}
.subbt {
 
  text-shadow: 0 1px 0 rgba(122, 122, 122, 0.85);
  transition: background 500ms ease-in-out 0s;
  -webkit-transition: background 500ms ease-in-out 0s;
  -moz-transition: background 500ms ease-in-out 0s;
  -o-transition: background 500ms ease-in-out 0s;
  -ms-transition: background 500ms ease-in-out 0s;
  background-color: #79b42b;
  border: none;
  color: #FFF;
  padding: 10px 15px 10px 15px;
  margin-top: 10px;
  cursor: pointer;
  font-size: .9em;
  border-radius: 3px;
  width: 218px;
  font-family: 'Arimo', sans-serif;
  font-weight: 700;
  font-style: normal;

}
.right a {
  position: relative;
  color: #b6b6b6;
  text-decoration: none;
  font-family: 'Arimo', sans-serif;
  font-weight: 400;
  font-style: normal;
  font-size: .9em;
  float: right;
  margin-top:5px;
}
.headit { 
  position: relative; 
  top: -10px;
}
.headit h4{
  color: #474646;
}
.bts a:hover{
  text-decoration: none;
  color: white;
}
.bts a:active{
  color: white;
  text-decoration: none;
}
.bts a:focus{
  text-decoration: none;
  color: white;
}
.headit a {
text-decoration: none;
}

.fa.fa-check-square {
padding-right: 19px;
}
form#login-form:before {
  content: 'or';
  color: #79b42b;
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  margin: auto;
  height: 0.5em;
  width: 0.5em;
  left: 1.5em;
 
  z-index: 900;
}
form#login-form:after {
  content: '';
  position: absolute;
  background: rgba(128, 128, 128, 0.3);
  top: 0;
  right: 0;
  left: 0;
  bottom: 15px;
  margin: auto;  
  height: 3.25em;
  width: 0.1em;
  left: 2.15em;
 
  -moz-box-shadow: 0 8.8em 0 0 rgba(128, 128, 128, 0.3);
  -webkit-box-shadow: 0 8.8em 0 0 rgba(128, 128, 128, 0.3);
  box-shadow: 0 8.8em 0 0 rgba(128, 128, 128, 0.3);
}
/*Media Query */
@media screen  and (min-width: 1400px) {
.signform { width: 680px; left: 25%; }
}


@media screen  and (max-width: 1400px) and (min-width: 1230px) {
.signform { width: 50%; left: 25%; }
}

@media screen  and (max-width: 1230px) and (min-width: 1000px) {
.signform { width: 60%; left: 15%; }
}

@media screen  and (max-width: 1000px) and (min-width: 900px) {
.signform { width: 70%; left: 10%; }
}

@media screen  and (max-width: 900px) and (min-width: 750px) {
.signform { width: 80%; left: 8%; }
}

@media screen  and (max-width: 750px) and (min-width: 640px) {
.signform { width: 90%; 
  left: 1%; 
  }
}

@media screen  and (min-width: 640px) {
  .left {   
  width: 47%;
  display: inline-table;
  margin-left: 20px;
  float: right;
  }
  .right {
  width: 40%;
  display: inline-table;
  margin-left: 50px; 
  }
}

@media screen  and (max-width: 640px) {
  .left { 
    width: 100%;
    display: inline-table;  
    margin-bottom: 25px;
  }
  .right {
    width: 85%;
    display: inline-table;
    margin-left: 20px;  
  }
  .signform { 
    width: 50%;
    min-width:255px;
  }
  form#login-form:before {
    content: ''; 
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    margin: auto;
   
}
  form#login-form:after {
    content: '';
    position: absolute;   
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    margin: auto;
    height:0em;
    width: 0em;
    left: 0em;
    top: 0em;
    
  }
}
@media screen  and (max-width: 1500px) and (min-width: 650px) {
form#login-form::before {
    content: 'or';
    color: #79b42b;
    position: absolute;   
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    margin: auto;
    height: 0.5em;
    width: 0.5em;
    left: 1.5em;
    top: 4.2em;
    z-index: 900;
  }
  form#login-form::after {
    content: '';
    position: absolute;
    background: rgba(128, 128, 128, 0.3);
    top: 0;
    right: 0;
    left: 0;
    bottom: 15px;
    margin: auto;
    height: 3.25em;
    width: 0.1em;
    left: 2.15em;
    top: -2.8em;
    -moz-box-shadow: 0 8.8em 0 0 rgba(128, 128, 128, 0.3);
    -webkit-box-shadow: 0 8.8em 0 0 rgba(128, 128, 128, 0.3);
    box-shadow: 0 8.8em 0 0 rgba(128, 128, 128, 0.3);
}
}
@media screen  and (max-width: 640px) and (min-width: 460px) {
.signform { 
  left: 25%;
   }
}
@media screen  and (max-width: 460px) and (min-width: 400px) {
  .signform {
   left: 20%; 
  }
}
@media screen  and (max-width: 400px) and (min-width: 320px) {
  .signform {
   left: 10%; 
  }
}
@media screen  and (max-width: 320px) {
  .signform {
   left: 1%;
    }
}
.bts i{ 
    margin: 15px 15px 15px 20px;
    float: left;
    width: 5%;
    font-size: 20px;
    margin-left: 20px;
}
<style>