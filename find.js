function showError(zip)
{
	if(zip.length == 0)
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
				document.getElementById("error").innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "ziperrors.php?zip=" + zip, true);
		xmlhttp.send();
	}
}