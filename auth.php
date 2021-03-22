<?PHP
// Авторизация пользователя журнала

//При прямом обращение выставляем реферер
session_start();
if (!isset($_SESSION['from'])) {
	$_SESSION['from'] = './view.php';
	}

//Выводим форму запроса пока не получим необходимую информацию
if (isset($_POST['save']) or (isset($_POST['sotrpass']) and $_POST['sotr'] != 'none')) {
	check_login();
	} else {
	showform();
	}
	
function showform() {

	include './includes/func.php';
	
	//Устанавливаем подключение к БД
	$mysqlconnect = my_connect();
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
			<tr><td align="center" colspan="2">ВХОД В СИСТЕМУ<br><br></td></tr>
			<tr><td align="right">
			<form action="auth.php" method="post">
				<font size="-1">Сотрудник:</font>
			</td><td align="right">	
				<select name="sotr">
					<option value="none">выберите из списка</option>
					<?PHP
					//Создаем список пользователей
					$query = "SELECT id, fio FROM users ORDER BY fio";
					$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
					while ($userlist = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						echo '<option value="'.$userlist['id'].'">'.$userlist['fio'].'</option>';
						}
					?>
				</select>
			</td></tr>
			<tr><td align="right">	
				<font size="-1">Пароль:</font>
			</td><td align="right">	
				<input type="password" size=22 maxlength=30 name="sotrpass"><br>
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
	}

function check_login() {

	include './includes/func.php';
	include './includes/config.php';
	
	//Подключаемся к базе данных
	$mysqlconnect = my_connect();
	
	//Выбираем пользователя из базы
	mysqli_query("SET CHARACTER SET utf8");
	$query = "SELECT fio,password FROM users WHERE id = '$_POST[sotr]'";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
	//Если такой пользователь существует
	if (isset($user['password'])) {
		//Если его пароль соответствует введенному
		if ($user['password'] == (hash('md5', $_POST['sotrpass']))) {
			//Устанавливаем флаг успешной авторизации
			$_SESSION['successful_auth_uyc'] = $user['password'];
			$_SESSION['fio'] = $user['fio'];
			$_SESSION['user_id'] = $_POST['sotr'];
			//Возвращаемся на страницу с которой перешли
			//echo $_SESSION['from'];
			//die();
			//echo $_SESSION['fio'];
			header("Location: ".$_SESSION['from']);
			die();
			}
		}
	header("Location: ./auth.php");
	}
