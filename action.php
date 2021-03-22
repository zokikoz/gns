<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>БД лишенных ВУ</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
		body,td,div,p,a,font,span {font-family: arial,sans-serif}
		A:visited {color: #0000FF}
		A:hover {color: #FF5577}
		A {color: #0000FF; text-decoration: none}
	</style>
</head>
<body>

<?PHP

//Проверка авторизации
include './includes/func.php';
include './includes/config.php';

if (!check_auth()) {
	$_SESSION['from'] = $_SERVER['REQUEST_URI'];
	header("Location: ./auth.php");
	}

//Изменяем данные
if ($_GET['do'] == 'change') {
	
	$checked_input = input_check();

	$mysqlconnect = my_connect();
	
	$query = "UPDATE main SET fam = '$_POST[fam]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET imj = '$_POST[imj]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		
	$query = "UPDATE main SET otch = '$_POST[otch]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET god_rozh = '$_POST[god_rozh]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET vu_ser = '$_POST[vu_ser]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET vu_nom = '$_POST[vu_nom]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET kateg = '$checked_input[kateg]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET narush = '$_POST[narush]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET organ = '$_POST[organ]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET srok = '$_POST[srok]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET reg_num = '$_POST[reg_num]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	if(isset($_POST['izyato'])) { $izyato = 1; } else { $izyato = 0; }
	$query = "UPDATE main SET izyato = '$izyato' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET dop_info = '$_POST[dop_info]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$query = "UPDATE main SET date_lishen = '$checked_input[date]' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	$date = date('Y-m-d');
	$query = "UPDATE main SET date = '$date' WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	header("Location: ./view.php?id=$_GET[id]");
	
	}

//Добавляем данные
if ($_GET['do'] == 'add') {
	
	$checked_input = input_check();
	$date = date('Y-m-d');
	
	//Заносим строку в таблицу
	$insert_string1 = "INSERT INTO main (date, fam";
	$insert_string2 = " VALUES ('$date', '$_POST[fam]'";
	
	if ($_POST['imj'] != '') {
		$insert_string1 .= ", imj";
		$insert_string2 .= ", '$_POST[imj]'";
		}
	if ($_POST['otch'] != '') {
		$insert_string1 .= ", otch";
		$insert_string2 .= ", '$_POST[otch]'";
		}
	if ($_POST['god_rozh'] != '') {
		$insert_string1 .= ", god_rozh";
		$insert_string2 .= ", '$_POST[god_rozh]'";
		}
	if ($_POST['vu_ser'] != '') {
		$insert_string1 .= ", vu_ser";
		$insert_string2 .= ", '$_POST[vu_ser]'";
		}
	if ($_POST['vu_nom'] != '') {
		$insert_string1 .= ", vu_nom";
		$insert_string2 .= ", '$_POST[vu_nom]'";
		}
	if ($checked_input['kateg'] != '') {
		$insert_string1 .= ", kateg";
		$insert_string2 .= ", '$checked_input[kateg]'";
		}
	if ($_POST['narush'] != '') {
		$insert_string1 .= ", narush";
		$insert_string2 .= ", '$_POST[narush]'";
		}
	if ($_POST['organ'] != '') {
		$insert_string1 .= ", organ";
		$insert_string2 .= ", '$_POST[organ]'";
		}
	if ($_POST['srok'] != '') {
		$insert_string1 .= ", srok";
		$insert_string2 .= ", '$_POST[srok]'";
		}
	if ($_POST['reg_num'] != '') {
		$insert_string1 .= ", reg_num";
		$insert_string2 .= ", '$_POST[reg_num]'";
		}
	if(isset($_POST['izyato'])) { $izyato = 1; } else { $izyato = 0; }
		$insert_string1 .= ", izyato";
		$insert_string2 .= ", '$izyato'";
		
	if ($_POST['dop_info'] != '') {
		$insert_string1 .= ", dop_info";
		$insert_string2 .= ", '$_POST[dop_info]'";
		}
	if ($checked_input['date'] != '') {
		$insert_string1 .= ", date_lishen";
		$insert_string2 .= ", '$checked_input[date]'";
		}
		
	$insert_string1 .= ")";
	$insert_string2 .= ")";
	
	$mysqlconnect = my_connect();
	//echo $insert_string1.$insert_string2;
	$result = mysqli_query($mysqlconnect, $insert_string1.$insert_string2) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	header("Location: ./view.php");
	}
	
//Удаляем данные
if ($_GET['do'] == 'delete') {
	$mysqlconnect = my_connect();
	$query = "DELETE FROM main WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	header("Location: ./view.php");
	}

//Изменяем пользователя
if ($_GET['do'] == 'userchange' and isset($_SESSION['admin'])) {
	$mysqlconnect = my_connect();
	if ($_POST['fio'] != '') {
		$query = "UPDATE users SET fio = '$_POST[fio]' WHERE id = '$_GET[id]' LIMIT 1";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		}
	if ($_POST['password'] != '') {
		$password = hash('md5', $_POST['password']);
		$query = "UPDATE users SET password = '$password' WHERE id = '$_GET[id]' LIMIT 1";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		}
	
	header("Location: ./admin.php");
	}

//Вносим данные пользователя
if ($_GET['do'] == 'useradd' and isset($_SESSION['admin'])) {
	if ($_POST['fio'] != '' and $_POST['password'] != '') {
		$mysqlconnect = my_connect();
		$password = hash('md5', $_POST['password']);
		$query = "INSERT INTO users (fio, password) VALUES ('$_POST[fio]', '$password')";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		}

	header("Location: ./admin.php");
	}

//Удаляем данные пользователя
if ($_GET['do'] == 'userdel' and isset($_SESSION['admin'])) {
	$mysqlconnect = my_connect();
	$query = "DELETE FROM users WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	header("Location: ./admin.php");
	}

//Изменяем нарушение
if ($_GET['do'] == 'narushchange' and isset($_SESSION['admin'])) {
	if ($_POST['narush'] != '') {
		$mysqlconnect = my_connect();
		$query = "UPDATE narush SET text = '$_POST[narush]' WHERE id = '$_GET[id]' LIMIT 1";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		}
		
	header("Location: ./admin.php");
	}

//Добавляем нарушение
if ($_GET['do'] == 'narushadd' and isset($_SESSION['admin'])) {
	if ($_POST['narush'] != '') {
		$mysqlconnect = my_connect();
		$query = "INSERT INTO narush (text) VALUES ('$_POST[narush]')";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		}

	header("Location: ./admin.php");
	}

//Удаляем нарушения
if ($_GET['do'] == 'narushdel' and isset($_SESSION['admin'])) {
	$mysqlconnect = my_connect();
	$query = "DELETE FROM narush WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	header("Location: ./admin.php");
	}

//Изменяем категорию
if ($_GET['do'] == 'kategchange' and isset($_SESSION['admin'])) {
	if ($_POST['kateg'] != '') {
		$mysqlconnect = my_connect();
		$query = "UPDATE kateg SET text = '$_POST[kateg]' WHERE id = '$_GET[id]' LIMIT 1";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		}
		
	header("Location: ./admin.php");
	}

//Добавляем категорию
if ($_GET['do'] == 'kategadd' and isset($_SESSION['admin'])) {
	if ($_POST['kateg'] != '') {
		$mysqlconnect = my_connect();
		$query = "INSERT INTO kateg (text) VALUES ('$_POST[kateg]')";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		}

	header("Location: ./admin.php");
	}

//Удаляем категорию
if ($_GET['do'] == 'kategdel' and isset($_SESSION['admin'])) {
	$mysqlconnect = my_connect();
	$query = "DELETE FROM kateg WHERE id = '$_GET[id]' LIMIT 1";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	
	header("Location: ./admin.php");
	}
?>
</body>
</html>