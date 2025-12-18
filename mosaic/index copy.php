

<?php
	require('functions.php');
	
	if(isset($nom)) {
	
		$db = mysql_connect($host, $db_user, $db_password) or die("Could not connect: " . mysql_error());
		
		$sqlstr = "SELECT id FROM $mosaictable WHERE nom = '".addslashes($nom)."' AND comentari = '".addslashes($comentari)."' ";
		$erg = mysql_db_query($dbname, $sqlstr);
		
		$rows=mysql_num_rows($erg);

		if($rows < 1) {
			if(isset($color1))
				$color = 3;
			else if(isset($color2))
				$color = 4;
			else if(isset($color3))
				$color = 1;
			else if(isset($color4))
				$color = 0;
			else if(isset($color5))
				$color = 2;
			$web = "http://" . $web;
			$web = str_replace("http://http://","http://",$web);
			
			$db = mysql_connect($host, $db_user, $db_password) or die("Could not connect: " . mysql_error());
			$sqlstr = "INSERT $mosaictable VALUES ";
			$sqlstr .= "('', '".addslashes($nom)."', '".addslashes($mail)."', '".addslashes($web)."', '" . addslashes($comentari) . "', $color, '". time() ."')";
			$erg = mysql_db_query($dbname, $sqlstr);
			mysql_close($db);
		}
	}
?>

<html>

<head>

<title>l a m o s c a</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="../css/lamosca.css" rel="stylesheet" type="text/css">

<script language="JavaScript">

<!--

j=0;	

function mark(mycolor) {
	for(i=1;i<=5;i++) {
		if(i != mycolor)
			eval("document.form1.color"+i+".checked = false");	 
	}
}

function checkForm() {
	if (document.form1.nom.value == "") {
		alert("Please enter your name!");
		document.form1.nom.focus();
		return false;
	}
	if (document.form1.comentari.value == "") {
		alert("Please enter your message!");
		document.form1.comentari.focus();
		return false;
	}
	for(i=1;i<=5;i++) {
		ischecked = eval("document.form1.color"+i+".checked");
		if(ischecked == true)
			i = 6;
	}
	if(ischecked != true) {
		alert("Please choose a colour!");
		return false;
	}
}


function MM_preloadImages() { //v3.0

  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();

    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)

    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}

}



function MM_swapImgRestore() { //v3.0

  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;

}



function MM_findObj(n, d) { //v3.0

  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {

    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}

  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];

  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;

}



function MM_swapImage() { //v3.0

  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)

   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}

}

//-->



</SCRIPT>

</head>



<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('img/col1-2.gif','img/col2-1.gif','img/col3-1.gif','img/col4-1.gif','img/col5-1.gif')">

<script language="JavaScript">


function tw(ident) 

{ 

www=window.open('info.php?id='+ident+'','Info','status=yes,width=266,height=172,left=0,top=0');

www.focus();

}

</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td valign="top"><table width="740" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td width="25" height="34">&nbsp;</td>

        <td width="359" height="34" valign="top">&nbsp;</td>

        <td width="356" height="34" align="left"><p></p>

            <p></p>

        </td>

      </tr>

      <tr>

        <td width="25">&nbsp;</td>

        <td width="359" valign="top"><img src="img/titol-nou.gif" width="69" height="19"></td>

        <td width="356" align="left">

          <form name="form1" METHOD="Post" ACTION="mosaic.php"  onSubmit="return checkForm()">

            <table width="356" border="0" cellspacing="0" cellpadding="0">

              <tr valign="bottom">

                <td height="22"><img src="img/name.gif" width="170" height="20"></td>

                <td height="22">&nbsp;</td>

                <td height="22" align="left"><img src="img/coment.gif" width="176" height="20"></td>

              </tr>

              <tr valign="top">

                <td width="170">

                  <input type="text" name="nom" style="font-size: 7pt; width : 170px;

					font-family: verdana, helvetica, sans-serif;border : 1px solid Black " tabindex="1" >

                </td>

                <td width="10">&nbsp;</td>

                <td rowspan="3" align="left">

                  <textarea name="comentari" style="font-size: 7pt; width : 170px;

					font-family: verdana, helvetica, sans-serif;border : 1px solid Black " rows="4" tabindex="4"></textarea>

                </td>

              </tr>

              <tr valign="top">

                <td width="170"><img src="img/mail.gif" width="170" height="19"></td>

                <td width="10">&nbsp;</td>

              </tr>

              <tr valign="top">

                <td width="170">

                  <input type="text" name="mail" style="font-size: 7pt; width : 170px;

					font-family: verdana, helvetica, sans-serif;border : 1px solid Black " tabindex="2" >

                </td>

                <td width="10">&nbsp;</td>

              </tr>

              <tr valign="top">

                <td width="170"><img src="img/web.gif" width="170" height="19"></td>

                <td width="10">&nbsp;</td>

                <td width="176" align="left"><img src="img/color.gif" width="176" height="19"></td>

              </tr>

              <tr valign="top">

                <td width="170">

                  <input type="text" name="web" style="font-size: 7pt; width : 170px;

					font-family: verdana, helvetica, sans-serif;border : 1px solid Black " value="http://" tabindex="3">

                </td>

                <td width="10">&nbsp;</td>

                <td width="176" align="left">

                  <table width="169" border="0" cellspacing="0" cellpadding="0" height="13">

                    <tr>

                      <td align="center" bgcolor="#F1BB00" height="18" width="24">

                        <input type="checkbox" name="color1" value="1" onClick="mark(this.value)" tabindex="5">

                      </td>

                      <td width="1" height="18"></td>

                      <td align="center" bgcolor="#E2E0CD" height="18" width="24">

                        <input type="checkbox" name="color2" value="2" onClick="mark(this.value)" tabindex="6">

                      </td>

                      <td width="1" height="18"></td>

                      <td align="center" bgcolor="#938156" height="18" width="24">

                        <input type="checkbox" name="color3" value="3" onClick="mark(this.value)" tabindex="7">

                      </td>

                      <td width="1" height="18"></td>

                      <td align="center" bgcolor="#D58137" height="18" width="24">

                        <input type="checkbox" name="color4" value="4" onClick="mark(this.value)" tabindex="8">

                      </td>

                      <td width="1" height="18"></td>

                      <td align="center" bgcolor="#763D12" height="18" width="24">

                        <input type="checkbox" name="color5" value="5" onClick="mark(this.value)" tabindex="9">

                      </td>

                      <td width="1" height="18"></td>

                      <td align="right" width="44" valign="bottom" height="18">

                        <input type="image" tabindex="10" border="0" name="submit" src="img/ok.gif" width="21" height="15">

                      </td>

                    </tr>

                  </table>

                </td>

              </tr>

              <tr>

                <td width="170"><img src="img/separa.gif" width="170" height="19"></td>

                <td width="10">&nbsp;</td>

                <td width="176" align="left"><img src="img/separa.gif" width="170" height="19"></td>

              </tr>

            </table>

          </form>

        </td>

      </tr>

      <tr>

        <td width="25" height="24">&nbsp;</td>

        <td width="359" height="24" valign="top"><img src="img/text.gif" width="149" height="116"></td>

        <td width="356" height="24" align="left" valign="top">

		<?php
			buildMosaic();
		?>

        </td>

      </tr>

    </table></td>

    <td width="15" valign="bottom"><img src="../imghome/firma.gif" width="14" height="139"></td>

  </tr>

</table>

</body>

</html>