<html>
<!-- Whizzypic.php v2 - resistant to no /images directory
Copyright © 2005, John Goodman - john.goodman(at)unverse.net  *date 051215
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
A copy of the GNU General Public License can be obtained at: http://www.gnu.org/licenses/gpl.html
-->
<head>
<title>Whizzypic image browse v2</title>
<style>
body {font:85% sans-serif;}
#picture {width:55%;height:100%;float:left;}
#files {height:100%;overflow:auto;margin-left:4em;font-size:90%;}
#caption{font-size:1.2em}
#preview {height:80%;width:100%}
</style>
<script type="text/javascript">
function WantThis(url) {
 window.opener.document.getElementById('if_url').value = url;
 window.close();
}
</script>
</head>
<body>
<div id="picture">
 <span id='caption'>Image preview</span><br><br>
 <iframe id='preview' src='/btn/image.gif'>
 </iframe>
</div>
<div id="files" >
Hover over a name below to preview, click it to select.<br>
<?php
$self = $_SERVER['SCRIPT_NAME'];
$docpath = $_REQUEST['d'] ? $_REQUEST['d'] : '/images';
$d = $_SERVER['DOCUMENT_ROOT'] . '/' . $docpath;
$d = str_replace('//','/',$d);
if (!is_dir($d)) $d = $_SERVER['DOCUMENT_ROOT'];
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
  if (is_dir($filepath)) { //it's a directory
    if ($filename == '.'){ //current directory
      $dlist .= "<img src='/btn/dir.png'> $docpath ";
    } else if ($filename == '..') { //parent directory
      if($docpath) { //we're in a sub directory - no Up from root
        $updir = substr($docpath,0,strrpos($docpath,'/'));
        $dlist .= "<img src='/btn/back.png'><a href='$self?d=$updir'>Up</a>/<br>";
       }
    } else if ($filename != 'bak') {
      $docpath = str_replace($_SERVER['DOCUMENT_ROOT'], "", $d);
      $dlist .= "<img src='/btn/dir.png'><a href='$self?d=$docpath/$filename'>$filename</a>/<br>"; 
    }
  } else if (strpos($filename, '.jpg') || strpos($filename, '.gif') || strpos($filename, '.png') || strpos($filename, '.ico') ) {
      $flist .= "<img src='/btn/image.png'><a href='#' onclick='WantThis(\"$docpath/$filename\")' onmouseover='document.getElementById(\"preview\").src=\"$docpath/$filename\";document.getElementById(\"caption\").innerHTML=\"<b>$filename</b><br>$tip\"'>$filename</a></br>"; //it's a picture
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