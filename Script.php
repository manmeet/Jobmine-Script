#!/usr/bin/php
<?php
$url = "https://jobmine.ccol.uwaterloo.ca/psp/SS/?cmd=login";
$cookie = '/*HIDDEN*/cookies.txt';
$ch = curl_init();
$con = mysql_connect("HIDDEN_HOST","HIDDEN_USER","HIDDEN_PWD");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("HIDDEN_DB", $con);
$users = mysql_query("SELECT * FROM HIDDEN_TABLE1");
while($RunningUser = mysql_fetch_array($users))
{
$user = $RunningUser['HIDDEN_FIELD1'];
$pwd = decode5t($RunningUser['HIDDEN_FIELD2']);
$UserEmail = $RunningUser['HIDDEN_FIELD3'];
$loginDetails = "userid=$user"."&"."pwd=$pwd";
$ch = curl_init();
$userOld = mysql_query("SELECT * FROM HIDDEN_TABLE2 WHERE HIDDEN_FIELD='$user'");

$header=array(
  'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
  'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
  'Accept-Language: en-us,en;q=0.5',
  'Accept-Encoding: gzip,deflate',
  'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
  'Keep-Alive: 115',
  'Connection: keep-alive',
);
curl_setopt($ch,CURLOPT_HTTPHEADER,$header);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

curl_setopt($ch, CURLOPT_POSTFIELDS, $loginDetails);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$cookie = '/*HIDDEN*/cookies_'.$user.'.txt';
curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie);

$url2 = "https://jobmine.ccol.uwaterloo.ca/psc/SS/EMPLOYEE/WORK/c/UW_CO_STUDENTS.UW_CO_APP_SUMMARY";

curl_setopt($ch, CURLOPT_URL, $url2);
curl_setopt($ch,CURLOPT_HTTPHEADER,$header);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

curl_setopt($ch, CURLOPT_POSTFIELDS, $loginDetails);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch,CURLOPT_ENCODING,'');

$response = curl_exec($ch);
$is =  mb_detect_encoding($response,"auto",true);

//The strings to look for in the html response from jobmine...
  $fnd = "false, true);\"  class='PSHYPERLINK' >";
	$fnde = "</a>";
	$fnst = "class='PSDROPDOWNLIST_DISPONLY' id='UW_CO_APPSTATVW_UW_CO_APPL_STATUS$";
	$fnco = "class='PSEDITBOX_DISPONLY' id='UW_CO_JOBINFOVW_UW_CO_PARENT_NAME$";
	$fnid = "id='UW_CO_APPS_VW2_UW_CO_JOB_ID$";
	$pos = strpos($response, $fnd);
do
{
	//JOB ID
	$pus3 = strpos($response, $fnid);
	$response = substr($response, $pus3+strlen($fnid));
	$response = substr($response, strpos($response, "'>")+2);
	$jobid = substr($response, 0, strpos($response, "</span>"));
	
	
	//NAME OF JOB
	$pos = strpos($response, $fnd);
	$response = substr($response, $pos+strlen($fnd));
	$job = substr($response, 0, strpos($response, $fnde));
	if($job != "Edit Application")
	{
	
		//NAME OF COMPANY
		$pus2 = strpos($response, $fnco);
		$response = substr($response, $pus2+strlen($fnco));
		$response = substr($response, strpos($response, "'>")+2);
		$company = substr($response, 0, strpos($response, "</span>"));
		
		//APPLICATION STATUS
		$pus = strpos($response, $fnst);
		$response = substr($response, $pus+strlen($fnst));
		$response = substr($response, strpos($response, "'>")+2);
		$status = substr($response, 0, strpos($response, "</span>"));
		$Alreadythere = false;
		if(($status=="Selected") || ($status=="Not Selected"))
		{
			$userOld = mysql_query("SELECT * FROM HIDDEN_TABLE2 WHERE HIDDEN_FIELD='$user'");
			while($row = mysql_fetch_array($userOld))
  			{
  				if($row['HIDDEN_FIELDa'] == $jobid)
  				{
  					if($row['HIDDEN_FIELDb'] == $status)
  					{
  						$Alreadythere = true;
  					}
  				}
  			}
  			if($Alreadythere == false)
  			{
  				$to = $UserEmail;
	 			$subject = "You Got $status";
 				$body = "$status - $job , $company";
 				$headers = 'From: admin@jobmine.com';
 				if (mail($to, $subject, $body, $headers)) {
 					$res = mysql_query("INSERT INTO HIDDEN_TABLE2 VALUES ('$user', '$jobid', '$status')");
   					echo("Message successfully sent!");
	  			} else {
   					echo("Message delivery failed...");
  				}
  			}
		}
	}
	$pos = strpos($response, $fnd);
}
while(!($pos===false));
curl_close($ch);
}
mysql_close($con);


//function to encrypt the string
function encode5t($str)
{
  //*HIDDEN*
  //Some combination of base64 encodings
  return $str;
}

//function to decrypt the string
function decode5t($str)
{
	//*HIDDEN*
  //Reverse combination of base64 encodings
  return $str;
}
?>