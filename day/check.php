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

	<link rel = "stylesheet" type = "text/css" href = "css/style2.css">

	<script type="text/javascript" src="//typesquare.com/3/tsst/script/ja/typesquare.js?5cefdf617e8441039f665ccaac1e02ec" charset="utf-8"></script>

	<title>redチーム・打ち上げ日程調整システム</title>

</head>


<body>

	<h1>打ち上げ日程&emsp;回答一覧</h1>

<?php
		
	try {

		// データベース(MySQL)へ接続
		$db = new PDO ( "mysql:dbname=table_name;host=db_host;","user","password");

		// SQL文のプリコンパイル（SQLインジェクション防止）
		$data = $db -> prepare ( "select * from user where name = ? and password = ?" );   // ログイン認証

		// プレースホルダに値を代入（ヴァリデーションの実施）
		$data -> bindValue ( 1, htmlspecialchars ( $_POST['name'] ) );
		$data -> bindValue ( 2, htmlspecialchars ( $_POST['password'] ) );

		// データベース上のデータを取得
		$data -> execute ();
		
		// データベース接続の切断
		$db = null;
		
		// ユーザ名の一致するデータがない（データがnullである）場合
		// ログインを拒否する
		if ( !( $root = $data -> fetch () ) ) {
			
			echo '<p>ログインに失敗しました。</p>';
			
		}
		else {

			// ユーザ名が一致している、かつ、共通パスワードが正しい場合のみ認証
			
			// データベース(MySQL)へ接続
			$db = new PDO ( "mysql:dbname=table_name;host=db_host;","user","password" );

			// データベース上のデータを取得
			$data = $db -> prepare ( "select * from day" );

			// データベース上のデータを取得
			$data -> execute ();

			// データベース接続の切断
			$db = null;

			// データを配列に代入
			while ( $row = $data -> fetch() ) {

				// データを表示
				echo '
					<div>

						<table>
						<!-- 本日のメッセージ一覧画面 -->

							<tr>
								<th id = "name">氏名</th>
								<th>6/24(月)</th>
								<th>6/25(火)</th>
								<th>6/26(水)</th>
								<th>6/27(木)</th>
								<th>6/28(金)</th>
							</tr>

							<tr>
								<td>'.$row["name"].'さん</td>
								<td>';
									if ( $row['d624'] == 1 ) {
										echo '<img src = "images/circle_checked.png">';
									}
									else {
										echo '<img src = "images/cross_checked.png">';
									}
								echo '</td>
								<td>';
									if ( $row['d625'] == 1 ) {
										echo '<img src = "images/circle_checked.png">';
									}
									else {
										echo '<img src = "images/cross_checked.png">';
									}
								echo '</td>
								<td>';
									if ( $row['d626'] == 1 ) {
										echo '<img src = "images/circle_checked.png">';
									}
									else {
										echo '<img src = "images/cross_checked.png">';
									}
								echo '</td>
								<td>';
									if ( $row['d627'] == 1 ) {
										echo '<img src = "images/circle_checked.png">';
									}
									else {
										echo '<img src = "images/cross_checked.png">';
									}
								echo '</td>
								<td>';
									if ( $row['d628'] == 1 ) {
										echo '<img src = "images/circle_checked.png">';
									}
									else {
										echo '<img src = "images/cross_checked.png">';
									}
								echo '</td>
							</tr>

						</table>

					</div>
				';

			}

		}
		
	}
	catch ( PDOException $e ) {
		
		// エラー内容の表示
		echo $e -> getMessage ();
		
		// 処理の終了
		die ();
		
	}

?>

</body>

</html>
