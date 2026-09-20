<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
	
<?php
	$cnx=mysqli_connect("localhost","root","","animalbd");
	$ch=""
		if(isset($_POST["c1"]))
		{$ch=$ch."(chat,)";
			
		}
	if(isset($_POST["c2"]))
	{$ch=$ch."(chien,)"}
	if(isset($_POST["c3"]))
	{$ch}
	
	
	
	?>
		
	<body>
    <table border="1" class="tinfo">
        <tr>
            <th>
                id
            </th>
            <th>
                Type
            </th>
            <th>
                Nom
            </th>
            <th>
                Age
            </th>
            <th>
                Race
            </th>
        </tr>
        <tr>
            <td>Info 1</td>
            <td>Info 2</td>
            <td>Info 3</td>
            <td>Info 4</td>
            <td>Info 5</td>
        </tr>

    </table>
</body>
</html>