<!-- BEGIN: main -->
<div class="table-responsive" style="width: 100%; height: 100%; overflow:scroll">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col width="20" />
			<col class="w150" />
			<col width="120" />
			<col width="120" />
		</colgroup>
		<thead>
			<tr>
				<th>&nbsp;</th>
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
				<td class="success"><a href="javascript:void(0);" rel='tooltip' data-html="true" data-toggle="tooltip" data-placement="bottom" title="{GLANG.delete}" onclick="nv_del_answer({ANSWER.id});"><em class="fa fa-trash-o fa-lg">&nbsp;</em></a></td>
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
    });
</script>
<!-- END: main -->