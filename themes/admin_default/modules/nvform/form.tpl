<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />

<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->

<form action="{FORM_ACTION}" method="post" class="confirm-reload form-horizontal">
	<input name="save" type="hidden" value="1" />
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.form_title}</strong> <span class="red">*</span></label>
				<div class="col-sm-21">
					<input class="form-control" type="text" value="{DATA.title}" name="title" id="idtitle" maxlength="255" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.form_alias}</strong></label>
				<div class="col-sm-21">
					<div class="input-group">
						<input class="form-control" type="text" value="{DATA.alias}" name="alias" id="idalias" maxlength="255" />
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">
								<i class="fa fa-refresh fa-lg" onclick="get_alias('{ID}');">&nbsp;</i>
							</button> </span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.form_description}</strong></label>
				<div class="col-sm-21">
					{DESCRIPTION}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 text-right"><strong>{LANG.form_who_view}</strong></label>
				<div class="col-sm-21">
					<!-- BEGIN: group_view -->
					<label><input name="groups_view[]" type="checkbox" value="{GR_VIEW.value}" {GR_VIEW.checked} />{GR_VIEW.title}</label>&nbsp;&nbsp;&nbsp; <!-- END: group_view -->
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.form_start_time}</strong></label>
				<div class="col-sm-4">
					<div class="input-group">
						<input name="start_time" id="start_time" value="{DATA.start_time}" class="form-control" maxlength="10" readonly="readonly" type="text"/>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="start_time-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
				<div class="col-sm-2">
					<select name="phour" class="form-control">
						{phour}
					</select>
				</div>
				<div class="col-sm-2">
					<select name="pmin" class="form-control">
						{pmin}
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.form_end_time}</strong></label>
				<div class="col-sm-4">
					<div class="input-group">
						<input name="end_time" id="end_time" value="{DATA.end_time}" class="form-control" maxlength="10" readonly="readonly" type="text"/>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="end_time-btn">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button> </span>
					</div>
				</div>
				<div class="col-sm-2">
					<select name="ehour" class="form-control">
						{ehour}
					</select>
				</div>
				<div class="col-sm-2">
					<select name="emin" class="form-control">
						{emin}
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.form_question_display}</strong></label>
				<div class="col-sm-21">
					<select name="question_display" class="form-control">
						<!-- BEGIN: question_display -->
						<option value="{STYLE.value}" {STYLE.seleced}>{STYLE.title}</option>
						<!-- END: question_display -->
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">&nbsp;</label>
				<div class="col-sm-21">
					<input type="submit" value="{LANG_SUBMIT}" class="btn btn-primary"/>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<!-- BEGIN: get_alias -->
<script type="text/javascript">
	$(document).ready(function() {
		$("#start_time,#end_time").datepicker({
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			showOn : 'focus'
		});

		$('#start_time-btn').click(function() {
			$("#start_time").datepicker('show');
		});

		$('#end_time-btn').click(function() {
			$("#end_time").datepicker('show');
		});

		$('#idtitle').change(function() {
			get_alias('{ID}');
		});
	});
</script>
<!-- END: get_alias -->
<!-- END: main -->