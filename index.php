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

	<link rel = "stylesheet" type = "text/css" href = "css/style_index.css">

	<script type="text/javascript" src="//typesquare.com/3/tsst/script/ja/typesquare.js?5cefdf617e8441039f665ccaac1e02ec" charset="utf-8"></script>

	<title>チーム連携システム</title>

</head>


<body>

	<h1>チーム連携システム&emsp;ログイン認証</h1>

	<form action = "home.php" method = "post">

		<table>

			<tr>
				<td class = "left">ユーザID</td><td class = "right"><input type = "text" name = "userId" pattern = "^([a-zA-Z0-9]{3,})$" placeholder = "半角英数字3文字以上" required></td>
			<tr>
			</tr>
				<td class = "left">パスワード</td><td class = "right"><input type = "password" name = "password" pattern = "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder = "半角英大文字・小文字、半角数字、半角記号のそれぞれを含む8文字以上" required></td>
			</tr>
			<tr id = "button1">
				<td><input type = "submit" value = "ログイン"></td>
			</tr>

		</table>

	</form>

	<h2>新規ユーザ登録（要管理者パスワード）</h2>
	
	<form action = "userCreate.php" method = "post">

		<table>

			<tr>
				<td class = "left">新規ユーザID</td><td class = "right"><input type = "text" name = "newUserId" pattern = "^([a-zA-Z0-9]{3,})$" placeholder = "半角英数字3文字以上" required></td>
			</tr>
			<tr>
				<td class = "left">新規ユーザパスワード</td><td class = "right"><input type = "password" name = "newPassword" pattern = "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder = "半角英大文字・小文字、半角数字、半角記号のそれぞれを含む8文字以上" required></td>
			</tr>
			<tr>
				<td class = "left">チームリーダー名</td>
				<td class = "right">
					<select id = "leader" name = "leaderId" required>

				<?php

					try{

						// データベース(MySQL)へ接続
						$db = new PDO ( "mysql:dbname=table_name;host=db_host;","user","password");

						// データベース上のデータを取得
						$data = $db -> prepare ( "select leaderInfo.id, userInfo.userName from leaderInfo left join userInfo on leaderInfo.userId = userInfo.userId" );

						// データベース上のデータを取得
						$data -> execute ();

						// データベース接続の切断
						$db = null;

						// データを配列に代入
						while ( $row = $data -> fetch() ) {

							// データを表示
							echo '
						<option value = "'.$row["id"].'">'.$row["userName"].'</option>
							';

						}


					}
					catch ( PDOException $e ) {

					}

				?>

					</select>
				</td>
			</tr>
			<tr>
				<td class = "left">新規ユーザ名</td><td class = "right"><input type = "text" name = "newUserName" pattern = "^([ぁ-んァ-ン\u4E00-\u9FFF]{1,})$" placeholder = "漢字・ひらがな・カタカナ" required></td>
			</tr>
			<tr>
				<td class = "left">管理者パスワード</td><td class = "right"><input type = "password" name = "adminPassword" pattern = "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder = "半角英大文字・小文字、半角数字、半角記号のそれぞれを含む8文字以上" required></td>
			</tr>
			<tr id = "button2">
				<td><input type = "submit" value = "新規登録"></td>
			</tr>
		</table>

	</form>

</body>

</html>
