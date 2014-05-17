<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post" class="confirm-reload">
	<input name="save" type="hidden" value="1" />
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w200" />
				<col />
			</colgroup>
			<tfoot>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" value="{LANG_SUBMIT}" class="btn btn-primary"/></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td class="right strong">{LANG.form_title}</td>
					<td><input class="w500 form-control" type="text" value="{DATA.title}" name="title" id="idtitle" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="right strong">{LANG.form_alias}</td>
					<td><input class="w500 form-control pull-left" type="text" value="{DATA.alias}" name="alias" id="idalias" maxlength="255" />&nbsp;<em class="fa fa-refresh fa-lg icon-pointer" onclick="get_alias('{ID}');">&nbsp;</em></td>
				</tr>
				<tr>
					<td class="right strong">{LANG.form_description} </td>
					<td >{DESCRIPTION}</td>
				</tr>
				<tr>
					<td class="right strong">{LANG.form_who_view} </td>
					<td >
						<select name="who_view" class="form-control w200">
							<!-- BEGIN: who_view -->
							<option value="{WHO_VIEW.key}"{WHO_VIEW.selected}>{WHO_VIEW.title}</option>
							<!-- END: who_view -->
						</select>
						
						<div id="form_groups">
							<!-- BEGIN: group_view_empty -->
							<strong>{LANG.form_groups}</strong>
							<!-- BEGIN: groups_view -->
							<input name="groups_view[]" value="{GROUPS_VIEW.key}" type="checkbox"{GROUPS_VIEW.checked} /> {GROUPS_VIEW.title}
							<br />
							<!-- END: groups_view -->
							<!-- END: group_view_empty -->
						</div>
					</td>
				</tr>
				<tr>
					<td class="right strong">{LANG.form_start_time} </td>
					<td>
						<div class="input-group pull-left" style="margin-right: 10px">
							<input name="start_time" id="start_time" value="{DATA.start_time}" class="form-control" style="width: 100px;" maxlength="10" readonly="readonly" type="text"/>
							<span class="input-group-btn pull-left">
								<button class="btn btn-default" type="button" id="start_time-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
							</span>
						</div>

						<select name="phour" class="form-control w100 pull-left">
							{phour}
						</select>
						<span class="text-middle pull-left">&nbsp;:&nbsp;</span>
						<select name="pmin" class="form-control w100 pull-left" style="margin-right: 10px">
							{pmin}
						</select>
						
						<span class="text-middle pull-left">{LANG.form_end_time}</span>
						<div class="input-group pull-left" style="margin-right: 10px">
							<input name="end_time" id="end_time" value="{DATA.end_time}" style="width: 100px;" class="form-control" maxlength="10" readonly="readonly" type="text"/>
							<span class="input-group-btn pull-left">
								<button class="btn btn-default" type="button" id="end_time-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
							</span>
						</div>
						
						<select name="ehour" class="form-control w100 pull-left">
							{ehour}
						</select>
						<span class="text-middle pull-left">&nbsp;:&nbsp;</span>
						<select name="emin" class="form-control w100 pull-left">
							{emin}
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- BEGIN: get_alias -->
<script type="text/javascript">
	$(document).ready(function() {
		$("#start_time,#end_time").datepicker({
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			showOn: 'focus'
		});
		
		$('#start_time-btn').click(function(){
			$("#start_time").datepicker('show');
		});
		
		$('#end_time-btn').click(function(){
			$("#end_time").datepicker('show');
		});
		
		$('#idtitle').change(function() {
			get_alias('{ID}');
		});
	});
</script>
<!-- END: get_alias -->
<!-- END: main -->
