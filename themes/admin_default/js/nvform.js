/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 08 Apr 2014 15:13:43 GMT
 */

function get_alias(id) {
	var title = strip_tags(document.getElementById('idtitle').value);
	if (title != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title) + '&id=' + id, function(res) {
			if (res != "") {
				document.getElementById('idalias').value = res;
			} else {
				document.getElementById('idalias').value = '';
			}
		});
	}
	return false;
}

function nv_chang_weight(vid, fid, op) {
	var nv_timer = nv_settimeout_disable('change_weight_' + vid, 5000);
	var new_weight = $('#change_weight_' + vid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_weight&nocache=' + new Date().getTime(), 'id=' + vid + '&op=' + op + '&fid=' + fid + '&new_weight=' + new_weight, function(res) {
		nv_chang_weight_res(res);
	});
	return;
}

function nv_chang_status(vid, op) {
	var nv_timer = nv_settimeout_disable('change_status_' + vid, 5000);
	var new_status = $('#change_status_' + vid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_status&nocache=' + new Date().getTime(), 'id=' + vid + '&op=' + op + '&new_status=' + new_status, function(res) {
		nv_chang_weight_res(res);
	});
	return;
}

function nv_chang_weight_res(res) {
	var r_split = res.split("_");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
		clearTimeout(nv_timer);
	} else {
		window.location.href = window.location.href;
	}
	return;
}

function nv_del_question(qid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=question&nocache=' + new Date().getTime(), 'del=1&qid=' + qid, function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_del_form(fid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 'del=1&fid=' + fid, function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_del_answer(aid) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=report&nocache=' + new Date().getTime(), 'del=1&aid=' + aid, function(res) {
			if (res == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

$( document ).ready(function() {
	$('#frm-download').submit(function(res){
		var type= $('input[name="type"]:checked').val();
		var is_zip= $('input[name="zip"]').is(':checked') ? 1 : 0;
		var fid = $('#fid').val();
		window.location.href = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + '=export&export=1&type=' + type + '&is_zip=' + is_zip + '&fid=' + fid;
		$('#sitemodal').modal('hide');
		return false;
	});

	$('#open_modal').click(function(){
		$.get( script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + '=export&fid=' + $(this).data('fid'), function( res ){
			modalShow( $('#open_modal').data('lang_ex'), res );
		});
	});

	$('#ex_onine').click(function(){
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export&nocache=' + new Date().getTime(), 'export=1&download=0&type=xlsx&fid=' + $(this).data('fid'), function(res) {
			var r_split = res.split("_");
			if ( r_split[0] == 'OK' ) {
				window.open( 'https://docs.google.com/viewerng/viewer?' + window.location.host + '/' + r_split[1], '_blank' ) ;
			}
			else{
				alert(r_split[1]);
			}
		});
	});
});

function nv_open_windown( url )
{
	nv_open_browse( url, '', 860, 500, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
	return false;
}