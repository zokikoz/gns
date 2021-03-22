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
	<script type="text/javascript">
		function show_confirm() {
			var r=confirm("Вы уверены?");
			if (r==true) {
				window.parent.location.href="./action.php?do=delete&id=<?PHP echo $_GET['id'] ?>";
				}
			}
	</script>
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

if (isset($_SESSION['continue_edit'])) {
	$data['fam'] = $_SESSION['fam'];
	$data['imj'] = $_SESSION['imj'];
	$data['otch'] = $_SESSION['otch'];
	$data['god_rozh'] = $_SESSION['god_rozh'];
	$data['vu_ser'] = $_SESSION['vu_ser'];
	$data['vu_nom'] = $_SESSION['vu_nom'];
	$data['kateg'] = $_SESSION['kateg'];
	$data['narush'] = $_SESSION['narush'];
	$data['organ'] = $_SESSION['organ'];
	$data['srok'] = $_SESSION['srok'];
	$data['reg_num'] = $_SESSION['reg_num'];
	$data['dop_info'] = $_SESSION['dop_info'];
	$data['date_lishen'] = $_SESSION['date_lishen'];
	$data['izyato'] = $_SESSION['izyato'];
} else {
	if ($_GET['id'] != 'new') {
		$mysqlconnect = my_connect();
		$query = "SELECT * FROM main WHERE id = '$_GET[id]'";
		$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
		$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if (isset($data['date_lishen'])) { $data['date_lishen'] = date("d.m.Y", strtotime($data['date_lishen'])); }
	} else {
		$data['fam'] = '';
		$data['imj'] = '';
		$data['otch'] = '';
		$data['god_rozh'] = '';
		$data['vu_ser'] = '';
		$data['vu_nom'] = '';
		$data['kateg'] = '';
		$data['narush'] = '';
		$data['organ'] = '';
		$data['srok'] = '';
		$data['reg_num'] = '';
		$data['dop_info'] = '';
		$data['date_lishen'] = '';
		$data['izyato'] = '';
	}
}

unset($_SESSION['continue_edit']);

?>
<div class="field">
<table class="form" cellspacing="7" border="0">
	<?PHP
	if ($_GET['id'] != 'new') {
	?><form action="action.php?do=change&id=<?PHP echo $_GET['id'] ?>" method="post"><?PHP
	} else {
	?><form action="action.php?do=add" method="post"><?PHP
	}
	?>
	<tr>
	<td align="right"><font size="-1">Фамилия:</font></td><td align="left"><?PHP echo '<input size=30 maxlength=30 name="fam" value="'.$data['fam'].'"></td>'; ?>
	<td align="right"><font size="-1">Год рождения:</font></td><td align="left"><?PHP echo '<input size=4 maxlength=4 name="god_rozh" value="'.$data['god_rozh'].'"></td>'; ?>
	</tr><tr>
	<td align="right"><font size="-1">Имя:</font></td><td align="left"><?PHP echo '<input size=30 maxlength=30 name="imj" value="'.$data['imj'].'"></td>'; ?>
	<td align="right"><font size="-1">Серия ВУ:</font></td><td align="left"><?PHP echo '<input size=10 maxlength=10 name="vu_ser" value="'.$data['vu_ser'].'"></td>'; ?>
	</tr><tr>
	<td align="right"><font size="-1">Отчество:</font></td><td align="left"><?PHP echo '<input size=30 maxlength=30 name="otch" value="'.$data['otch'].'"></td>'; ?>
	<td align="right"><font size="-1">Номер ВУ:</font></td><td align="left"><?PHP echo '<input size=10 maxlength=10 name="vu_nom" value="'.$data['vu_nom'].'"></td>'; ?>
	</tr><tr>
	<td align="right"><font size="-1">Орган:</font></td><td align="left"><?PHP echo '<input size=30 maxlength=30 name="organ" value="'.$data['organ'].'"></td>'; ?>
	<td align="right"><font size="-1">Нарушение:</font></td>
	<td align="left">
	<select class="text-size" name="narush">
	<?PHP
	echo '<option value="'.$data['narush'].'">'.$data['narush'].'</option>';
	$mysqlconnect = my_connect();
	$query = "SELECT id, text FROM narush ORDER BY text";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	while ($kateglist = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		echo '<option value="'.$kateglist['text'].'">'.$kateglist['text'].'</option>';
		}
	?>
	</select>
	</td></tr>
	<td align="right"><font size="-1">Раскладка:</font></td><td align="left"><?PHP echo '<input size=30 maxlength=30 name="reg_num" value="'.$data['reg_num'].'"></td>'; ?>
	<td align="right"><font size="-1">Дата лишения:</font></td><td align="left"><?PHP echo '<input size=10 maxlength=10 name="date_lishen" value="'.$data['date_lishen'].'"></td>'; ?>
	</tr><tr>
	<td align="right"><font size="-1">Срок (мес.):</font></td><td align="left"><?PHP echo '<input size=4 maxlength=4 name="srok" value="'.$data['srok'].'"></td>'; ?>
	<td align="right"><font size="-1">Изъято:</font></td>
	<td align="left">
	<?PHP
	if ($data['izyato']) { echo '<input type="checkbox" name="izyato" checked>'; } else { echo '<input type="checkbox" name="izyato">'; }
	?>
	</td>
	</tr><tr>
	<td align="right"><font size="-1">Категории:</font></td>
	<td align="left" colspan="3">
	<?PHP
	$query = "SELECT id, text FROM kateg ORDER BY text";
	$result = mysqli_query($mysqlconnect, $query) or die("Ошибка запроса: " . mysqli_error($mysqlconnect));
	$kat_arr = explode(" ", $data['kateg']);
	while ($kateglist = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$checked = false;
		foreach($kat_arr as $value) {
			if ($value == $kateglist['text']) {
				echo $kateglist['text'].'<input type="checkbox" name="'.$kateglist['id'].'" checked> ';
				$checked = true;
				}
			}
		if (!$checked) { echo $kateglist['text'].'<input type="checkbox" name="'.$kateglist['id'].'"> '; }
		}
	?>
	</td></tr>
	<tr>
	<td align="right"><font size="-1">Доп. инфо:</font></td><td align="left" colspan="3"><?PHP echo '<input size=60 maxlength=255 name="dop_info" value="'.$data['dop_info'].'"></td>'; ?>
	</tr></tr>
	<?PHP
	if ($_GET['id'] != 'new') {
	?><td align="left" colspan="2"><input type="submit" name="save" value="Изменить"></td><td align="right" colspan="2"><input type="button" onclick="show_confirm()" value="Удалить" /></td><?PHP
	} else {
	?><td align="left" colspan="4"><input type="submit" name="save" value="Добавить"></td><?PHP
	}
	?>
	</tr>
	</form>
</table>
</div>
</body>
</html>