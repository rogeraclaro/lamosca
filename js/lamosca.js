function openWindow(url,xs,ys) {
	var mWidth = xs;
	var mHeight = ys;
	var leftPos = (screen.width - mWidth) / 2;
	var topPos = (screen.height - mHeight) / 2;
	w = window.open(url, '_', 'width='+mWidth+',height='+mHeight+',top='+topPos+',left='+leftPos+',location=no,menubar=no,toolbar=no,scrollbars=yes,resizable=yes')
	w.focus();
}