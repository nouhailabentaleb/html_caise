<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
	session_start();
	
	// Create connection to database
	$conn = new mysqli("localhost", "root", "", "tp_php");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
}
	function getArticleName($id,$conn)
	{
	$sql="SELECT `nomArticle` FROM `article` WHERE `id` = ".$id.";";
	$articleName = $conn->query($sql);
	$row= $articleName->fetch_assoc(); 
	return $row["nomArticle"];
	}
	
	function getArticlePrice($id,$conn)
	{
	$sql="SELECT `prix` FROM `article` WHERE `id` = ".$id.";";
	$articlePrice = $conn->query($sql);
	$row= $articlePrice->fetch_assoc(); 
	return $row["prix"];
	}

	?>
<html  ns="http://www.w3.org/1999/xhtml">
	<head>
<title> Caise </title>




    <!-- Core CSS -->
    <link rel="stylesheet" href="bts/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="bts/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="bts/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="bts/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="bts/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="bts/assets/js/config.js"></script>
















	</head>
	<body>
	<p>
     <form action="achat.php" method="post">
	 <p> 
	 code article: <input type="text" name="codeArticle" id="codeArticle" /> <br>
	 quantite: <input type="text" name="quantite" id="quantite" /> 
	 <input type="submit" class="btn btn-outline-dark" value="valider"/> </p>

	</form>

	<?php
	if(isset($_POST["codeArticle"]))
	{		
	$idArticle=$_POST["codeArticle"];
	$quantite=$_POST["Quantite"];
	$sql="INSERT INTO `achat` ( `idArticle`,`nomArticle` , `prixArticle` , `Quantite`) 
	VALUES ('".$idArticle."',  '".getArticleName($idArticle,$conn)."', '".getArticlePrice($idArticle,$conn)."', '".$quantite."');" ; 
	echo $sql; 
	$conn->query($sql);
	
	}
	
	
	
	echo "<h1> listes des achats</h1>";
	
	echo '<table border=1 class="table table-hover"> ';
	
	echo "<tr> <td><h1>image article</h1></td> <td><h1>nom Article</h1></td> <td><h1>quantité</h1></td> <td><h1>Prix</h1></td> </tr> ";

	$sql = "SELECT * FROM `achat`";
	$listAchat = $conn->query($sql);
	$somme=0;
	if ($listAchat->num_rows > 0) {
  // output data of each row
		while($row = $listAchat->fetch_assoc()) {
		
	$somme= $somme + $row["Quantite"] * $row["prixArticle"]; 
	echo "<tr> <td><img src=image/".$row["idArticle"].".jpeg /></td> <td><h1>".$row["nomArticle"]."</h1></td> <td><h1>".$row["Quantite"]."</h1></td> <td><h1>".$row["prixArticle"]."</h1></td></tr> ";

		}
	}
	
	echo "<tr> <td><h1> </h1></td> <td><h1> </h1></td> <td><h1> Prix Total</h1></td> <td><h1>".$somme."</h1></td> </tr> ";

	echo "</table>";
	
	?>
	
	<form action="achat.php" method="post">
	 <p> 
	 paiement : <input type="text" name="paiement" id="paiement" /> 
	 <input type="submit" class="btn btn-outline-dark" value="valider"/> </p>
	</form>
	
	<?php
	$pieces = array(200, 100, 50, 20,10,5,2,1);
	$paiement=0;
	if(isset($_POST["paiement"]))
	$paiement=$_POST["paiement"];
	{
		if($paiement<$somme)
		echo " ajouter d'autre pieces";
		else
		{
		$rest=$paiement-$somme;
		echo $rest."Dh =";
		
		
			for ($j=0 ; $j<count($pieces) ; $j++)
			{
				if($pieces[$j]<=$rest)
				{
					$rest=$rest-$pieces[$j];
					echo $pieces[$j]."Dh -";
					$j=$j-1;
				}
				
			}
		
		}
	}
	
	echo "<h1> listes des articles</h1>";
	
	echo '<table border=1 class="table table-dark table-striped-columns" >';
	
	echo "<tr> <td><h1>image article</h1></td> <td><h1>nom Article</h1></td> <td><h1>Prix</h1></td> </tr> ";

	$sql = "SELECT * FROM `article`";
	$listArticle = $conn->query($sql);
	echo $sql;
	if ($listArticle->num_rows > 0) {
  // output data of each row
		while($row = $listArticle->fetch_assoc()) {
		
		
	echo "<tr> <td><img src=image/".$row["id"].".jpeg /></td> <td><h1>".$row["nomArticle"]."</h1></td> <td><h1>".$row["prix"]."</h1></td> </tr> ";

		}
	}
	

	echo "</table>";

	?>
	</p>
	</body>
</html>
