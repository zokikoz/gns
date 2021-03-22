<?PHP

//Функция подключения к MySQL
function my_connect() {
	
	include 'config.php';
	
	//Устанавливаем подключение к серверу mysql 
	$mysqlconnect = mysqli_connect("localhost", $Name, $Password);
	mysqli_query($mysqlconnect, "SET CHARACTER SET utf8");
	//Выбираем базу
	$db_selected = mysqli_select_db($mysqlconnect, $DataBase);
	if (!$db_selected) {
		exit ('Невозможно подключиться к ' . $DataBase . '<br>' . mysqli_error($mysqlconnect));
		}
	
	//Если mysql_connect вернула FALSE, значит произошла ошибка подключения
	//Выводим ошибку и прекращаем работу скрипта
	if (! $mysqlconnect ) { 
		$err = mysqli_error($mysqlconnect);
		echo "<br>Ошибка при подключении к базе данных: " . $err['message'];
		exit(); 
		}
		
	return $mysqlconnect;
	}
	
//Фунция проверки авторизации
function check_auth() {
	
	include 'config.php';
	
	if (session_id() == "") session_start(); 
	
	$mysqlconnect = my_connect();
		
	//Проверяем установлен ли флаг авторизации
	if (isset($_SESSION['successful_auth_uyc'])) {
		//Выбираем пользователя из базы
		mysqli_query($mysqlconnect, "SET CHARACTER SET utf8");
		$query = "SELECT password FROM users WHERE password = '$_SESSION[successful_auth_uyc]'";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if (isset($user['password'])) {
			return true;
			}
		return false;
		}
	
	return false;
	}

//Функция вывода данных
function show_list($result) {	

	$mysqlconnect = my_connect();

	//Шапка таблицы
	?><table class="list" width="100%" border="1" cellpadding="2">
	<tr><td>№</td><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>г.р.</td><td>Серия ВУ</td><td>Номер ВУ</td><td>Категории</td><td>Нарушение</td><td>Орган</td><td>Срок (мес)</td><td>Раскладка</td><td>Изъято</td><td>Лишен</td><td>Дополнительно</td></tr>
	<?PHP
	//Построчный вывод результата запроса в таблицу
	while ($list = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		if ($list['izyato'] == '0') { $izyato = 'НЕТ'; } elseif ($list['izyato'] == '1') { $izyato = 'ДА'; } else { $izyato = ''; }
		if (isset($list['date_lishen'])) { $list['date_lishen'] = date("d.m.Y", strtotime($list['date_lishen'])); }		
			?>
			<tr><td><?PHP echo '<a href="./edit.php?id='.$list['id'].'">'.$list['id'].'</a>' ?></td><td><?PHP echo $list['fam'] ?></td><td><?PHP echo $list['imj'] ?></td><td><?PHP echo $list['otch'] ?></td><td><?PHP echo $list['god_rozh'] ?></td><td><?PHP echo $list['vu_ser'] ?></td><td><?PHP echo $list['vu_nom'] ?></td><td><?PHP echo $list['kateg'] ?></td><td><?PHP echo $list['narush'] ?></td><td><?PHP echo $list['organ'] ?></td><td><?PHP echo $list['srok'] ?></td><td><?PHP echo $list['reg_num'] ?></td><td><?PHP echo $izyato ?></td><td><?PHP echo $list['date_lishen'] ?></td><td><?PHP echo $list['dop_info'] ?></td></tr>
			<?PHP
		}
	}

//Функция проверки вводимых значений
function input_check() {

	include 'config.php';
	$bad_input = false;
	$kateg = '';
		
	$mysqlconnect = my_connect();

	//Проверяем дату лишения
	$chk_date = explode(".", $_POST['date_lishen']);
	if (isset($chk_date[0]) and isset($chk_date[1]) and isset($chk_date[2]) and $chk_date[0] != '' and $chk_date[1] != '' and $chk_date[2] != '' and is_numeric($chk_date[0]) and is_numeric($chk_date[1]) and is_numeric($chk_date[2])) {
		if (checkdate($chk_date[1],$chk_date[0],$chk_date[2])) {
			//Приводим дату в MySQL-формат
			$mysql_date = $chk_date[2].'-'.$chk_date[1].'-'.$chk_date[0];
		} else {
		echo 'Неверная дата лишения: "'.$_POST['date_lishen'].'". Должна быть: ДД.ММ.ГГГГ<br>';
		$bad_input = true;
		}
	} else {
		echo 'Неверная дата лишения: "'.$_POST['date_lishen'].'". Должна быть: ДД.ММ.ГГГГ<br>';
		$bad_input = true;
	}
	
	//Проверяем год рождения
	if (!is_numeric($_POST['god_rozh']) or (substr($_POST['god_rozh'], 0, 2) != '19' and substr($_POST['god_rozh'], 0, 2) != '20' and substr($_POST['god_rozh'], 0, 2) != '21')) {
		echo 'Неверный год рождения: "'.$_POST['god_rozh'].'". Должен быть: ГГГГ (от 1900 до 2199)<br>';
		$bad_input = true;
	}
	
	//Проверяем срок лишения
	if (!is_numeric($_POST['srok'])) {
		echo 'Неверный срок лишения: "'.$_POST['srok'].'".<br>';
		$bad_input = true;
	}
	
	//Проверяем фамилию
	if ($_POST['fam'] == '') {
		echo 'Пустая фамилия<br>';
		$bad_input = true;
	}
	
	//Проверяем имя
	if ($_POST['imj'] == '') {
		echo 'Пустое имя<br>';
		$bad_input = true;
	}
	
	//Проверяем фамилию
	if ($_POST['otch'] == '') {
		echo 'Пустое отчество<br>';
		$bad_input = true;
	}
	
	//Приводим категории к строке для занесения в БД
	$query = "SELECT id, text FROM kateg ORDER BY text";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	while ($kateglist = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		if (isset($_POST[$kateglist['id']])) {
		$kateg .= $kateglist['text'].' ';
		}
	}
	$kateg = trim($kateg);
		
	if (!$bad_input) {
		$res['date'] = $mysql_date;
		$res['kateg'] = $kateg;
		return $res;
	} else {
		$_SESSION['continue_edit'] = true;
		$_SESSION['fam'] = $_POST['fam'];
		$_SESSION['imj'] = $_POST['imj'];
		$_SESSION['otch'] = $_POST['otch'];
		$_SESSION['god_rozh'] = $_POST['god_rozh'];
		$_SESSION['vu_ser'] = $_POST['vu_ser'];
		$_SESSION['vu_nom'] = $_POST['vu_nom'];
		$_SESSION['narush'] = $_POST['narush'];
		$_SESSION['organ'] = $_POST['organ'];
		$_SESSION['srok'] = $_POST['srok'];
		$_SESSION['reg_num'] = $_POST['reg_num'];
		$_SESSION['dop_info'] = $_POST['dop_info'];
		$_SESSION['date_lishen'] = $_POST['date_lishen'];
		if (isset($_POST['izyato'])) { $_SESSION['izyato'] = $_POST['izyato']; } else { $_SESSION['izyato'] = false; }
		$_SESSION['kateg'] = $kateg;
		
		echo '<br><a href="'.$_SERVER['HTTP_REFERER'].'">Назад</a>';
		exit;
	}
	return false;
	}
	
?>