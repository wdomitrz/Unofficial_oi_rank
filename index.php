
<html>
	<head>
		<link rel="stylesheet" href="arkusz.css" type="text/css">
		<meta http-equiv="content-type" content="text/html; charset=utf8">
		<title>Nieoficjalny ranking   etapu   Olimpiady Informatycznej</title>					<!--Potrzeba numeru Olimpiady i etapu-->
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
		<div id="header">
			<center><h1>Nieoficjalny ranking  etapu  Olimpiady Informatycznej</center>				<!--Potrzeba numeru Olimpiady i etapu-->
		</div>
		<div id="all">
			<div id="table">
			<?php
				if(isset($_POST["name"]) && isset($_POST["surname"]) && isset($_POST["problem_1"]) && isset($_POST["problem_2"]) && isset($_POST["problem_3"]) && isset($_POST["problem_4"]) && isset($_POST['g-recaptcha-response'])){																											  //Aby usunąć CAPTHCHĘ && isset($_POST['g-recaptcha-response'])
					$key="";																		//<!--Potrzeba klucza do CAPTCHY-->						//Aby usunąć CAPTHCHĘ usuń tą linijkę 
					$check=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$key.'&response='.$_POST['g-recaptcha-response']);	//Aby usunąć CAPTHCHĘ usuń tą linijkę 
					$ans=json_decode($check);																												//Aby usunąć CAPTHCHĘ usuń tą linijkę 
					if($ans->success){																														//Aby usunąć CAPTHCHĘ usuń tą linijkę 
						if(empty($_POST["name"]) || empty($_POST["surname"]) ){
							echo "<div class='error'>Wszytkie pola muszą być wypełnione!!!</div>";
						}
						else{
							if(strpos($_POST["name"], "\n") || strpos($_POST["surname"], "\n") || strpos($_POST["problem_1"], "\n") || strpos($_POST["problem_2"], "\n") || strpos($_POST["problem_3"], "\n") || strpos($_POST["problem_4"], "\n")  ) {
								echo "<div class='error'>Pola nie mogą zawierać znaków nowej linii!!!</div>";
							}
							else{
								if(!is_string($_POST["name"]) ||  !is_string($_POST["surname"])){
									echo "<div class='error'>Imię i nazwisko muszą być tekstem!!!</div>";
								}
								else{
									if( strlen($_POST["name"])>32+1 ||  strlen($_POST["surname"])>32+1 ){
										echo "<div class='error'>Imię i nazwisko nie mogą być dłuższe niż 32 znaki!!!</div>";
									}
									else{
										$name = $_POST["name"];
										$surname = $_POST["surname"];
										$problem_1 = $_POST["problem_1"];
										$problem_2 = $_POST["problem_2"];
										$problem_3 = $_POST["problem_3"];
										$problem_4 = $_POST["problem_4"];
										if(!is_numeric($_POST["problem_1"]) || !is_numeric($_POST["problem_2"]) || !is_numeric($_POST["problem_3"]) || !is_numeric($_POST["problem_4"])){
											echo "<div class='error'>Wynik musi być liczbą!!!</div>";
										}
										else{
											if( 0>$_POST["problem_1"] || 0>$_POST["problem_2"] || 0>$_POST["problem_3"] || 0>$_POST["problem_4"] || 100<$_POST["problem_1"] || 100<$_POST["problem_2"] || 100<$_POST["problem_3"] || 100<$_POST["problem_4"] ){
												echo "<div class='error'>Wynik musi być liczbą z przedziału [0,100]!!!</div>";
											}
											else{
												$sum = $problem_1+$problem_2+$problem_3+$problem_4;
												$file =	fopen("rank.txt","a"."\n");
												fwrite($file,$sum.":".$problem_1.":".$problem_2.":".$problem_3.":".$problem_4.":".$name.":".$surname."\n");
												fclose($file);
											}
										}
									}
								}
							}
						}
					}																																		//Aby usunąć CAPTHCHĘ usuń tą linijkę
					else{
						echo "<div class='error'>Wypełnij CAPTCHĘ!!!</div>";
					}
				}
			?>
			<?php
				$file = file("./rank.txt");
				$ile = count($file);
				$rank_unordered[$ile][7]=array();
				for($i=0;$i<$ile;$i++){
					$line = explode(":", $file[$i]);
					for($j=0;$j<7;$j++){
						$rank_unordered[$i][$j]=$line[$j];
					}
				}
				$rank[$ile][7]=array();
				$zero[7]=array();
				if(!isset($rank_unordered[0]));
					$zero=$rank_unordered[0];
				for($i=0;$i<$ile;$i++){
					if($zero>$rank_unordered[$i]){
						$zero=$rank_unordered[$i];
					}
				}
				for($g=0;$g<$ile;$g++){
					$wh=-1;
					$rank[$g]=$zero;
					for($i=0;$i<$ile;$i++){
						if($rank[$g]<$rank_unordered[$i]){
							$rank[$g]=$rank_unordered[$i];
							$wh=$i;
						}
					}
					$rank_unordered[$wh]=$zero;
				}
				echo "
					<table>
						<tr>
								<th>Pozycja</th>
								<th>Suma</th>
								<th>Imię</th>
								<th>Nazwisko</th>
								<th>Zadanie str</th>
								<th>Zadanie sum</th>
								<th>Zadanie kon</th>
								<th>Zadanie zam</th>
						</tr>
				";
				$lv=1;
				for($i=0;$i<$ile;$i++){
					if($i!=0){
						if($rank[$i -1][0]!=$rank[$i][0]){
							$lv=$i +1;
						}
					}
					echo "<tr>";
					echo "<td>".htmlspecialchars($lv)."</td>";
					for($j=0;$j<1;$j++){
						echo "<td>";
							echo htmlspecialchars($rank[$i][$j]);
						echo "</td>";
					}
					for($j=5;$j<7;$j++){
						echo "<td>";
							echo htmlspecialchars($rank[$i][$j]);
						echo "</td>";
					}
					for($j=1;$j<5;$j++){
						echo "<td>";
							echo htmlspecialchars($rank[$i][$j]);
						echo "</td>";
					}

					echo "</tr>";
				}
				echo "</table>"
			?>
			</div>
			<div id="submit">
				<form action="./" method="post">
						Imię: <input typa="text" name="name"></br>
						Nazwisko: <input typa="text" name="surname"></br>
						Liczba punktów za zadanie Strajki (str): <input typa="text" name="problem_1" maxlength=3></br>
						Liczba punktów za zadanie Suma cyfr (sum): <input typa="text" name="problem_2" maxlength=3></br>
						Liczba punktów za zadanie Kontenery (kon): <input typa="text" name="problem_3" maxlength=3></br>
						Liczba punktów za zadanie Zamek (zam): <input typa="text" name="problem_4" maxlength=3></br>
						<div class="g-recaptcha" data-sitekey=""></div>													<!--Potrzeba klucza do CAPTCHY		//Aby usunąć CAPTHCHĘ usuń tą linijkę-->
						<input type="submit" value="Dodaj do rankingu">
						
				</form>
			</div>
		</div>
		<div id="foot">
			 W razie wystąpienia błędów proszę pisać na adres:						 									<!--Potrzeba e-maila do zgłaszania błędów-->
		</div>
	</body>
</html>


<!-- Autor: Witalis Domitrz -->
