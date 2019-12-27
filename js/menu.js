$( function () {

	// メニュー「メッセージシステム」クリックで動作
	$('#menuMessage').on ( 'click', function () {

		// メッセージシステムを表示
		$('#message').show ();

		// 日誌システムを非表示
		$('#diary').hide ();

	} );

	// メニュー「日誌システム」クリックで動作
	$('#menuDiary').on ( 'click', function () {

		// メッセージシステムを非表示
		$('#message').hide ();

		// 日誌システムを表示
		$('#diary').show ();

	} );

} );
