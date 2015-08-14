<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.css">

<!-- BEGIN: error -->
<div class="alert alert-danger">
	{ERROR}
</div>
<!-- END: error -->

<form action="{FORM_ACTION}" method="post" class="confirm-reload form-horizontal">
	<input name="save" type="hidden" value="1" />

	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="">
			<a href="#form_info" aria-controls="form_info" role="tab" data-toggle="tab"> <em class="fa fa-info-circle fa-lg">&nbsp;</em>{LANG.form_info} </a>
		</li>
		<li role="presentation" class="active">
			<a href="#form_template" aria-controls="form_template" role="tab" data-toggle="tab"> <em class="fa fa-picture-o fa-lg">&nbsp;</em>{LANG.form_template} </a>
		</li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane " id="form_info">
			<div class="panel panel-default" style="border-top: none">
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
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane active" id="form_template">
			<div class="panel panel-default" style="border-top: none">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-sm-3 control-label"><strong>{LANG.form_template_background}</strong></label>
						<div class="col-sm-21 form-inline">
							<input type="text" class="form-control m-bottom" value="{DATA.template.background_color}" name="template[background_color]" id="picker_background" placeholder="{LANG.form_template_background_color}" style="background-color: {DATA.template.background_color}" />
							<div class="input-group m-bottom">
								<span class="input-group-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.form_template_background_image_clear}">
									<button class="btn btn-default" type="button" id="clearimg">
										<em class="fa fa-trash-o fa-fix">&nbsp;</em>
									</button> </span>
								<input type="text" class="form-control" id="bg_image" value="{DATA.template.background_image}" name="template[background_image]" placeholder="{LANG.form_template_background_image}" readonly="readonly" />
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="selectimg" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.form_template_background_image_chosen}">
										<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
									</button> </span>
							</div>
							<select name="template[background_imgage_repeat]" class="form-control m-bottom">
								<option value="">---{LANG.form_template_background_image_repeat}---</option>
								<!-- BEGIN: background_repeat -->
								<option value="{REPEAT.key}" {REPEAT.selected}>{REPEAT.value}</option>
								<!-- END: background_repeat -->
							</select>
							<select name="template[background_imgage_position]" class="form-control m-bottom">
								<option value="">---{LANG.form_template_background_image_position}---</option>
								<!-- BEGIN: background_position -->
								<option value="{POSITION.key}" {POSITION.selected}>{POSITION.value}</option>
								<!-- END: background_position -->
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" value="{LANG_SUBMIT}" class="btn btn-primary"/>
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.js"></script>

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

		$('#picker_background').colpick({
			layout : 'hex',
			submit : 0,
			colorScheme : 'dark',
			onChange : function(hsb, hex, rgb, el, bySetColor) {
				$(el).css('background-color', '#' + hex);
				if (!bySetColor)
					$(el).val('#' + hex);
			}
		}).keyup(function() {
			$(this).colpickSetColor(this.value);
		});
	});

	$('#selectimg').click(function() {
		var area = 'bg_image';
		var alt = "backgroundimgalt";
		var path = "{UPLOADS_DIR_USER}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&alt=" + alt + "&path=" + path + "&type=" + type, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});

	$('#clearimg').click(function() {
		$('#bg_image').val('');
	});
</script>
<!-- END: get_alias -->
<!-- END: main -->