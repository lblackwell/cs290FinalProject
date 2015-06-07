function checkCredentials()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var response;
			response = xmlhttp.responseText;

			if(!response)
			{
				return false;
			}
			else if(response === "<p>No error.</p>")
			{
				document.getElementById('loginForm').submit();
			}
			else
			{
				document.getElementById("error").innerHTML = response;
				return false;
			}
		}
	}

	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
	var params = 'username=' + username + '&password=' + password;

	xmlhttp.open("POST", "login2.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(params);

	return false;
};