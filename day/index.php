<?php session_start();

	// なりすまし防止用IDの発行
	$insertId = base64_encode ( openssl_random_pseudo_bytes ( 28 ) );
	setcookie ( 'insertId', $insertId );
	$_SESSION['insertId'] = $insertId;

?>


<!DOCTYPE html>

<html>


<head>

	<meta charset = "utf-8">

	<link rel = "stylesheet" type = "text/css" href = "css/style.css">

	<script type="text/javascript" src="//typesquare.com/3/tsst/script/ja/typesquare.js?5cefdf617e8441039f665ccaac1e02ec" charset="utf-8"></script>

	<title>redチーム・打ち上げ日程調整システム</title>

</head>


<body>

	<h1>打ち上げ日程調整</h1>
<?php
	<p>以下の日時について、ご都合の可否を選択して、送信ボタンを押してください。</p>
	<p>（お名前は、本名を入力してください。）</p>

	<form action = "register.php" method = "post">

	<p>お名前<input type = "text" name = "name" pattern = "^([ぁ-んァ-ン\u4E00-\u9FFF]{1,})$" placeholder = "漢字・ひらがな・カタカナ" required>さん</p>

		<table align = "center">

			<tr>
				<th>日時</th>
				<th>ご都合の可否</th>
			</tr>

			<tr class = "day">
				<td>6/24(月)</td>
				<td>
					<input type = "radio" id = "624good" class = "good" name = "624" value = "1" required><label for = "624good"></label>
					<input type = "radio" id = "624notgood" class = "notgood" name = "624" value = "0" required><label for = "624notgood"></label>
				</td>
			</tr>

			<tr class = "day">
				<td>6/25(火)</td>
				<td>
					<input type = "radio" id = "625good" class = "good" name = "625" value = "1" required><label for = "625good"></label>
					<input type = "radio" id = "625notgood" class = "notgood" name = "625" value = "0" required><label for = "625notgood"></label>
				</td>
			</tr>

			<tr class = "day">
				<td>6/26(水)</td>
				<td>
					<input type = "radio" id = "626good" class = "good" name = "626" value = "1" required><label for = "626good"></label>
					<input type = "radio" id = "626notgood" class = "notgood" name = "626" value = "0" required><label for = "626notgood"></label>
				</td>
			</tr>

			<tr class = "day">
				<td>6/27(木)</td>
				<td>
					<input type = "radio" id = "627good" class = "good" name = "627" value = "1" required><label for = "627good"></label>
					<input type = "radio" id = "627notgood" class = "notgood" name = "627" value = "0" required><label for = "627notgood"></label>
				</td>
			</tr>

			<tr class = "day">
				<td>6/28(金)</td>
				<td>
					<input type = "radio" id = "628good" class = "good" name = "628" value = "1" required><label for = "628good"></label>
					<input type = "radio" id = "628notgood" class = "notgood" name = "628" value = "0" required><label for = "628notgood"></label>
				</td>
			</tr>

		</table>

		<div>
			<input type = "submit" value = "送信">
		</div>

	</form>
?>
	<h2>管理者ログイン</h2>
	
	<form action = "check.php" method = "post">
		<p>管理者ID<input type = "text" name = "name"></p>
		<p>パスワード<input type = "password" name = "password"></p>
		<div><input type = "submit" value = "管理者ログイン"></div>
	</form>

</body>

</html>
