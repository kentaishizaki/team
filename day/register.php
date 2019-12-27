<?php
session_start();

$insertId = @$_COOKIE['insertId'];

if ( empty ( $insertId ) || $insertId != @$_SESSION['insertId'] ) {

	echo '<h1>エラー</h1>';
	echo '<p>セッションの不正<p>';
	die ();

}

?>


<!DOCTYPE html>
<html lang = "ja">

	<head>

		<link rel = "stylesheet" type = "text/css" href = "css/style.css">

		<script type="text/javascript" src="//typesquare.com/3/tsst/script/ja/typesquare.js?5cefdf617e8441039f665ccaac1e02ec" charset="utf-8"></script>

		<title>チーム連携システム</title>

	</head>

	<body>
	

<?php

	try {

		// データベース(MySQL)へ接続
		$db = new PDO ( "mysql:dbname=table_name;host=db_host;","user","password");

		// SQL文のプリコンパイル（SQLインジェクション防止）
		$data = $db -> prepare ( "insert into day(name, d624, d625, d626, d627, d628, date) values (?, ?, ?, ?, ?, ?, now())" );

		// プレースホルダに値を代入（ヴァリデーションの実施）
		$data -> bindValue ( 1, htmlspecialchars ( $_POST['name'] ) );
		$data -> bindValue ( 2, htmlspecialchars ( $_POST['624'] ) );
		$data -> bindValue ( 3, htmlspecialchars ( $_POST['625'] ) );
		$data -> bindValue ( 4, htmlspecialchars ( $_POST['626'] ) );
		$data -> bindValue ( 5, htmlspecialchars ( $_POST['627'] ) );
		$data -> bindValue ( 6, htmlspecialchars ( $_POST['628'] ) );

		// データベース上のデータを取得
		$data -> execute ();
		
		echo '
			<h1>登録が完了しました。</h1>
			
			<p>ありがとうございます！</p>
			<p>日どりが決まるまで、いましばらくお待ちください！</p>
		';

	}
	catch ( PDOException $e ) {
	
		// エラー内容の表示
		echo $e -> getMessage ();
		
		// 処理の終了
		die ();
	
	}
	finally {
	
		$db = null;   // データベース接続の切断
	
	}

?>
	<h1>本システムによる登録受付は終了しました</h1>

	<p>参加登録・変更をご希望の方は、管理者にお問い合わせください。</p>

	</body>

</html>
