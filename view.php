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
		.form {border: 1px solid #9999FF}
		.list {clear: left;	border: 1px solid #9999FF; border-collapse: collapse; font-size: 12px; margin-top: 5px;}
		.menu-left {float: left; font-size: 15px; margin-bottom: 2px;}
		.menu-right {float: right; font-size: 12px; margin-bottom: 2px;}
		.field {clear: left; width: 100%}
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
if (isset($_SESSION['admin'])) { unset($_SESSION['admin']); }

include './header.php';

if (!isset($_POST['save'])) {
	$_POST['imj'] = '';
	$_POST['fam'] = '';
	$_POST['otc'] = '';
	}

?>
<div class="field">
<table class="form" width="100%" cellspacing="7">
	<form action="view.php" method="post">
	<tr><td>
		<font size="-1">Фамилия:</font>&nbsp;<input size=30 maxlength=30 name="fam" value=<?PHP echo $_POST['fam']; ?>>&nbsp;
		<font size="-1">Имя:</font>&nbsp;<input size=30 maxlength=30 name="imj" value=<?PHP echo $_POST['imj']; ?>>&nbsp;
		<font size="-1">Отчество:</font>&nbsp;<input size=30 maxlength=30 name="otc" value=<?PHP echo $_POST['otc']; ?>>
	</td><td align="right">
		<input type="submit" name="save" value="Найти" align="right">
	</td></tr>
	</form>
</table>
</div>	
	<?PHP

if (isset($_POST['save'])) {
	//Выводим записи по поисковому запросу
	if ($_POST['imj'] == '') { $imj = '%'; } else { $imj = $_POST['imj']; }
	if ($_POST['fam'] == '') { $fam = '%'; } else { $fam = $_POST['fam']; }
	if ($_POST['otc'] == '') { $otc = '%'; } else { $otc = $_POST['otc']; }
	
	$mysqlconnect = my_connect();
	$query = "SELECT id, date, fam, imj, otch, god_rozh, vu_ser, vu_nom, kateg, narush, organ, srok, reg_num, izyato, date_lishen, dop_info FROM main WHERE imj LIKE '$imj%' and fam LIKE '$fam%' and otch LIKE '$otc%' ORDER BY id";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	show_list($result);
	?>
	</table>
	</body>
	</html>
	<?PHP
	
} else {
	//Выводим последние записи или запись по запросу
	$mysqlconnect = my_connect();
	if (isset($_GET['id'])) {
		$query = "SELECT id, date, fam, imj, otch, god_rozh, vu_ser, vu_nom, kateg, narush, organ, srok, reg_num, izyato, date_lishen, dop_info FROM main WHERE id = '$_GET[id]'";	
	} else {
		$query = "SELECT id, date, fam, imj, otch, god_rozh, vu_ser, vu_nom, kateg, narush, organ, srok, reg_num, izyato, date_lishen, dop_info FROM main ORDER BY id DESC LIMIT 30";
	}
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	show_list($result);
	?>
	</table>
	</body>
	</html>
	<?PHP
}