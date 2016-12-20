<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.css">

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<form action="{FORM_ACTION}" method="post" class="confirm-reload form-horizontal">
	<input name="save" type="hidden" value="1" />

	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#form_info" aria-controls="form_info" role="tab" data-toggle="tab"> <em class="fa fa-info-circle fa-lg">&nbsp;</em>{LANG.form_info}
		</a></li>
		<li role="presentation"><a href="#form_template" aria-controls="form_template" role="tab" data-toggle="tab"> <em class="fa fa-picture-o fa-lg">&nbsp;</em>{LANG.form_template}
		</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="form_info">
			<div class="panel panel-default" style="border-top: none">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.form_title}</strong> <span class="red">*</span></label>
						<div class="col-sm-20">
							<input class="form-control" type="text" value="{DATA.title}" name="title" id="idtitle" maxlength="255" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.form_alias}</strong></label>
						<div class="col-sm-20">
							<div class="input-group">
								<input class="form-control" type="text" value="{DATA.alias}" name="alias" id="idalias" maxlength="255" /> <span class="input-group-btn">
									<button class="btn btn-default" type="button">
										<i class="fa fa-refresh fa-lg" onclick="get_alias('{ID}');">&nbsp;</i>
									</button>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.form_description}</strong></label>
						<div class="col-sm-20">
							<textarea name="description" class="form-control" rows="4">{DATA.description}</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.form_description_html}</strong></label>
						<div class="col-sm-20">{DESCRIPTION_HTML}</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.form_image}</strong></label>
						<div class="col-sm-20">
							<div class="input-group">
								<input class="form-control" type="text" name="image" id="image" value="{DATA.image}" /> <span class="input-group-btn">
									<button class="btn btn-default" onclick="nv_selectimg('image')" type="button">
										<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
									</button>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 text-right"><strong>{LANG.form_who_view}</strong></label>
						<div class="col-sm-20">
							<!-- BEGIN: group_view -->
							<label class="show"><input name="groups_view[]" type="checkbox" value="{GR_VIEW.value}" {GR_VIEW.checked} />{GR_VIEW.title}</label>
							<!-- END: group_view -->
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.form_active} {LANG.form_start_time}</strong></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input name="start_time" value="{DATA.start_time}" class="form-control datepicker" maxlength="10" readonly="readonly" type="text" /> <span class="input-group-btn">
									<button class="btn btn-default" type="button">
										<em class="fa fa-calendar fa-fix">&nbsp;</em>
									</button>
								</span>
							</div>
						</div>
						<div class="col-sm-2">
							<select name="phour" class="form-control"> {phour}
							</select>
						</div>
						<div class="col-sm-2">
							<select name="pmin" class="form-control"> {pmin}
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.form_end_time}</strong></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input name="end_time" value="{DATA.end_time}" class="form-control datepicker" maxlength="10" readonly="readonly" type="text" /> <span class="input-group-btn">
									<button class="btn btn-default" type="button">
										<em class="fa fa-calendar fa-fix">&nbsp;</em>
									</button>
								</span>
							</div>
						</div>
						<div class="col-sm-2">
							<select name="ehour" class="form-control"> {ehour}
							</select>
						</div>
						<div class="col-sm-2">
							<select name="emin" class="form-control"> {emin}
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 text-right"><strong>{LANG.form_user_editable}</strong></label>
						<div class="col-sm-20">
							<label><input type="checkbox" name="user_editable" value="1" {DATA.user_editable_check} />{LANG.form_user_editable_note}</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.status}</strong></label>
						<div class="col-sm-20">
							<select id="change_status_{ROW.id}" class="form-control">
								<!-- BEGIN: status -->
								<option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
								<!-- END: status -->
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="form_template">
			<div class="panel panel-default" style="border-top: none">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.form_question_display}</strong></label>
						<div class="col-sm-20">
							<select name="question_display" class="form-control">
								<!-- BEGIN: question_display -->
								<option value="{STYLE.value}"{STYLE.seleced}>{STYLE.title}</option>
								<!-- END: question_display -->
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.form_template_background}</strong></label>
						<div class="col-sm-20 form-inline">
							<input type="text" class="form-control m-bottom" value="{DATA.template.background_color}" name="template[background_color]" id="picker_background" placeholder="{LANG.form_template_background_color}" style="background-color: {DATA.template.background_color" />
							<div class="input-group m-bottom">
								<span class="input-group-btn" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.form_template_background_image_clear}">
									<button class="btn btn-default" type="button" id="clearimg">
										<em class="fa fa-trash-o fa-fix">&nbsp;</em>
									</button>
								</span> <input type="text" class="form-control" id="bg_image" value="{DATA.template.background_image}" name="template[background_image]" placeholder="{LANG.form_template_background_image}" readonly="readonly" /> <span class="input-group-btn">
									<button class="btn btn-default" onclick="nv_selectimg('bg_image')" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.form_template_background_image_chosen}">
										<em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
									</button>
								</span>
							</div>
							<select name="template[background_imgage_repeat]" class="form-control m-bottom">
								<option value="">---{LANG.form_template_background_image_repeat}---</option>
								<!-- BEGIN: background_repeat -->
								<option value="{REPEAT.key}"{REPEAT.selected}>{REPEAT.value}</option>
								<!-- END: background_repeat -->
							</select> <select name="template[background_imgage_position]" class="form-control m-bottom">
								<option value="">---{LANG.form_template_background_image_position}---</option>
								<!-- BEGIN: background_position -->
								<option value="{POSITION.key}"{POSITION.selected}>{POSITION.value}</option>
								<!-- END: background_position -->
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">{LANG.form_report}</div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-4 text-right"><strong>{LANG.form_question_report}</strong></label>
				<div class="col-sm-20">
					<label><input type="checkbox" name="question_report" value="1" {DATA.question_report_check} />{LANG.form_question_report_note}</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 text-right"><strong>{LANG.form_report_type}</strong></label>
				<div class="col-sm-20">
					<!-- BEGIN: form_report_type -->
					<label><input type="radio" name="form_report_type" class="form_report_type" value="{REPORT_TYPE.key}" {REPORT_TYPE.checked} />{REPORT_TYPE.value}&nbsp;&nbsp;&nbsp;</label>
					<!-- END: form_report_type -->
					<div id="form_report_type_email"{form_report_type_email_dipslay}>
						<hr />
						<div class="m-bottom">
							<!-- BEGIN: form_report_type_email -->
							<label><input type="radio" name="form_report_type_email" class="form_report_type_email" value="{REPORT_TYPE_EMAIL.key}" {REPORT_TYPE_EMAIL.checked} />{REPORT_TYPE_EMAIL.value}&nbsp;&nbsp;&nbsp;</label>
							<!-- END: form_report_type_email -->
						</div>
						<div id="group_email"{form_report_type_email_groups_mail_dipslay}>
							<!-- BEGIN: group_email -->
							<div style="display: block">
								<label><input name="group_email[]" type="checkbox" value="{GR_EMAIL.value}" {GR_EMAIL.checked} />{GR_EMAIL.title}</label>
							</div>
							<!-- END: group_email -->
						</div>
						<input type="text" name="listmail" id="listmail" value="{DATA.listmail}" class="form-control" {form_report_type_email_listmail_dipslay} placeholder="{LANG.form_report_type_email_maillist_note}" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" value="{LANG_SUBMIT}" class="btn btn-primary" />
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/colpick.js"></script>

<!-- BEGIN: get_alias -->
<script type="text/javascript">
	
</script>
<!-- END: get_alias -->

<script type="text/javascript">
	$(document).ready(function() {
		$(".datepicker").datepicker({
			dateFormat : "dd/mm/yy",
			changeMonth : !0,
			changeYear : !0,
			showOtherMonths : !0,
			showOn : "focus",
			yearRange : "-90:+0"
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

		$('.form_report_type').change(function() {
			if ($(this).val() == 1 || $(this).val() == 2) {
				$('#form_report_type_email').slideDown();
			} else {
				$('#form_report_type_email').slideUp();
			}
		});

		$('.form_report_type_email').change(function() {
			if ($(this).val() == 0) {
				$('#group_email').show();
				$('#listmail').hide();
			} else {
				$('#group_email').hide();
				$('#listmail').show();
			}
		});
	});

	$('#clearimg').click(function() {
		$('#bg_image').val('');
	});

	function nv_selectimg(area) {
		var path = "{UPLOADS_DIR_USER}";
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable
				+ "=upload&popup=1&area=" + area + "&path=" + path + "&type="
				+ type, "NVImg", 850, 420,
				"resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	}
</script>
<!-- END: main -->