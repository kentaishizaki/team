$( function timeCount () {

	function reload () {

		// ���g�ɑ΂�POST���\�b�h�Ńf�[�^�]���i�����[�h�j
		document.home.submit();

	}

	// �^�C�}�ɐݒ肷��X�V�Ԋu
	const limit = 1 * 60 * 1000; // 1���i�P�ʂ̓~���b�j

	// �^�C�}���X�^�[�g
	var timer = setInterval ( reload, limit );

	// �}�E�X���삪�������ۂ́A�^�C�}�����Z�b�g���������ōċN��
	$( 'body' ).on ( 'mousedown', function () {

		// �^�C�}�����Z�b�g
		clearInterval ( timer );
		timeCount ();

	} );

	// �L�[�{�[�h���͂��������ۂ́A�X�N���v�g���I��
	$( 'body' ).on ( 'keydown', function () {

		exit;

	} );

} );
