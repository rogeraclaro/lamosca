<html>
<!-- Whizzylink.php 
Copyright © 2005, John Goodman - john.goodman(at)unverse.net  *date 051115
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
A copy of the GNU General Public License can be obtained at: http://www.gnu.org/licenses/gpl.html
-->
<head>
<title>Whizzylink link browse v2</title>
<style>
body {font:85% sans-serif;}
</style>
<script type="text/javascript">
function WantThis(url) {
 window.opener.document.getElementById('lf_url').value = url;
 window.close();
}
</script>
<meta name="robots" content="noindex,nofollow">
</head>
<body>
<div id="files" >
Click a name below to select.<br>
<?php
$docpath = $_REQUEST['d'];
$extensions = $_REQUEST['x'] ? '/(' . $_REQUEST['x'] .')$/i' : '/\.(html|pdf|txt)$/i';
$d = $_SERVER['DOCUMENT_ROOT'] . '/' . $docpath;
$d = str_replace('//','/',$d);
$dir = opendir($d);
while ($file = readdir($dir)){
  $files[] = $file;
}
closedir($dir);
usort($files, "insensitive"); //see function insensitive($a, $b)
foreach ($files as $filename) {
  $filepath = "$d/$filename";
  $fsize = sprintf("%u", filesize($filepath)); //filesizes over 2Mb won't fit in an int so we unsign it
  $modtime = date ("d F Y H:i:s", filemtime($filepath)); //mtime is unix timestamp
  $tip = " Size: $fsize <br>Updated: $modtime ";
  if (is_dir($filepath) && $docpath) { //it's a directory
    if ($filename == '.'){ //current directory
      $dlist .= "<img src='/btn/dir.png'> $docpath ";
    } else if ($filename == '..') { //parent directory
      if($docpath) { //we're in a sub directory - no Up from root
        $updir = substr($docpath,0,strrpos($docpath,'/'));
        $dlist .= "<img src='/btn/back.png'><a href='$self?d=$updir'>Up</a>/<br>";
       }
    } else {
      $docpath = str_replace($_SERVER['DOCUMENT_ROOT'], "", $d);
      $dlist .= "<div style='float:left;width:20em'><img src='/btn/dir.png'><a href='$self?d=$docpath/$filename'>$filename</a></div>"; 
    }
  } else if (preg_match($extensions,$filename) ) {
      $flist .= "<div style='float:left;width:20em'><a href='#' onclick='WantThis(\"$docpath/$filename\")'>$filename</a></div>"; //it's a potential link
  }
}
echo $dlist . $flist;

function insensitive($a, $b) { //used by usort to sort file list case insensitive -------------------------
   return strcmp(strtolower($a), strtolower($b));
}
?>
</div>
</body>
</html>