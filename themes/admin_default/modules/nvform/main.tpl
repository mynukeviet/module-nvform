<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr class="center">
				<th width="100">{LANG.order}</th>
				<th>{LANG.form_title}</th>
				<th width="100">{LANG.status}</th>
				<th width="280">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: row -->
			<tr>
				<td class="center">
				<select id="change_weight_{ROW.id}" onchange="nv_chang_weight('{ROW.id}', 0, 'form');" class="form-control w100">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
					<!-- END: weight -->
				</select></td>
				<td><a href="{ROW.url_view}" title="{ROW.title}" target="_blank">{ROW.title}</a></td>
				<td class="center">
				<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}', 'form');" class="form-control w150">
					<!-- BEGIN: status -->
					<option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
					<!-- END: status -->
				</select></td>
				<td class="center">
					<em class="fa fa-tasks fa-lg">&nbsp;</em> <a href="{ROW.qlist}">{LANG.question}</a> &nbsp;
					<em class="fa fa-bar-chart-o fa-lg">&nbsp;</em> <a href="{ROW.url_report}">{LANG.form_report}</a> &nbsp;
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_form({ROW.id});">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: row -->
		</tbody>
	</table>
</div>
<!-- END: main -->