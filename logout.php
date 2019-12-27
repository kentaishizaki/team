<?php
session_start();

$insertId = @$_COOKIE['insertId'];

if ( empty ( $insertId ) || $insertId != @$_SESSION['insertId'] ) {

	echo '<h1>エラー</h1>';
	echo '<p>セッションの不正<p>';
	die ();

}
else {

	// Cookieの削除
	if ( isset ( $_COOKIE['PHPSESSID'] ) ) {
		setcookie ( "PHPSESSID", '', time () - 1800, '/' );
	}
	if ( isset ( $_COOKIE['insertId'] ) ) {
		setcookie ( "insertId", '', time () - 1800, '/' );
	}

}

?>


<!DOCTYPE html>
<html lang = "ja">

    <head>

        <link rel = "stylesheet" href = "css/style_logout.css">
        
        <title>チーム連携システム・システムを終了しました</title>

	<script type="text/javascript" src="//typesquare.com/3/tsst/script/ja/typesquare.js?5cefdf617e8441039f665ccaac1e02ec" charset="utf-8"></script>

    </head>

    <body>

	<h3>システムを終了しました。</h3>

		<p>おつかれさまでした。明日もがんばりましょう！</p>

		<div id = "button"><a href ="/"><input type = "button" value = "トップページにもどる"></a></div>
        
    </body>

</html>
