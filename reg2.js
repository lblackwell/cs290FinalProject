function checkInput()
{
	// TEST PRINT
	console.log("Before ready state change");

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var response;
			response = xmlhttp.responseText;

			// TEST PRINT
			console.log("Response (before if/else): " + response);

			if(response === "<p>No error.</p>")
			{
				// TEST PRINT
				console.log("No error response: " + response);

				document.getElementById('regForm').submit();
			}
			else
			{
				// TEST PRINT
				console.log("Error response: " + response);

				document.getElementById('error').innerHTML = response;
				return false;
			}
		}
	};

	// TEST PRINT
	console.log("About to send POST");

	var username = document.getElementById('username').value;
	var email = document.getElementById('email').value;
	var password = document.getElementById('password').value;
	var params = 'username=' +username + '&email=' + email + '&password=' + password;

	// TEST PRINT
	console.log("Username: " + username);
	console.log("Email: " + email);
	console.log("Password: " + password);
	console.log("Param string: " + 'username=' +username + '&email=' + email + '&password=' + password);

	xmlhttp.open("POST", "reg2.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(params);

	// TEST PRINT
	console.log("Sent POST");

	return false;
}