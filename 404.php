<?php
//After upload you need to set this page as the ErrorDocument in .htaccess using
//
//ErrorDocument 404 /404.php
//
//This PHP Script creates a 404 Page with the ability to check if the person just put the file in a wrong Case Sensitivty, wrong extension, or missing a letter.
//Example would be, if I own the file named FileName.php
//A member can go to FILENAME.PHP and filename.php and be redirected to the correct page.
//A member can go to File.php or FileNa.php (if those 2 don't exist, it will find FileName.php)
//A member can go to filename.txt or filename.gif (if those 2 don't exist, it will find FileName.php)

//This file is filled with comments so you know what everything does.

//This function creates the current pages URL,
function CurrentPageURL()
{
$pageURL = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
$pageURL .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
return $pageURL;
}

$ur = basename($_SERVER['REQUEST_URI'], ""); //This gets the files name
$uri = substr($_SERVER['REQUEST_URI'], 1); //This gets the files directory and file name
$websitendir = urldecode(str_replace($ur, "", CurrentPageURL()));//This creates Website and Directory but no filename.
$pagedir = urldecode(str_replace($ur, "", $uri));//This removes the files name, to produce just the directory
$path_parts = pathinfo('./'.$pagedir.$ur); //Get the paths info
$nameWE = $path_parts['filename']; //Grabs the files name without the extention.

$stack = array (); //This is the start of the Files array

if ($handle = opendir('./'.$pagedir)) { //This opens the directory which the person 404'd on
    while (false !== ($file = readdir($handle))) { //This is a loop to create an array of all files in that directory
        if ($file != "." && $file != "..") {
            array_push ($stack,$file); //adding each file to the array snd returning it as $stack
        }
    }
    closedir($handle);
}


$stackNE = array (); //This is the start of the Files array
if ($handle = opendir('./'.$pagedir)) { //This opens the directory which the person 404'd on
    while (false !== ($file = readdir($handle))) { //This is a loop to create an array of all files in that directory
        if ($file != "." && $file != "..") {
		;
$tfile = explode(".", $file);
$nu = count($tfile);
$nu = 2-$nu;
array_push ($stackNE,$tfile[$nu]); //adding each file to the array snd returning it as $stack
        }
    }
    closedir($handle);
}
$stackNE = array_filter($stackNE);

$stack2 = explode(',',strtoupper(join(',',$stack))); //Creates the same array as Stack but all upper case
$index = array_search(strtoupper($ur), $stack2); //Grabs the Array index of where the filename they want to go to
$link = $websitendir.$stack[$index]; //Creates the link that the users are trying to get to

if($index){ //If Index doesnt exsist that means that the file they attempted to go to doesnt even exsist.
//Here we redirect to the correct link
header('Location: '.$link.'');
}else{
$newr = array (); //This is the start of the the new Files
foreach($stack2 as $key1=>$value1) {
  if(strpos($value1, strtoupper($ur))) {
  array_push ($newr,$value1);
  }
}
if($newr){ 
$nlink = $websitendir.$newr[0]; //Creates the new link
header('Location: '.$nlink.'');
}else{
$newr2 = array (); //This is the start of the the new Files with different extentions that exist.
$newextentions = array(1 => $nameWE.'.html',$nameWE.'.htm',$nameWE.'.gif',$nameWE.'.jpg',$nameWE.'.png',$nameWE.'.cgi',$nameWE.'.pl',$nameWE.'.js',$nameWE.'.java',$nameWE.'.class',$nameWE.'.asp',$nameWE.'.cfm',$nameWE.'.cfml',$nameWE.'.shtm',$nameWE.'.shml',$nameWE.'.php',$nameWE.'.php3');
foreach($newextentions as $key=>$value) {
if (file_exists( './'.$pagedir.$value)) {
  array_push ($newr2,$value);
}
}

if($newr2){
$nlink2 = $websitendir.$newr2[0]; //Creates the new link
header('Location: '.$nlink2.'');
}else{
//This is if the file doesnt exsist, as you see I put a simple 404 message

echo "<h3>404 File Not Found</h1>Sorry, the file you were looking for could not be found. It may have moved to a new location or could of just been temporary, or even *gulp* deleted.<br>
To go on to the main page of this site, click the link below:<br>
<a href = 'http://eput.my.id'>http://www.eput.my.id/</a>";
}
}
}
?>
