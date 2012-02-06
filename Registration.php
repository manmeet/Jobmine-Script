<html>
<head><title>JOBMINE STATUS SCRIPT</title></head>
<body>
<p>Use this form to register or unregister yourself from the script</p>
<p>The login information is stored in encrypted form</p><br/>
<br/>
<form name="input" action="index.php" method="post">
Jobmine Username: <input type="text" name="user" /><br/>
Jobmine Password: <input type="password" name="pwd" /><br/>
Email to receive Update: <input type="text" name="email" /><br/>
<input type="submit" name="Submit" value="Register" /><br/>
<input type="submit" name="Submit" value="UnRegister" /><br/>
</form> 
<?php
if($_POST['Submit']=='Register')
{
  echo "<p>Registering you...</p>";
	if(($_POST['user']=="")||($_POST['pwd']=="")||($_POST['email']==""))
	{
		echo "<p>please enter all information<br/></p>";
	}
	else
	{
		$con = mysql_connect("HIDDEN_HOST","HIDDEN_USER","HIDDEN_PASS");
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  		}
		mysql_select_db("HIDDEN_DB", $con);
		$pwd = encode5t($_POST['pwd']);
		$state = "INSERT INTO HIDDEN_TABLE VALUES ('$_POST[user]', '$pwd', '$_POST[email]')";
		//echo $state;
		$res = mysql_query($state);
		
		if ($res == true)
		{
			echo "<p>Successfully added to script<br/>You will shortly recieve emails regarding the jobs you have been selected or rejected for as they are saved into the server<br/></p>";
		}
		else
		{
			echo "<p>ERROR: Unable to add to script.<br/> Please contact manmeet.maggu@gmail.com</p>";
		}
	}
}
else if($_POST['Submit']=='UnRegister')
{
	echo "<p>UnRegistering you....</p>";
	if(($_POST['user']=="")||($_POST['pwd']==""))
	{
		echo "<p>please enter username and password<br/></p>";
	}
	else
	{
		$con = mysql_connect("HIDDEN_HOST","HIDDEN_USER","HIDDEN_PASS");
		if (!$con)
  		{
  			die('Could not connect: ' . mysql_error());
  		}
		mysql_select_db("HIDDEN_DB", $con);
		$pwd = encode5t($_POST['pwd']);
		$state = "SELECT * FROM HIDDEN_TABLE WHERE HIDDEN_FIELD='$_POST[user]'";
		$res = mysql_query($state);
		$foundsome = false;
		while($row = mysql_fetch_array($res))
  			{
  				$foundsome = true;
  				$encodepwd = encode5t($_POST['pwd']);
  				if($row['pwd'] == $encodepwd)
  				{
  					$rem = mysql_query("DELETE FROM HIDDEN_TABLE WHERE HIDDEN_FIELD='$_POST[user]'");
  					if ($rem == true)
					{
						echo "<p>Successfully removed from script</p>";
					}
					else
					{
						echo "<p>ERROR: Unable to remove from script.<br/> Please contact manmeet.maggu@gmail.com</p>";
					}
  				}
  				else
  				{
  					echo "<p>ERROR: password mismatch..please try again!</p>";
  				}
  			}
  			if($foundsome == false)
  			{
  				echo "<p>ERROR: You were not found on the script..Please contact manmeet.maggu@gmail.com for further info</p>";
  			}
	}
}
function encode5t($str)
{
  //*HIDDEN*
  //Some combination of base64 encodes
  return $str;
}
?>

</body>
</html>