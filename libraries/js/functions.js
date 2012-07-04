var currentPost;

tinyMCE.init({
    	mode : 'none',
    	theme : 'advanced', 
    	plugins: 'save', 
    	theme_advanced_buttons3_add : 'save', 
    	save_onsavecallback : 'saveEdit', 
    	inline_styles : false
	});

window.onload = function()
{
	if ( window.addEventListener )
		document.body.addEventListener( 'dblclick', onDoubleClick, false );
	else if ( window.attachEvent )
		document.body.attachEvent( 'ondblclick', onDoubleClick );
};

window.unload = function( ) {
	closeEditor( );
}

function closeEditor( )
{
	if( currentPost != null)
		tinyMCE.execCommand( 'mceRemoveControl', false, currentPost);
}

function saveEdit( )
{
	var editor = tinyMCE.get( currentPost );
	
	var url = "/admin/post/update/";
	var params = "content=" + editor.getContent( ) + "&post=" + currentPost.substring( currentPost.indexOf( '_') + 1);
	
	var http = new XMLHttpRequest();
	
	http.open("POST", url, true);
	
	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");
	
	http.onreadystatechange = function() { //Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			if( http.responseText > "")
				alert( http.responseText );
		}
	}
	
	http.send( params );
	
	closeEditor( );
}

function onDoubleClick( ev ) 
{
	var evt = window.event || ev; 	
	var element = evt.target || evt.srcElement;
	
	do
	{
		element = element.parentNode;
	}while( element.className.indexOf( 'editable') == -1);
	
	if( element.nodeName.toLowerCase( ) == 'div' && 
		( element.className.indexOf( 'editable' ) != -1) )
	{
		if( currentPost != null)
			closeEditor( );
	
		currentPost = element.id;
				
		tinyMCE.execCommand('mceAddControl', false, element.id );
	}	
}