function delete_confirm() {
	var r = confirm("Are you sure you want to delete the course?");
	if (r == true) {
		// OK
	} else {
		// CANCEL
	}
}

function ajax_execute(str) {
	// Creazione dell'oggetto
	if (window.XMLHttpRequest) {
		// codice per IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else { // codice per IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	// Callback
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			
		}
	}

	// Invio parametri
	xmlhttp.open("POST","includes/confirmPwd.php?",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("password="+p+"&confermapwd="+str);
}