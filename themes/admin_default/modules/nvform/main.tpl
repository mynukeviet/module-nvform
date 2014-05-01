<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr class="center">
			<td width="50">{LANG.order}</td>
			<td>{LANG.form_title}</td>
			<td width="100">{LANG.status}</td>
			<td width="280">&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: row -->
		<tr>
			<td class="center">
			<select id="change_weight_{ROW.id}" onchange="nv_chang_weight('{ROW.id}', 0, 'form');">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.w}"{WEIGHT.selected}>{WEIGHT.w}</option>
				<!-- END: weight -->
			</select></td>
			<td><a href="{ROW.url_view}" title="{ROW.title}" target="_blank">{ROW.title}</a></td>
			<td class="center">
			<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}', 'form');">
				<!-- BEGIN: status -->
				<option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
				<!-- END: status -->
			</select></td>
			<td class="center">
				<em class="icon-share icon-large">&nbsp;</em> <a href="{ROW.qlist}">{LANG.question}</a> &nbsp;
				<em class="icon-bar-chart icon-large">&nbsp;</em> <a href="{ROW.qlist}">{LANG.form_report}</a> &nbsp;
				<em class="icon-edit icon-large">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
				<em class="icon-trash icon-large">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_form({ROW.id});">{GLANG.delete}</a>
			</td>
		</tr>
		<!-- END: row -->
	</tbody>
</table>
<!-- END: main -->