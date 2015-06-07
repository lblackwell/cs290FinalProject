function showError(input, which)
{
	if(input.length == 0)
	{
		document.getElementById("error").innerHTML = "";
		return;
	}
	else
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
			{
				var putHere = which + "Error";
				document.getElementById(putHere).innerHTML = xmlhttp.responseText;
			}
		};
		xmlhttp.open("GET", "regerrors.php?input=" + input + "&which=" + which, true);
		xmlhttp.send();
	}
}