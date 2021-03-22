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
		.menu-left {clear: left; font-size: 15px; margin-bottom: 2px;}
		.menu-right {float: right; font-size: 12px; margin-bottom: 2px;}
		.field {clear: left; float: left; font-size: 12px;}
		.field1 {float: left; margin-left: 2px; font-size: 12px;}
		.field2 {float: left; margin-left: 2px; font-size: 12px;}
	</style>
	<script type="text/javascript">
		function show_usr_confirm() {
			var r=confirm("Вы уверены?");
			if (r==true) {
				window.parent.location.href="./action.php?do=userdel&id=<?PHP echo $_GET['id'] ?>";
				}
			}
		function show_nar_confirm() {
			var r=confirm("Вы уверены?");
			if (r==true) {
				window.parent.location.href="./action.php?do=narushdel&id=<?PHP echo $_GET['id'] ?>";
				}
			}
		function show_kat_confirm() {
			var r=confirm("Вы уверены?");
			if (r==true) {
				window.parent.location.href="./action.php?do=kategdel&id=<?PHP echo $_GET['id'] ?>";
				}
			}
	</script>
</head>
<body>

<div class="menu-left"><a href="./view.php">Просмотр</a>&nbsp;|&nbsp;<a href="./edit.php?id=new">Добавление</a>&nbsp;|&nbsp;<a href="./admin.php">Управление</a></div>
<?PHP

//Проверка авторизации
include './includes/func.php';
include './includes/config.php';

if (!check_auth()) {
	$_SESSION['from'] = $_SERVER['REQUEST_URI'];
	header("Location: ./auth.php");
	}
	
if (isset($_POST['save'])) { $_SESSION['admin'] = $_POST['adminpass']; }

