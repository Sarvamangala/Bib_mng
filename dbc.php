<?php
error_reporting( 0 );

/************* MYSQL DATABASE SETTINGS *****************
1. choose a database name to the attribute DB_NAME
2. choose a database host name from the Azure portal and paste it here
3. choose a database user name to the from Azure portal and paste it here.
4. Also choose a database password which is in the Azure portal

Note: If you use cpanel, the name will be like account_database
*************************************************************/

define ("DB_HOST", "us-cdbr-azure-west-c.cloudapp.net");
define ("DB_USER", "bf6b806fea1b5e");
define ("DB_PASS","9db6118b");
define ("DB_NAME","BibliogarphyDB");

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");

/* Registration Type (Automatic or Manual) 
 1 -> Automatic Registration (Activation code->approval)
 0 -> Manual Approval (NO Activation code)
*/
// PAGE PROTECT CODE for registered user


function page_protect()
{
    session_start();
    global $db;

// Session protection code
    
    if (isset($_SESSION['HTTP_USER_AGENT']))
    {
        if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
            {
                logout();
                exit;
            }
    }

// Cookie Access
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) )
    {
        header("Location: index.php");
        exit();

    }
}



function filter($data)
{
	$data = trim(htmlentities(strip_tags($data)));
	
	if (get_magic_quotes_gpc())
    $data = stripslashes($data);
	$data = mysql_real_escape_string($data);
	return $data;
}



function EncodeURL($url)
{
    $new = strtolower(ereg_replace(' ','_',$url));
    return($new);
}

function DecodeURL($url)
{
    $new = ucwords(ereg_replace('_',' ',$url));
    return($new);
}

function ChopStr($str, $len) 
{
    if (strlen($str) < $len)
        return $str;

    $str = substr($str,0,$len);
    
    if ($spc_pos = strrpos($str," "))
            $str = substr($str,0,$spc_pos);

    return $str . "...";
}	

function isEmail($email)
{
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function isUserID($username)
{
	if (preg_match('/^[a-z\d_]{5,20}$/i', $username))
    {
		return true;
	}
    else
    {
		return false;
	}
 }	
 
function isURL($url) 
{
	if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url))
    {
		return true;
	}
    else
    {
		return false;
	}
} 

function checkPwd($x,$y) 
{
    if(empty($x) || empty($y) )
    {
        return false;
    }
    if (strlen($x) < 4 || strlen($y) < 4)
    {
        return false;
    }

    if (strcmp($x,$y) != 0)
    {
        return false;
    }
    return true;
}

function GenPwd($length = 7)
{
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels
  
  $i = 0; 
    
  while ($i < $length)
  {
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
      if (!strstr($password, $char))
      {
      $password .= $char;
      $i++;
      }

  }

  return $password;

}

function GenKey($length = 7)
{
  $password = "";
  $possible = "0123456789abcdefghijkmnopqrstuvwxyz"; 
  
  $i = 0; 
    
  while ($i < $length)
  {
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       if (!strstr($password, $char))
       {
           $password .= $char;
           $i++;
       }

  }

  return $password;

}

function deleteLibrary($id)
{
	
	$sql = "DELETE FROM Reference WHERE id=".$id;
    mysql_query($sql);
}

function logout()
{
    global $db;
    session_start();
// Session delete
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_level']);
    unset($_SESSION['HTTP_USER_AGENT']);
    session_unset();
    session_destroy();
    header("Location: index.php");
}

// Password generation
function PwdHash($pwd, $salt = "crypt")
{
    if ($salt === null)
    {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else
    {
        $salt = substr($salt, 0, SALT_LENGTH);
    }
    return $salt . sha1($pwd . $salt);
}

function checkAdmin()
{
    if($_SESSION['user_level'] == ADMIN_LEVEL)
    {
        return 1;
    }
    else
    {
        return 0 ;
    }

}

?>