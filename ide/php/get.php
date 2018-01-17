<?php
session_start();

header ("Content-Type:text/xml");  
error_reporting(E_ERROR | E_PARSE);


$script = $_POST["script"];


if (strlen($script) == 0){
	die('<rs> <cmd title="Input Error!!" type="error"/></rs>');
}


//check if the site is hacked
//if the ide is called before?
if (!isset($_SESSION['time']) || empty($_SESSION['time'])){
    die ("Service not available!");
}

//if two consecutive executions are too close (less than 2 seconds)
if (time() - $_SESSION['time'] < 2){
	die ("<cmd title='Error' type = 'error'>Please wait a moment!</cmd>");
}

$script = str_replace( "'", '"', $script );

$soft   = "./soft";
$z3path = "$soft/z3/bin/z3";
$jar    = "$soft/schora.jar";
$java   = "java";
$cmd    = "$java -jar $jar '$script' $z3path";

//die ($cmd);

//set_time_limit(5);	//5 seconds
$f = popen("$cmd 2>&1", "r");

//read output of execution
while (!feof($f)) {
	$output .= fread($f, 1024);
}
pclose($f);

//response to client
echo "<rs>$output</rs>";
?>

