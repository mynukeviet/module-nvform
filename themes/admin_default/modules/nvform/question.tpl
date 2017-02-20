<!-- BEGIN: main -->
<div style="margin: 5px 0 10px 0; display: block;">
	<a href="{ADD_QUESTION}" class="btn btn-danger">{LANG.question_add}</a>
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w100">
			<col span="1">
			<col class="w200">
			<col span="2" class="w150">
		</colgroup>
		<thead>
			<tr class="center">
				<th>{LANG.order}</th>
				<th>{LANG.question_content}</th>
				<th>{LANG.question_type}</th>
				<th>{LANG.status}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td class="center"><select id="change_weight_{ROW.qid}" onchange="nv_chang_weight('{ROW.qid}', '{ROW.fid}', 'question');" class="form-control w100">
						<!-- BEGIN: weight -->
						<option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
						<!-- END: weight -->
				</select></td>
				<td>{ROW.title} <small class="help-block">{LANG.page} {ROW.page}</small>
				</td>
				<td>{FIELD_TYPE_TEXT}</td>
				<td class="center"><select id="change_status_{ROW.qid}" onchange="nv_chang_status('{ROW.qid}', 'question');" class="form-control w150">
						<!-- BEGIN: status -->
						<option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
						<!-- END: status -->
				</select></td>
				<td class="text-center"><em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp; <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_question({ROW.qid});">{GLANG.delete}</a></td>
			</tr>
			<!-- END: row -->
		</tbody>
	</table>
</div>
<!-- END: main -->