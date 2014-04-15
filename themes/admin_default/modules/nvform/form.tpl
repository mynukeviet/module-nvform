<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
	<input name="save" type="hidden" value="1" />
	<table class="tab1">
		<colgroup>
			<col class="w200" />
			<col />
		</colgroup>
		<tfoot>
			<tr>
				<td></td>
				<td><input type="submit" value="{LANG_SUBMIT}"/></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="right strong">{LANG.form_title}</td>
				<td><input class="w500" type="text" value="{DATA.title}" name="title" id="idtitle" maxlength="255" /></td>
			</tr>
			<tr>
				<td class="right strong">{LANG.form_alias}</td>
				<td><input class="w500" type="text" value="{DATA.alias}" name="alias" id="idalias" maxlength="255" /> <em class="icon-refresh icon-large icon-pointer" onclick="get_alias('{ID}');">&nbsp;</em></td>
			</tr>
			<tr>
				<td class="right strong">{LANG.form_description} </td>
				<td >{DESCRIPTION}</td>
			</tr>
			<tr>
				<td class="right strong">{LANG.form_who_view} </td>
				<td >
					<select name="who_view">
						<!-- BEGIN: who_view -->
						<option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
						<!-- END: who_view -->
					</select>
					
					<div id="form_groups">
						<!-- BEGIN: group_view_empty -->
						<strong>{LANG.form_groups}</strong><div class="hr"></div>
						<!-- BEGIN: groups_view -->
						<input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}
						<br />
						<!-- END: groups_view -->
						<!-- END: group_view_empty -->
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<!-- BEGIN: get_alias -->
<script type="text/javascript">
	$(document).ready(function() {
		$('#idtitle').change(function() {
			get_alias('{ID}');
		});
	});
</script>
<!-- END: get_alias -->
<!-- END: main -->
