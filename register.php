<?php 


include 'dbc.php';

$err = array();
					 
if($_POST['doRegister'] == 'Register') 
{ 
// Filters false scripts
    foreach($_POST as $key => $value)
    {
        $data[$key] = filter($value);
    }

// SERVER SIDE VALIDATION


    if(empty($data['full_name']) || strlen($data['full_name']) < 4)
    {
        $err[] = "ERROR - Invalid name. Please enter atleast 3 or more characters for your name";

    }



// Checks mailid
    if(!isEmail($data['usr_email']))
    {
        $err[] = "ERROR - Invalid email address.";

    }
// Check User Passwords
    if (!checkPwd($data['pwd'],$data['pwd2']))
    {
        $err[] = "ERROR - Invalid Password or mismatch. Enter 5 chars or more";

    }
    $sha1pass = PwdHash($data['pwd']);
    $usr_email = $data['usr_email'];

    if(empty($err))
    {

        $sql_insert = "INSERT into `user`
  			(`email`,`password`,`DisplayName`
			)
		    VALUES
		    ('$usr_email','$sha1pass','$data[full_name]'
			)
			";

        mysql_query($sql_insert,$link) or die("Insertion Failed:" . mysql_error());


        $sql_insert = "INSERT into `Library`
  			(`DisplayName`,`OwnerEmail`
			)
		    VALUES
		    ('Trash','$usr_email'
			)
			";

          mysql_query($sql_insert,$link) or die("Insertion Failed:" . mysql_error());
		  
		  $sql_insert = "INSERT into `Library`
  			(`DisplayName`,`OwnerEmail`
			)
		    VALUES
		    ('Unfiled','$usr_email'
			)
			";

          mysql_query($sql_insert,$link) or die("Insertion Failed:" . mysql_error());




  header("Location: thankyou.php");  
  exit();

	 } 
 }					 

?>
<html>
<head>
<title>Signup Form</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>

  <script>
  $(document).ready(function(){
    $.validator.addMethod("username", function(value, element) {
        return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
    }, "Username must contain only letters, numbers, or underscore.");

    $("#regForm").validate();
  });
  </script>

<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td width="160" valign="top"><p>&nbsp;</p>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="732" valign="top"><p>
	<?php 
	 if (isset($_GET['done'])) { ?>
	  <h2>If you want you can<a href="login.php">login here</a>";
	 <?php exit();
	  }
	?></p>
      <h3 class="titlehdr">Free Signup</h3>
      <p>Please register a free account, 
        Please note that fields marked <span class="required">*</span> 
        are required.</p>
	 <?php	
	 if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";	
	   }
	 ?> 
	 
	  <br>
      <form action="register.php" method="post" name="regForm" id="regForm" >
        <table width="95%" border="0" cellpadding="3" cellspacing="3" class="forms">
          <tr> 
            <td colspan="2">Valid User Name <span class="required"><font color="#CC0000">*</font></span><br> 
              <input name="full_name" type="text" id="full_name" size="100" class="required"></td>
          </tr>
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>

          <tr> 
            <td colspan="2"><h4><strong>Login Details</strong></h4></td>
          </tr>

          <tr> 
            <td>Your Email<span class="required"><font color="#CC0000">*</font></span> 
            </td>
            <td><input name="usr_email" type="text" id="usr_email3" class="required email"> 
              <span class="example">** Valid email..</span></td>
          </tr>
          <tr> 
            <td>Password<span class="required"><font color="#CC0000">*</font></span> 
            </td>
            <td><input name="pwd" type="password" class="required password" minlength="5" id="pwd"> 
              <span class="example">** 5 chars minimum..</span></td>
          </tr>
          <tr> 
            <td>Retype Password<span class="required"><font color="#CC0000">*</font></span> 
            </td>
            <td><input name="pwd2"  id="pwd2" class="required password" type="password" minlength="5" equalto="#pwd"></td>
          </tr>
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>

        </table>
        <p align="center">
          <input name="doRegister" type="submit" id="doRegister" value="Register">
        </p>
      </form>

      </td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

</body>
</html>
