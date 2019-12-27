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
		
		<link rel = "stylesheet" href = "css/style_home.css">
	
		<title>チーム連携システム・ホーム画面</title>

		<script type="text/javascript" src="//typesquare.com/3/tsst/script/ja/typesquare.js?5cefdf617e8441039f665ccaac1e02ec" charset="utf-8"></script>

		<link rel = "stylesheet" href = "https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity = "sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin = "anonymous">

		<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

		<script type = "text/javascript" src = "js/menu.js" charset = "uft-8"></script>
		<script type = "text/javascript" src = "js/loadAllMessage.js" charset = "uft-8"></script>
		<script type = "text/javascript" src = "js/reload.js" charset = "uft-8"></script>

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

				// メニュー
				echo '
					<div id = "header">
					<!-- メニュー（ここから） -->

						<div id = "title"><h1>チーム連携システム&ensp;メニュー</h1></div>

						<div class = "button">

							<form action = "logout.php" method = "post">

								<input id = "menuMessage" type = "button" value = "メッセージシステム">
								<input id = "menuDiary" type = "button" value = "日誌システム">
								<input type = "submit" value = "システム終了">

							</form>

						</div>

					</div>
					<!-- メニュー（ここまで） -->
				';

				// チーム内メッセージシステム
				echo '
					<div id = "message">
					<!-- チーム内メッセージシステム（ここから） -->

						<h1>チーム内メッセージシステム&emsp;ホーム画面</h1>
						<h3>氏名：'.$userInfo['userName'].'</h3>

						<!-- 通常メッセージ用フォーム（ここから） -->
						<form action = "registerMessage.php" method = "post">

							<input type = "hidden" name = "userId" value = "'.$userInfo["userId"].'">
							<input type = "hidden" name = "password" value = "'.$commonData["sesame"].'">
							<input type = "hidden" name = "leaderId" value = "'.$userInfo["leaderId"].'">

							<div>

								<table id = "input">
								<!-- メッセージ送信画面 -->

									<tr><td><textarea id = "message" type = "text" name = "message" placeholder = "メッセージ本文" required></textarea></td></tr>
									<tr class = "button"><td><input type = "submit" value = "送信"></td></tr>

								</table>

							</div>

						</form>
						<!-- 通常メッセージ用フォーム（ここまで） -->

						<!-- git push 通知用フォーム（ここから） -->
						<form action = "registerMessage.php" method = "post">

							<input type = "hidden" name = "userId" value = "'.$userInfo["userId"].'">
							<input type = "hidden" name = "password" value = "'.$commonData["sesame"].'">
							<input type = "hidden" name = "leaderId" value = "'.$userInfo["leaderId"].'">

							<div>

								<table>

									<tr class = "button"><td><input type = "hidden" name = "message" value = "Githubにpushしました。"><input id = "gitpush" type = "submit" value = "git&ensp;push&ensp;通知"></td></tr>

								</table>

							</div>

						</form>
						<!-- git push 通知用フォーム（ここまで） -->


				';

						// データベース(MySQL)へ接続
						$db = new PDO ( "mysql:dbname=table_name;host=db_host;","user","password" );

						// 本日の年月日を取得
						$today = date('Y-m-d');

						// データベース上のデータを取得
						$data = $db -> prepare ( "select messageContents.insertDate, userInfo.userName, messageContents.message from messageContents left join userInfo on messageContents.userId = userInfo.userId where messageContents.leaderId = ? and insertDate like '".$today."%' order by insertDate desc" );

						// プレースホルダに値を代入（ヴァリデーションの実施）
						$data -> bindValue ( 1, $userInfo['leaderId'] );

						// データベース上のデータを取得
						$data -> execute ();

						// データベース接続の切断
						$db = null;

							echo '
								<h2>本日のメッセージ</h2>
							';

						// データを配列に代入
						while ( $row = $data -> fetch() ) {

							// データを表示
							echo '
								<div>

									<table class = "contents">
									<!-- 本日のメッセージ一覧画面 -->

										<tr><td class = "left">送信者：'.$row["userName"].'さん</td><td class = "right">送信日時：'.$row["insertDate"].'</td></tr>
										<tr><td class = "messageBody" colspan = "2">'.$row["message"].'</td></tr>

									</table>

								</div>
							';

						}

						// データベース(MySQL)へ接続
						$db = new PDO ( "mysql:dbname=table_name;host=db_host;","user","password" );

						// データベース上のデータを取得
						$data = $db -> prepare ( "select messageContents.insertDate, userInfo.userName, messageContents.message from messageContents left join userInfo on messageContents.userId = userInfo.userId where messageContents.leaderId = ? and insertDate not like '".$today."%' order by insertDate desc" );

						// プレースホルダに値を代入（ヴァリデーションの実施）
						$data -> bindValue ( 1, $userInfo['leaderId'] );

						// データベース上のデータを取得
						$data -> execute ();

						// データベース接続の切断
						$db = null;

							echo '

								<div class = "button"><input id = "loadAllMessage" type = "button" value = "過去のメッセージを見る"></div>
								<div id = "allMessage">

									<h2>過去のメッセージ</h2>
							';

						// データを配列に代入
						while ( $row = $data -> fetch() ) {

							// データを表示
							echo '
									<div>

										<table class = "contents">
										<!-- 過去のメッセージ一覧画面 -->

											<tr><td class = "left">送信者：'.$row["userName"].'さん</td><td class = "right">送信日時：'.$row["insertDate"].'</td></tr>
											<tr><td class = "messageBody" colspan = "2">'.$row["message"].'</td></tr>

										</table>

									</div>
							';

						}

							echo '
								</div>
							';

				echo '
					</div>
					<!-- チーム内メッセージシステム（ここまで） -->
				';

				// 日誌システム
				echo '
					<div id = "diary">
					<!-- 日誌システム（ここから） -->

						<h1>日誌システム&emsp;ホーム画面</h1>
						<h3>氏名：'.$userInfo['userName'].'</h3>

						<form action = "registerDiary.php" method = "post">

							<input type = "hidden" name = "userId" value = "'.$userInfo["userId"].'">
							<input type = "hidden" name = "password" value = "'.$commonData["sesame"].'">

							<div>

								<table id = "input">
								<!-- 日誌投稿画面 -->

									<tr><td class = "left">本日の目標</td><td class = "right"><input type = "text" name = "todayGoal" placeholder = "本日の目標"></td></tr>
									<tr><td class = "left">本日の結果</td><td class = "right"><input type = "text" name = "results" placeholder = "本日の結果" required></td></tr>
									<tr><td class = "left">進捗率(%)</td><td class = "right"><input type = "text" name = "progressRate" placeholder = "進捗率(%)"></td></tr>
									<tr><td class = "left">良かった点</td><td class = "right"><textarea id = "goodPoint" type = "text" name = "goodPoint" placeholder = "良かった点"></textarea></td></tr>
									<tr><td class = "left">課題・反省</td><td class = "right"><textarea id = "issues" type = "text" name = "issues" placeholder = "課題・反省"></textarea></td></tr>
									<tr><td class = "left">明日の目標</td><td class = "right"><input type = "text" name = "tomorrowGoal" placeholder = "明日の目標"></td></tr>
									<tr class = "button"><td colspan = "2"><input type = "submit" value = "登録"></td></tr>

								</table>

							</div>

						</form>
				';

						// データベース(MySQL)へ接続
						$db = new PDO ( "mysql:dbname=table_name;host=db_host;","user","password");

						// データベース上のデータを取得
						$data = $db -> prepare ( "select id, insertDate, todayGoal, results, progressRate, goodPoint, issues, tomorrowGoal from diaryContents where userId = ? and deleteFlg = false order by insertDate desc" );

						// プレースホルダに値を代入（ヴァリデーションの実施）
						$data -> bindValue ( 1, $userInfo['userId'] );

						// データベース上のデータを取得
						$data -> execute ();

						// データベース接続の切断
						$db = null;

						// データを配列に代入
						while ( $row = $data -> fetch() ) {

							// データを表示
							echo '
								<div>

									<table class = "contents">
									<!-- 日誌一覧画面 -->

										<tr><td rowspan = "7" class = "garbage"><form name = "deleteDiary'.$row["id"].'" action = "deleteDiary.php" method = "post"><input type = "hidden" name = "userId" value = "'.$userInfo["userId"].'"><input type = "hidden" name = "password" value = "'.$commonData["sesame"].'"><input type = "hidden" name = "id" value = "'.$row["id"].'"><a class = "garbage" href = "javascript:deleteDiary'.$row["id"].'.submit()"><i class = "fas fa-trash-alt"></i></a></form></td><td class = "left">記入日</td><td class = "right">'.$row["insertDate"].'</td></tr>
										<tr><td class = "left">本日の目標</td><td class = "right">'.$row["todayGoal"].'</td></tr>
										<tr><td class = "left">本日の結果</td><td class = "right">'.$row["results"].'</td></tr>
										<tr><td class = "left">進捗率</td><td class = "right">'.$row["progressRate"].'%</td></tr>
										<tr><td class = "left">良かった点</td><td class = "right">'.$row["goodPoint"].'</td></tr>
										<tr><td class = "left">課題・反省</td><td class = "right">'.$row["issues"].'</td></tr>
										<tr><td class = "left">明日の目標</td><td class = "right">'.$row["tomorrowGoal"].'</td></tr>

									</table>

								</div>
							';

						}

				echo'
					</div>
					<!-- 日誌システム（ここまで） -->

					<!-- リロード用フォーム（隠し要素） -->
					<div id = "reload">

						<form action = "home.php" name = "home" method = "post">

							<input type = "hidden" name = "userId" value = "'.$userInfo["userId"].'">
							<input type = "hidden" name = "password" value = "'.$commonData["sesame"].'">

						</form>

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

