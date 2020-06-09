<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		content="width=device-width, user-scalable=no, initial-scale=1.0, maxinum-scale=1.0, mininum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>zp</title>
	<link rel="stylesheet" href="mysite.css">	   
</head>
<body>
	<form class="padding">

<!--Вывод таблицы на форму-->
<?php
	require_once 'sql.php';

	//Табица коэффициентов и разряда.
	$query = mysqli_query($conn, "SELECT * FROM $db1");
	$numrows = mysqli_num_rows($query);
	$i = 0;

	echo '<table width="500" id="collapse"><tr>
	<th>Razryad</th>
	<th>Coefficient</th>
	<th>Date begin</th>
	<th>date end</th></tr><tr>';

	while ($row = mysqli_fetch_assoc($query)){
		$i++;
		echo '<td>'.$row['razryad_id'].'</td><td>'.$row['coeff'].'</td><td>'.$row['date_degin'].'</td><td>'
		.$row['date_end'].'</td></tr>';
	}

	echo '</table><br>';
	//Табица коэффициентов и разряда.

	//Таблица Работников
	$query = mysqli_query($conn, "SELECT * FROM $db2");
	$numrows = mysqli_num_rows($query);
	$i = 0;

	echo '<table width="500" id="collapse"><tr>
	<th>Id</th>
	<th>Fio</th>
	<th>Razryad</th>
	<th>Dolzhnost</th>
	<th>Oklad</th>
	<th>Nachisleniya</th>
	<th>Uderzhaniya</th>
	<th>Viplata</th></tr><tr>';
	
	while ($row = mysqli_fetch_assoc($query)){
		$i++;
		echo '<td>'.$row['id'].'</td><td>'.$row['fio'].'</td><td>'.$row['razryad_id'].'</td><td>'
		.$row['dolzhnost'].'</td><td>'.$row['oklad'].'</td><td>'.$row['nach'].'</td><td>'
		.$row['uderzh'].'</td><td>'.$row['viplata'].'</td></tr>';
	}

	echo '</table><br>';
	//Таблица Работников
?>
<!--Вывод таблицы на форму-->


<!--Подсчет зарплаты-->
<?php
	require_once 'sql.php';

	$link = mysqli_connect($servername, $user, $password, $dbname) 
		or die("Ошибка " . mysqli_error($link));
		
	if(isset($_POST['fio']) && isset($_POST['razryad_id']) && isset($_POST['id']) 
	&& isset($_POST['dolzhnost']) && isset($_POST['oklad']) && isset($_POST['nach'])
	&& isset($_POST['uderzh']) && isset($_POST['viplata'])){
 
    	$id = htmlentities(mysqli_real_escape_string($link, $_POST['id']));
    	$name = htmlentities(mysqli_real_escape_string($link, $_POST['fio']));
    	$razryad = htmlentities(mysqli_real_escape_string($link, $_POST['razryad_id']));
    	$dolzhnost = htmlentities(mysqli_real_escape_string($link, $_POST['dolzhnost']));
    	$oklad = htmlentities(mysqli_real_escape_string($link, $_POST['oklad']));
    	$nach = htmlentities(mysqli_real_escape_string($link, $_POST['nach']));
    	$uderzh = htmlentities(mysqli_real_escape_string($link, $_POST['uderzh']));
    	$viplata = htmlentities(mysqli_real_escape_string($link, $_POST['viplata']));
     
    	$query ="UPDATE person SET fio='$name', oklad='$oklad', nach='$nach', uderzh='$uderzh', viplata='$viplata' WHERE id='$id'";
    	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
	}

	if(isset($_GET['id']))
	{   
		$id = htmlentities(mysqli_real_escape_string($link, $_GET['id']));
		
		$query ="SELECT * FROM person WHERE id = '$id'";

		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
		//если в запросе более нуля строк
		if($result && mysqli_num_rows($result)>0) 
		{
			$row = mysqli_fetch_row($result); // получаем первую строку
			$name = $row[1];
			$razryad = $row[2];
			$dolzhnost = $row[3];
			$oklad = $row[4];
			$nach = $row[5];
			$uderzh = $row[6];
			$viplata = $row[7];
			
			//Калькулятор
				$a = $_POST['a'];
				$b = $_POST['b'];
				$oklad = $a * ($b/100);
				$nach = $oklad * (15/100);
				$uderzh = ($oklad - ($oklad *(10/100)) - 42500) * 0.1;
				$viplata = $oklad + $nach + $uderzh;
			//Калькулятор

			echo "<h2>Внесите данные</h2>
				<form method='POST'>
				<input type='hidden' name='id' value='$id' />

				<p>ФИО:<br> 
				<input type='text' name='fio' value='$name' size='n' /></p>
				
				<p>Разряд:<br> 
				<input type='text' name='razryad_id' value='$razryad' /></p>
				
				<p>Должность:<br> 
				<input type='text' name='dolzhnost' value='$dolzhnost' /></p>
				
				<p>Оклад:<br> 
				<input id='left' type='number' name='a' placeholder='Зарплата' />
				<input id='left' type='number' name='b' placeholder='Коэффициент' />
				<input class='submit' type='submit' name='sabmit' value='='>
				<input type='text' name='oklad' value='$oklad' /></p>
				
				<p>Доплата: <br> 
				<input type='number' name='nach' value='$nach' /></p>
				
				<p>Удержание: <br> 
				<input type='number' name='uderzh' value='$uderzh' /></p>
				
				<p>Выплата: <br> 
				<input type='number' name='viplata' value='$viplata' /></p>
				
				<input class='button' type='submit' value='Сохранить'>
				</form>";
			
			mysqli_free_result($result);
		}
	}
	mysqli_close($link);
?>
<!--Подсчет зарплаты-->
</form>
</body>
</html>