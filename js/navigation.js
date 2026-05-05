/**
 * www.lamosca.com -- navigation
 */

myServer = "/";


function navigate(selField) {
	
	var mySelection = selField.selectedIndex;
	var myValue = selField.getElementsByTagName("option")[mySelection].value;
	
	// subnavi projects
	if(selField.name == "js_project") {
	
		var catField = document.navForm.js_cat;
		var myCatSelection = catField.selectedIndex;
		var myCatValue = catField.getElementsByTagName("option")[myCatSelection].value;
		
		if(myCatValue == "index" || myValue == "index") {
			document.location.href = myServer;
		} else {
			gotoProject(myCatValue,myValue);
		}
		
	// main navigation
	} else {
		if(myValue == "index") {
			document.location.href = myServer;
		} else if(myValue == "mosaic") {
			document.location.href = myServer + "mosaic/";
		} else if(myValue == "tshirt") {
			openShirts();
		} else if(myValue == "info") {
			openInfo();
		} else {
			gotoCategory(myValue);
		}
	}

}

function gotoCategory(myValue) {
	document.location.href = myServer + myValue + "/";
}

function gotoProject(myCatValue,myValue) {
	document.location.href = myServer + myCatValue + "/" + myValue + ".html";
}

function indexnavigate(myCatValue,selField) {
	
	var mySelection = selField.selectedIndex;
	var myValue = selField.getElementsByTagName("option")[mySelection].value;
	
	if(myValue != "") 
		gotoProject(myCatValue,myValue);
}

function openShirts() {
	var mWidth = 820;
	var mHeight = 670;
	var leftPos = (screen.width - mWidth) / 2;
	var topPos = (screen.height - mHeight) / 2;
	tshirt = window.open('http://www.lamosca.com/tshirt/coleccio.htm', 'Shirts', 'width='+mWidth+',height='+mHeight+',top='+topPos+',left='+leftPos+',location=yes,menubar=yes,toolbar=yes,scrollbars=yes,resizable=yes')
	tshirt.focus();
}

function openInfo() {
	document.location.href = "http://www.lamosca.info/";
	// info = window.open('http://www.lamosca.info/', 'Info')
	// info.focus();
}