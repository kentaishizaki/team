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

	<link rel = "stylesheet" href = "css/style_userCreate.css">
    
        <title>チーム連携システム・新規ユーザ登録</title>

	<script type="text/javascript" src="//typesquare.com/3/tsst/script/ja/typesquare.js?5cefdf617e8441039f665ccaac1e02ec" charset="utf-8"></script>
    
    </head>
    
    <body>

<?php

    try {

        // データベース(MySQL)へ接続
        $db = new PDO ( "mysql:dbname=table_name;host=db_host;", "user", "password" );

	// SQL文のプリコンパイル
	$data1 = $db -> prepare ( "select * from userInfo where userId = ?" );
	$data2 = $db -> prepare ( "select * from commonData where sesame = ?" );

	// POST変数のヴァリデーション
	$newUserId = htmlspecialchars ( $_POST['newUserId'] );
	$adminPassword = htmlspecialchars ( $_POST['adminPassword'] );
	$newUserName = htmlspecialchars ( $_POST['newUserName'] );
	$leaderId = htmlspecialchars ( $_POST['leaderId'] );

	// プレースホルダに値を代入
	$data1 -> bindValue ( 1, $newUserId );
	$data2 -> bindValue ( 1, $adminPassword );

        // データベース上のデータを取得
        $data1 -> execute ();
        $data2 -> execute ();

	// 共通パスワードの認証
        if ( $common_data = $data2 -> fetch () ) {
        
            // ユーザ名の一致するデータがある場合
            // 新規ユーザ登録を拒否する
            if ( $user_info = $data1 -> fetch () ) {
                echo ('ユーザ登録に失敗しました。');
            }
            else {

		// SQL文のプリコンパイル
		$data3 = $db -> prepare ( "insert into userInfo ( userId, userName, leaderId ) values ( ?, ?, ? );");

		// プレースホルダに値を代入
		$data3 -> bindValue ( 1, $newUserId );
		$data3 -> bindValue ( 2, $newUserName );
		$data3 -> bindValue ( 3, $leaderId );

                // データベース上に新規ユーザを追加
	        $data3 -> execute ();

                echo '
                    <h3>新規ユーザ登録が完了しました。</h3>
                    <p><a href = "index.php">トップページへもどる</a></p>
                ';
            }

        }
	else {
	    echo ('ユーザ登録に失敗しました。');
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