//Если не введен пароль выводим форму
if (!isset($_SESSION['admin']) or $_SESSION['admin'] != $Admin_password) {
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html>
		<head>
			<title>Авторизация</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<style type="text/css">
				body,td,div,p,a,font,span {font-family: arial,sans-serif}
				table {border: 1px solid #9999FF}
			</style>
		</head>
		<body>
			<table align="center" cellpadding="7" cellspacing="0" border="0">
			<tr><td align="center" colspan="2">Управление БД<br><br></td></tr>
			<tr><td align="right">
			<form action="admin.php" method="post">
			</td></tr>
			<tr><td align="right">	
				<font size="-1">Введите пароль администратора:</font>
			</td><td align="right">	
				<input type="password" size=22 maxlength=30 name="adminpass"><br>
			</td></tr>
			<tr><td align="right" colspan="2">
				<br>
				<input type="submit" name="save" value="Войти">
			</td></tr>
			</form>
			</table>
		</body>
	</html>
	<?PHP
	exit;
	}
//include './header.php';

//Выводим админку
if (!isset($_GET['do'])) {
	$mysqlconnect = my_connect();
	?>
	<div class="field">
	<table class="form" cellspacing="7" border="0">
	<tr><td align="center">Пользователи:</td></tr>
	<?PHP
		$query = "SELECT id, fio FROM users ORDER BY fio";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		while ($user = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			echo '<tr><td><a href="./admin.php?do=userchange&id='.$user['id'].'">'.$user['fio'].'</a></td></tr>';
			}
	?>
	<tr><td align="center"><input type="button" value="Добавить" onclick="location.href='./admin.php?do=useradd'" /></td></tr>
	</table>
	</div>

	<div class="field1">
	<table class="form" cellspacing="7" border="0">
	<tr><td align="center">Нарушения:</td></tr>
	<?PHP
		$query = "SELECT id, text FROM narush ORDER BY text";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		while ($narush = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			echo '<tr><td><a href="./admin.php?do=narushchange&id='.$narush['id'].'">'.$narush['text'].'</a></td></tr>';
			}
	?>
	<tr><td align="center"><input type="button" value="Добавить" onclick="location.href='./admin.php?do=narushadd'" /></td></tr>
	</table>
	</div>

	<div class="field2">
	<table class="form" cellspacing="7" border="0">
	<tr><td align="center">Категории:</td></tr>
	<?PHP
		$query = "SELECT id, text FROM kateg ORDER BY text";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		while ($kateg = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			echo '<tr><td><a href="./admin.php?do=kategchange&id='.$kateg['id'].'">'.$kateg['text'].'</a></td></tr>';
			}
	?>
	<tr><td align="center"><input type="button" value="Добавить" onclick="location.href='./admin.php?do=kategadd'" /></td></tr>
	</table>
	</div>
	</body>
	</html>
	<?PHP
	
	exit;
	}

//Выводим форму изменения пользователя
if ($_GET['do'] == 'useradd' or $_GET['do'] == 'userchange') {
	if ($_GET['do'] != 'useradd') {
		$mysqlconnect = my_connect();
		$query = "SELECT fio FROM users WHERE id = '$_GET[id]'";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		$data['fio'] = '';
		}
	$data['password'] = '';
	?>
	<div class="field">
	<table class="form" cellspacing="7" border="0">
	<?PHP
	if ($_GET['do'] == 'userchange') {
	?><form action="action.php?do=userchange&id=<?PHP echo $_GET['id'] ?>" method="post"><?PHP
	} else {
	?><form action="action.php?do=useradd" method="post"><?PHP
	}
	?><tr>
	<td align="right"><font size="-1">ФИО:</font></td><td align="left"><?PHP echo '<input size=30 maxlength=30 name="fio" value="'.$data['fio'].'"></td>'; ?>
	<td align="right"><font size="-1">Пароль:</font></td><td align="left"><?PHP echo '<input type="password" size=15 maxlength=15 name="password" value="'.$data['password'].'"></td>'; ?>
	</tr><tr>
	<?PHP
	if ($_GET['do'] == 'userchange') {
	?><td colspan="2" align="left"><input class="text-size-center" type="submit" name="save" value="Изменить"></td><td align="right" colspan="2"><input type="button" onclick="show_usr_confirm()" value="Удалить" /></td>
	</tr><tr>
	<td colspan="4" align="center">Если вы не желаете изменять пароль, оставьте поле ввода пустым.</td>
	<?PHP
	} else {
	?><td colspan="4" align="left"><input class="text-size-center" type="submit" name="save" value="Добавить"></td>
	</tr>
	</table>
	</div>
	</body>
	</html>
	<?PHP
	}
}

//Выводим форму изменения нарушения
if ($_GET['do'] == 'narushadd' or $_GET['do'] == 'narushchange') {
	if ($_GET['do'] != 'narushadd') {
		$mysqlconnect = my_connect();
		$query = "SELECT text FROM narush WHERE id = '$_GET[id]'";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		$data['text'] = '';
		}
	?>
	<div class="field">
	<table class="form" cellspacing="7" border="0">
	<?PHP
	if ($_GET['do'] == 'narushchange') {
	?><form action="action.php?do=narushchange&id=<?PHP echo $_GET['id'] ?>" method="post"><?PHP
	} else {
	?><form action="action.php?do=narushadd" method="post"><?PHP
	}
	?><tr>
	<td align="right"><font size="-1">Нарушение:</font></td><td align="left"><?PHP echo '<input size=30 maxlength=30 name="narush" value="'.$data['text'].'"></td>'; ?>
	</tr><tr>
	<?PHP
	if ($_GET['do'] == 'narushchange') {
	?><td align="left"><input class="text-size-center" type="submit" name="save" value="Изменить"></td><td align="right"><input type="button" onclick="show_nar_confirm()" value="Удалить" /></td><?PHP
	} else {
	?><td colspan="2" align="left"><input class="text-size-center" type="submit" name="save" value="Добавить"></td>
	</tr>
	</table>
	</div>
	</body>
	</html>
	<?PHP
	}
}

//Выводим форму изменения категории
if ($_GET['do'] == 'kategadd' or $_GET['do'] == 'kategchange') {
	if ($_GET['do'] != 'kategadd') {
		$mysqlconnect = my_connect();
		$query = "SELECT text FROM kateg WHERE id = '$_GET[id]'";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		$data['text'] = '';
		}
	?>
	<div class="field">
	<table class="form" cellspacing="7" border="0">
	<?PHP
	if ($_GET['do'] == 'kategchange') {
	?><form action="action.php?do=kategchange&id=<?PHP echo $_GET['id'] ?>" method="post"><?PHP
	} else {
	?><form action="action.php?do=kategadd" method="post"><?PHP
	}
	?><tr>
	<td align="right"><font size="-1">Категория:</font></td><td align="left"><?PHP echo '<input size=10 maxlength=10 name="kateg" value="'.$data['text'].'"></td>'; ?>
	</tr><tr>
	<?PHP
	if ($_GET['do'] == 'kategchange') {
	?><td align="left"><input class="text-size-center" type="submit" name="save" value="Изменить"></td><td align="right"><input type="button" onclick="show_kat_confirm()" value="Удалить" /></td><?PHP
	} else {
	?><td colspan="2" align="left"><input class="text-size-center" type="submit" name="save" value="Добавить"></td>
	</tr>
	</table>
	</div>
	</body>
	</html>
	<?PHP
	}
}