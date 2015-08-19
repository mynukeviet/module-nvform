/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */

$(document).ready(function() {
	$('#upload_fileupload').change(function(){
	     $('#file_name').val($(this).val().match(/[-_\w]+[.][\w]+$/i)[0]);
	});

	$('#upload_fileimage').change(function(){
	     $('#photo_name').val($(this).val().match(/[-_\w]+[.][\w]+$/i)[0]);
	});
});

var page = 1;
if(window.location.hash) {
	page = window.location.hash.substring(1);
	page = page.match( /^page\-([0-9]+)$/ );
	page = page[1];
}

if( page == 1 ){
	$('#btn-prev').attr( 'disabled', 'disabled' );
	$('#btn-prev').hide();
}
else{
	$('#btn-prev').removeAttr( 'disabled' );
	$('#btn-prev').show();
}

if( page == $('#max_page' ).val() )
{
	$('#btn-next').attr( 'disabled', 'disabled' );
	$('#btn-next').hide();
	$('#btn-submit').css( 'display', 'block' );
}

$('#question .question_row').each( function( index, item ){
	if( $(item).data('page') != page )
	{
		$(item).hide();
	}
});

$('#btn-next').click(function(){
	var next_page = parseInt( $('#page').val() ) + 1;
	$('#question .question_row').each( function( index, item ){
		if( $(item).data('page') != next_page ){
			$(item).hide();
		}
		else{
			$(item).show();
		}
	});

	$('#page').val( next_page );

	window.history.pushState( window.location.href, '', '#page-' + next_page );

	if( next_page != 1 ){
		$('#btn-prev').removeAttr( 'disabled' );
		$('#btn-prev').show();
	}

	if( next_page == $('#max_page' ).val() )
	{
		$('#btn-next').attr( 'disabled', 'disabled' );
		$('#btn-next').hide();
		$('#btn-submit').css( 'display', 'block' );
	}

	return false;
});

$('#btn-prev').click(function(){
	var prev_page = parseInt( $('#page').val() ) - 1;
	prev_page = prev_page < 0 ? 0 : prev_page;
	$('#question .question_row').each( function( index, item ){
		if( $(item).data('page') != prev_page ){
			$(item).hide();
		}
		else{
			$(item).show();
		}
	});

	$('#page').val( prev_page );

	window.history.pushState( window.location.href, '', '#page-' + prev_page );

	if( prev_page == 1 ){
		$('#btn-prev').attr( 'disabled', 'disabled' );
		$('#btn-prev').hide();
	}
	else{
		$('#btn-prev').removeAttr( 'disabled' );
		$('#btn-prev').show();
	}

	if( prev_page < $('#max_page' ).val() )
	{
		$('#btn-next').removeAttr( 'disabled' );
		$('#btn-next').show();
		$('#btn-submit').css( 'display', 'none' );
	}

	return false;
});