<?php
include 'dbc.php';

$err = array();
// Filtering variables
foreach($_GET as $key => $value)
{
	$get[$key] = filter($value);
}

if ($_POST['doLogin']=='Login')
{

foreach($_POST as $key => $value)
{
	$data[$key] = filter($value);
}


$user_email = $data['usr_email'];
$pass = $data['pwd'];

  $user_cond = "Email='".$user_email."'";


$result = mysql_query("SELECT * FROM user WHERE ".$user_cond) or die (mysql_error()); 
$num = mysql_num_rows($result);

  // User Authentication
    if ( $num > 0 )
    {
        list($email,$pwd,$full_name,) = mysql_fetch_row($result);
        if ($pwd === PwdHash($pass))
        {
            if(empty($err))
            {

                // Provide session
                session_start();
                session_regenerate_id (true);

                // Provides session variables
                $_SESSION['email']= $email;
                $_SESSION['user_name'] = $full_name;
                $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
                header("Location: library.php");
            }
		}
		else
		{
            $err[] = "Invalid Login. Please try again with correct user email and password.";
		
		}
	}
    else
    {
		$err[] = "Error - Invalid login. No such user exists";
    }
}
					 


?>
<html>
<head>
<title>Login Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#logForm").validate();
  });
  </script>
<link href="style.css" rel="stylesheet" type="text/css">

</head>
</br></br>
<h1 align="center" color=>Welcome to Bibliography Manager</h1></br>
<body>

 <form action="index.php" method="post" name="logForm" id="logForm" >
  <h1>--- Registerd member login ---</h1>  
	  
  <div class="inset">
  <p>
	  <?php
	 //Error prompt
          if(!empty($err))
          {
              echo "<div class=\"msg\">";
              foreach ($err as $e)
              {
                  echo "$e <br>";
              }
              echo "</div>";
          }
	  	  
	  ?></p></br></br>
  <p>
    <label for="email">EMAIL ADDRESS</label>
    <input name="usr_email" type="text" class="required" id="txtbox" size="25"></td>
          
  </p>
  <p>
    <label for="password">PASSWORD</label>
    <input name="pwd" type="password" class="required password" id="txtbox" size="25"></td>
          
  </p>
    
  <p class="p-container">    
   <input name="doLogin" type="submit" id="doLogin3" value="Login">
    </br>        
  </p></div>
   
             
</form>
<h3><a align="center" href="register.php">***New member can register for FREE, click here to register</a><font color="#"> 
  </h3> </font>


</body>
</html>
