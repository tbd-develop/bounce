function saveEmailTemplate( )
{
	var editor = tinyMCE.get( "emailcontent" );
	var title = document.getElementById( "templatename").value;

	var url = "/settings/email/template/add";
	
	var params = "title=" + title + "&content=" + editor.getContent( );
	
	var http = new XMLHttpRequest();
	
	http.open("POST", url, true);
	
	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");
	
	http.onreadystatechange = function() { 
		if(http.readyState == 4 && http.status == 200) {
			if( http.responseText > "")
				alert( http.responseText );
			else
				alert( 'Template Saved');
		} 
	}
	
	http.send( params );
}
