<!-- BEGIN: main -->
<input type="button" name="export_excel" class="btn btn-success" style="margin-bottom: 10px" value="{LANG.report_ex_excel}">
<p id="export" class="text-center">&nbsp;</p>
<div class="table-responsive" style="width: 100%; height: 100%; overflow:scroll">
	<table class="table table-striped table-bordered table-hover" id="table_report">
		<colgroup>
			<col width="30" />
			<col width="20" />
			<col class="w150" />
			<col width="120" />
			<col width="120" />
		</colgroup>
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>STT</th>
				<th>{LANG.report_who_answer}</th>
				<th>{LANG.report_answer_time}</th>
				<th>{LANG.report_answer_edit_time}</th>
				<!-- BEGIN: thead -->
				<th><span href="#" style="cursor: pointer" rel='tooltip' data-html="true" data-toggle="tooltip" data-placement="bottom" title="<p class='text-justify'>{QUESTION.title}</p>">{QUESTION.title_cut}</span></th>
				<!-- END: thead -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: tr -->
			<tr>
				<td class="danger"><a href="javascript:void(0);" rel='tooltip' data-html="true" data-toggle="tooltip" data-placement="bottom" title="{GLANG.delete}" onclick="nv_del_answer({ANSWER.id});"><em class="fa fa-trash-o fa-lg">&nbsp;</em></a></td>
				<td class="success">{ANSWER.no}</td>
				<td class="success">{ANSWER.username}</td>
				<td class="success">{ANSWER.answer_time}</td>
				<td class="success">{ANSWER.answer_edit_time}</td>
				<!-- BEGIN: td -->
				<td>{ANSWER}</td>
				<!-- END: td -->
			</tr>
			<!-- END: tr -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
    $(function () {
        $("[rel='tooltip']").tooltip();
        $("#export").hide();
    });
    
	function nv_data_export(set_export, fid) {
		$.ajax({
			type : "POST",
			url : "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=export_excel&nocache=" + new Date().getTime(),
			data : "step=1&set_export=" + set_export + "&fid=" + fid,
			success : function(response) {
				if (response == "OK_GETFILE") {
					nv_data_export(0);
				} else if (response == "OK_COMPLETE") {
					$("#export").hide();
					alert('{LANG.report_export_complete}');
					$("input[name=export_excel]").removeAttr("disabled");
					window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export_excel&step=2';
				} else {
					$("input[name=export_excel]").removeAttr("disabled");
					$("#export").hide();
					alert(response);
				}
			}
		});
	}

	$("input[name=export_excel]").click(function() {
		$("input[name=export_excel]").attr("disabled", "disabled");
		$("#export").show();
		$('#export').html('<div class="text-center well">{LANG.report_export_note}<br /><br /><em class="fa fa-spinner fa-spin fa-3x"></em></div>');
		nv_data_export(1, '{FID}' );
	});
</script>
<!-- END: main -->