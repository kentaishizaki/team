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

		<link rel = "stylesheet" href = "css/style_register.css">
	
		<title>チーム連携システム・新規日誌登録完了</title>

		<script type="text/javascript" src="//typesquare.com/3/tsst/script/ja/typesquare.js?5cefdf617e8441039f665ccaac1e02ec" charset="utf-8"></script>

	</head>

	<body>
	

<?php

	try {

		// データベース(MySQL)へ接続
		$db = new PDO ( "mysql:dbname=table_name;host=db_host;","user","password");

		// SQL文のプリコンパイル（SQLインジェクション防止）
		$data1 = $db -> prepare ( "select userId, userName, leaderId from userInfo where userId = ?" );   // ユーザID・ユーザ名・所属チーム（リーダーID）の取得
		$data2 = $db -> prepare ( "select sesame from commonData where sesame = '".$_POST['password']."'" );   // システム共通パスワード

		// プレースホルダに値を代入（ヴァリデーションの実施）
		$data1 -> bindValue ( 1, htmlspecialchars ( $_POST['userId'] ) );
		$data2 -> bindValue ( 1, htmlspecialchars ( $_POST['password'] ) );

		// データベース上のデータを取得
		$data1 -> execute ();
		$data2 -> execute ();
		
		// データベース接続の切断
		$db = null;
		
		// ユーザ名の一致するデータがない（データがnullである）場合
		// ログインを拒否する
		if ( !( $userInfo = $data1 -> fetch () ) ) {
			
			echo '<p>ログインに失敗しました。</p>';
			
		}
		else {


			// ユーザ名が一致している、かつ、共通パスワードが正しい場合のみ認証
			if ( !( $commonData = $data2 -> fetch () ) ) {
				
				echo '<p>ログインに失敗しました。</p>';
				
			}
			else {

				// データベース(MySQL)へ接続
				$db = new PDO ( "mysql:dbname=table_name;host=db_host;","user","password");

				// 日時の取得
				$insert_date = date('Y-m-d H:i:s');

				// SQL文のプリコンパイル
				$data = $db -> prepare ( "insert into diaryContents ( insertDate, todayGoal, results, progressRate, goodPoint, issues, tomorrowGoal, userId ) values ( ?, ?, ?, ?, ?, ?, ?, ? ); ");

				// プレースホルダに値を代入
				$data -> bindValue ( 1, $insert_date );
				$data -> bindValue ( 2, htmlspecialchars ( $_POST['todayGoal'] ) );
				$data -> bindValue ( 3, htmlspecialchars ( $_POST['results'] ) );
				$data -> bindValue ( 4, htmlspecialchars ( $_POST['progressRate'] ) );
				$data -> bindValue ( 5, htmlspecialchars ( $_POST['goodPoint'] ) );
				$data -> bindValue ( 6, htmlspecialchars ( $_POST['issues'] ) );
				$data -> bindValue ( 7, htmlspecialchars ( $_POST['tomorrowGoal'] ) );
				$data -> bindValue ( 8, $_POST['userId'] );

				// Postメソッドで受け取ったデータをデータベースへ追加
				$data -> execute ();

				echo '

					<h3>登録が完了しました。</h3>
					
					<p>おつかれさまでした。明日もがんばりましょう！</p>

					<form action = "home.php" method = "post">

					<input type = "hidden" name = "userId" value = "'.$_POST["userId"].'">
					<input type = "hidden" name = "password" value = "'.$_POST["password"].'">

					<input type = "submit" value = "ホームにもどる">

					</form>

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
	finally {
	
		$db = null;   // データベース接続の切断
	
	}

?>
	
	</body>

</html>
