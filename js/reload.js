$( function timeCount () {

	function reload () {

		// 自身に対しPOSTメソッドでデータ転送（リロード）
		document.home.submit();

	}

	// タイマに設定する更新間隔
	const limit = 1 * 60 * 1000; // 1分（単位はミリ秒）

	// タイマをスタート
	var timer = setInterval ( reload, limit );

	// マウス操作があった際は、タイマをリセットしたうえで再起動
	$( 'body' ).on ( 'mousedown', function () {

		// タイマをリセット
		clearInterval ( timer );
		timeCount ();

	} );

	// キーボード入力があった際は、スクリプトを終了
	$( 'body' ).on ( 'keydown', function () {

		exit;

	} );

} );
