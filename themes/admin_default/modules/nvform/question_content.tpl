<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error">
		<span>{ERROR}</span>
	</blockquote>
</div>
<!-- END: error -->

<form action="{FORM_ACTION}" method="post" id="fquestion" class="form-horizontal">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-4 control-label"><strong id="question_title">{LANG.question}</strong></label>
				<div class="col-sm-20">{DATAFORM.title}</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"><strong>{LANG.question_form}</strong></label>
				<div class="col-sm-20">
					<!-- BEGIN: form -->
					<select name="question_form" class="form-control">
						<!-- BEGIN: flist -->
						<option value="{FLIST.id}"{FLIST.selected}>{FLIST.title}</option>
						<!-- END: flist -->
					</select>
					<!-- END: form -->
					<span style="margin-top: 6px; display: block;">{FORM_TEXT}</span>
				</div>
			</div>
			<div class="form-group" id="question_required"{DATAFORM.requireddisabled}>
				<label class="col-sm-4 text-right"><strong>{LANG.question_required}</strong></label>
				<div class="col-sm-20">
					<label><input name="required" value="1" type="checkbox"{DATAFORM.checked_required}> {LANG.question_required_note}</label>
				</div>
			</div>
			<div class="form-group" id="question_user_edit"{DATAFORM.user_editdisabled}>
				<label class="col-sm-4 text-right"><strong>{LANG.question_user_edit}</strong></label>
				<div class="col-sm-20">
					<!-- BEGIN: user_editable -->
					<label><input name="user_editable" value="{EDITABLE.key}" type="radio" {EDITABLE.checked} />{EDITABLE.value}</label>&nbsp;&nbsp;&nbsp;
					<!-- END: user_editable -->
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 text-right"><strong>{LANG.question_break}</strong></label>
				<div class="col-sm-20">
					<label><input name="break" value="1" type="checkbox" {DATAFORM.checked_break}/> {LANG.question_break_note}</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 text-right"><strong>{LANG.question_report}</strong></label>
				<div class="col-sm-20">
					<label><input name="report" id="report" value="0" type="checkbox" {DATAFORM.checked_report} {DATAFORM.reportdisabled} /> {LANG.question_report_note}</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 text-right"><strong>{LANG.question_type}</strong></label>
				<div class="col-sm-20">
					<div class="row">
						<!-- BEGIN: question_type -->
						<div class="col-sm-8">
							<label for="f_{FIELD_TYPE.key}"> <input type="radio" {FIELD_TYPE.checked} id="f_{FIELD_TYPE.key}" value="{FIELD_TYPE.key}" name="question_type"> {FIELD_TYPE.value}
							</label>
						</div>
						<!-- END: question_type -->
					</div>
					{FIELD_TYPE_TEXT}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"><strong>{LANG.question_css}</strong></label>
				<div class="col-sm-20">
					<input class="form-control" name="class" value="{DATAFORM.class}" type="text" />
				</div>
			</div>
		</div>
	</div>

	<div id="textfields"{DATAFORM.display_textquestions}>
		<div class="panel panel-default">
			<div class="panel-heading">{LANG.question_options_text}</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_match_type}</strong></label>
					<div class="col-sm-20">
						<ul style="list-style: none; padding: 0">
							<!-- BEGIN: match_type -->
							<li id="li_{MATCH_TYPE.key}"><label for="m_{MATCH_TYPE.key}"> <input type="radio" {MATCH_TYPE.checked} id="m_{MATCH_TYPE.key}" value="{MATCH_TYPE.key}" name="match_type"> {MATCH_TYPE.value}
							</label> <!-- BEGIN: match_input --> <input type="text" value="{MATCH_TYPE.match_value}" name="match_{MATCH_TYPE.key}"{MATCH_TYPE.match_disabled}> <!-- END: match_input --></li>
							<!-- END: match_type -->
						</ul>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_default_value}</strong></label>
					<div class="col-sm-20">
						<input class="form-control" maxlength="255" type="text" value="{DATAFORM.default_value}" name="default_value">
					</div>
				</div>
				<div id="max_length">
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.question_min_length}</strong></label>
						<div class="col-sm-20">
							<input class="number form-control" type="text" value="{DATAFORM.min_length}" name="min_length">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"><strong>{LANG.question_max_length}</strong></label>
						<div class="col-sm-20">
							<input class="number form-control" type="text" value="{DATAFORM.max_length}" name="max_length">
						</div>
					</div>
				</div>
				<div class="form-group" id="editor_mode"{DATAFORM.display_editorquestions}>
					<label class="col-sm-4 control-label"><strong>{LANG.question_editor_mode}</strong></label>
					<div class="col-sm-20">
						<label><input type="radio" name="editor_mode" value="0" {DATAFORM.editor_mode_0} />{LANG.question_editor_mode_basic}</label>&nbsp;&nbsp; <label><input type="radio" name="editor_mode" value="1" {DATAFORM.editor_mode_1} />{LANG.question_editor_mode_advance}</label>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="numberfields"{DATAFORM.display_numberquestions}>
		<div class="panel panel-default">
			<div class="panel-heading">{LANG.question_options_number}</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 text-right"><strong>{LANG.question_number_type}</strong></label>
					<div class="col-sm-20">
						<input type="radio" value="1" name="number_type"{DATAFORM.number_type_1}>{LANG.question_integer} <input type="radio" value="2" name="number_type"{DATAFORM.number_type_2}> {LANG.question_real}
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_default_value}</strong></label>
					<div class="col-sm-20">
						<input class="required number form-control" maxlength="255" type="text" value="{DATAFORM.default_value_number}" name="default_value_number">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_min_value}</strong></label>
					<div class="col-sm-20">
						<input class="required number form-control" type="text" value="{DATAFORM.min_number}" name="min_number_length" maxlength="11">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_min_value}</strong></label>
					<div class="col-sm-20">
						<input class="required number form-control" type="text" value="{DATAFORM.max_number}" name="max_number_length" maxlength="11">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="datefields"{DATAFORM.display_datequestions}>
		<div class="panel panel-default">
			<div class="panel-heading">{LANG.question_options_date}</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 text-right"><strong>{LANG.question_default_value}</strong></label>
					<div class="col-sm-20">
						<label><input type="radio" value="2" name="current_date"{DATAFORM.current_date_2}> {LANG.question_empty}</label>&nbsp;&nbsp;&nbsp; <label><input type="radio" value="1" name="current_date"{DATAFORM.current_date_1}>{LANG.question_current_date}</label>&nbsp;&nbsp;&nbsp; <label><input type="radio" value="0" name="current_date"{DATAFORM.current_date_0}> {LANG.question_default_date}</label>
						<div class="input-group" id="default_date"{DATAFORM.default_date_display}>
							<input class="date form-control datepicker" type="text" value="{DATAFORM.default_date}" name="default_date"> <span class="input-group-btn">
								<button class="btn btn-default" type="button" id="default_date-btn">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 text-right"><strong>{LANG.question_min_date}</strong></label>
					<div class="col-sm-20">
						<div class="input-group pull-left">
							<input class="datepicker required date form-control pull-left" style="width: 100px" type="text" value="{DATAFORM.min_date}" name="min_date" id="min_date" maxlength="10"> <span class="input-group-btn pull-left">
								<button class="btn btn-default" type="button" id="min_date-btn">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
						<span style="margin-left: 30px;" class="pull-left text-middle">{LANG.question_max_date}:</span>
						<div class="input-group pull-left">
							<input class="datepicker required date form-control" style="width: 100px" type="text" value="{DATAFORM.max_date}" name="max_date" id="max_date" maxlength="10"> <span class="input-group-btn pull-left">
								<button class="btn btn-default" type="button" id="max_date-btn">
									<em class="fa fa-calendar fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="timefields"{DATAFORM.display_timequestions}>
		<div class="panel panel-default">
			<div class="panel-heading">{LANG.question_options_time}</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 text-right"><strong>{LANG.question_default_value}</strong></label>
					<div class="col-sm-20">
						<label><input type="radio" value="1" name="current_time"{DATAFORM.current_time_1}>{LANG.question_current_time}</label> <label><input type="radio" value="0" name="current_time"{DATAFORM.current_time_0}> {LANG.question_default_time}</label>
						<div class="input-group">
							<input class="form-control" type="time" value="{DATAFORM.default_time}" name="default_time"> <span class="input-group-btn">
								<button class="btn btn-default" type="button">
									<em class="fa fa-clock-o fa-fix">&nbsp;</em>
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="choicefields"{DATAFORM.display_choiceitems}>
		<div class="panel panel-default">
			<div class="panel-heading">{LANG.question_options_choice}</div>
			<table class="table table-striped table-bordered table-hover">
				<colgroup>
					<col class="w50" />
					<col class="w150" />
					<col />
					<col class="w100" />
					<col class="w50" />
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">{LANG.question_number}</th>
						<th>{LANG.question_value}</th>
						<th>{LANG.question_text}</th>
						<th class="text-center">{LANG.question_default_value}</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="5"><input type="button" class="btn btn-success btn-xs" value="{LANG.question_add_choice}" onclick="nv_choice_fields_additem();" /></td>
					</tr>
				</tfoot>
				<tbody id="choiceitems">
					<!-- BEGIN: loop_field_choice -->
					<tr class="choiceitems_row_{FIELD_CHOICES.number}">
						<td class="text-center">{FIELD_CHOICES.number}</td>
						<td><input class="validalphanumeric form-control" type="text" value="{FIELD_CHOICES.key}" name="question_choice[{FIELD_CHOICES.number}]" /></td>
						<td><input class="form-control" type="text" value="{FIELD_CHOICES.value}" name="question_choice_text[{FIELD_CHOICES.number}]" /></td>
						<td class="text-center"><input type="radio" {FIELD_CHOICES.checked} value="{FIELD_CHOICES.number}" name="default_value_choice"></td>
						<td class="text-center">
							<button id="button_extend_{FIELD_CHOICES.number}" class="btn btn-primary btn-xs" data-number="{FIELD_CHOICES.number}" data-number-extend="{FIELD_CHOICES_EXTEND_NUMBER}" onclick="nv_choice_fields_extend( $(this).data('number'), $(this).data('number-extend') ); return false;" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.question_add_choice_extend}">
								<em class="fa fa-code-fork">&nbsp;</em>
							</button>
						</td>
					</tr>
					<!-- BEGIN: loop_field_choice_extend -->
					<tr class="choiceitems_row_{FIELD_CHOICES.number}">
						<td colspan="2">&nbsp;</td>
						<td colspan="4"><input type="text" class="form-control" name="question_choice_extend[{FIELD_CHOICES.number}][{FIELD_CHOICES_EXTEND.number}]" value="{FIELD_CHOICES_EXTEND.value}" placeholder="{LANG.question_text}" /></td>
					</tr>
					<!-- END: loop_field_choice_extend -->
					<!-- END: loop_field_choice -->
				</tbody>
			</table>
		</div>
	</div>

	<div id="gridfields"{DATAFORM.display_gridfields}>
		<div class="panel panel-default">
			<div class="panel-heading">{LANG.question_options_grid}</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_options_grid_col}</strong></label>
					<div class="col-sm-20">
						<div id="question_grid_col">
							<!-- BEGIN: loop_question_grid_col -->
							<div class="row">
								<div class="col-sm-4">
									<input type="text" class="form-control m-bottom validalphanumeric" name="question_grid_col[{COL.number}][key]" value="{COL.key}" placeholder="{LANG.question_value}" />
								</div>
								<div class="col-sm-19">
									<input type="text" class="form-control m-bottom" name="question_grid_col[{COL.number}][value]" value="{COL.value}" placeholder="{LANG.question_text}" />
								</div>
								<div class="col-sm-1 text-middle">
									<input type="radio" name="question_grid_col_default" value="{COL.number}" {COL.checked} />
								</div>
							</div>
							<!-- END: loop_question_grid_col -->
						</div>
						<a class="btn btn-success btn-xs" href="javascript:void(0)" onclick="nv_question_grid_col_additem()">{LANG.question_add_col}</a>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_options_grid_row}</strong></label>
					<div class="col-sm-20">
						<div id="question_grid_row">
							<!-- BEGIN: loop_question_grid_row -->
							<div class="row">
								<div class="col-sm-4">
									<input type="text" class="form-control m-bottom validalphanumeric" name="question_grid_row[{ROW.number}][key]" value="{ROW.key}" placeholder="{LANG.question_value}" />
								</div>
								<div class="col-sm-19">
									<input type="text" class="form-control m-bottom" name="question_grid_row[{ROW.number}][value]" value="{ROW.value}" placeholder="{LANG.question_text}" />
								</div>
								<div class="col-sm-1 text-middle">
									<input type="radio" name="question_grid_row_default" value="{ROW.number}" {ROW.checked} />
								</div>
							</div>
							<!-- END: loop_question_grid_row -->
						</div>
						<a class="btn btn-success btn-xs" href="javascript:void(0)" onclick="nv_question_grid_row_additem()">{LANG.question_add_row}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="filefields"{DATAFORM.display_filefields}>
		<div class="panel panel-default">
			<div class="panel-heading">{LANG.question_options_file}</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_options_file_max_size}</strong></label>
					<div class="col-sm-20">
						<select class="form-control" name="nv_max_size">
							<!-- BEGIN: size -->
							<option value="{SIZE.key}"{SIZE.selected}>{SIZE.title}</option>
							<!-- END: size -->
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_options_file_allow}</strong></label>
					<div class="col-sm-20">
						<!-- BEGIN: types -->
						<label style="display: inline-block; width: 100px"><input type="checkbox" name="type[]" value="{TYPES.key}" {TYPES.checked}/> {TYPES.title}&nbsp;&nbsp;</label>
						<!-- END: types -->
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><strong>{LANG.question_options_file_ext_ban}</strong></label>
					<div class="col-sm-20">
						<!-- BEGIN: exts -->
						<label style="display: inline-block; width: 100px"><input type="checkbox" name="ext[]" value="{EXTS.key}" {EXTS.checked} /> {EXTS.title}&nbsp;&nbsp;</label>
						<!-- END: exts -->
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="text-center">
		<input type="hidden" value="{DATAFORM.fid}" name="fid"> <input class="w150 btn btn-primary" type="submit" value="{LANG_SUBMIT}" name="submit">
	</div>
</form>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
	var items = '{FIELD_CHOICES_NUMBER}';
	function nv_choice_fields_additem() {
		items++;
		var newitem = '<tr class="center">';
		newitem += '	<td class="text-center">' + items + '</td>';
		newitem += '	<td><input class="validalphanumeric form-control" type="text" value="" name="question_choice[' + items + ']"></td>';
		newitem += '	<td><input class="form-control" type="text" value="" name="question_choice_text[' + items + ']"></td>';
		newitem += '	<td class="text-center"><input type="radio" value="' + items + '" name="default_value_choice"></td>';
		newitem += '	<td class="text-center"><button class="btn btn-primary btn-xs" onclick="nv_choice_fields_extend( \"items_' + items + '\" ); return false;" data-toggle="tooltip" data-placement="top" title="" data-original-title="{LANG.question_add_choice_extend}"><em class="fa fa-code-fork">&nbsp;</em></button></td>';
		newitem += '	</tr>';
		$('#choiceitems').append(newitem);
	}

	function nv_choice_fields_extend( item_id, items_extend ) {
		items_extend++;
		$('#button_extend_'+item_id).data('number-extend', items_extend );
		var newitem = '<tr class="choiceitems_row_' + item_id + '">';
		newitem += '	<td colspan="2">&nbsp;</td>';
		newitem += '	<td colspan="4"><input type="text" class="form-control" name="question_choice_extend[' + item_id + '][' + items_extend + ']" placeholder="{LANG.question_text}" /></td>';
		newitem += '</tr>';
		$('.choiceitems_row_'+item_id+':last').after(newitem);
	}

	var col_numfield = '{COL_NUMFIELD}';
	function nv_question_grid_col_additem() {
		col_numfield++;
		var newitem = '';
		newitem += '<div class="row">';
		newitem += '<div class="col-sm-4">';
		newitem += '	<input type="text" class="form-control m-bottom validalphanumeric" name="question_grid_col[' + col_numfield + '][key]" value="" placeholder="{LANG.question_value}" />';
		newitem += '</div>';
		newitem += '<div class="col-sm-19">';
		newitem += '	<input type="text" class="form-control m-bottom" name="question_grid_col[' + col_numfield + '][value]" value="" placeholder="{LANG.question_text}" />';
		newitem += '</div>';
		newitem += '<div class="col-sm-1 text-middle">';
		newitem += '	<input type="radio" name="question_grid_col_default" value="" />';
		newitem += '</div>';
		newitem += '</div>';
		$('#question_grid_col').append(newitem);
	}

	var row_numfield = '{ROW_NUMFIELD}';
	function nv_question_grid_row_additem() {
		row_numfield++;
		var newitem = '';
		newitem += '<div class="row">';
		newitem += '<div class="col-sm-4">';
		newitem += '	<input type="text" class="form-control m-bottom validalphanumeric" name="question_grid_row[' + row_numfield + '][key]" value="" placeholder="{LANG.question_value}" />';
		newitem += '</div>';
		newitem += '<div class="col-sm-19">';
		newitem += '	<input type="text" class="form-control m-bottom" name="question_grid_row[' + row_numfield + '][value]" value="" placeholder="{LANG.question_text}" />';
		newitem += '</div>';
		newitem += '<div class="col-sm-1 text-middle">';
		newitem += '	<input type="radio" name="question_grid_row_default" value="" />';
		newitem += '</div>';
		newitem += '</div>';
		$('#question_grid_row').append(newitem);
	}


	$(document).ready(function() {
		$('input[name="current_date"]').change(function(){
			if( $(this).val() == 0 ){
				$('#default_date').show();
				$("input[name=default_date]").datepicker({
					dateFormat : "dd/mm/yy",
					changeMonth : true,
					changeYear : true,
					showOtherMonths : true,
					showOn : 'focus',
					yearRange: "-90:+30"
				});
				$("input[name=default_date]").removeAttr("disabled");
				$("input[name=default_date]").focus();
			}
			else{
				$('#default_date').hide();
			}
		});

		$('#default_date-btn').click(function() {
			$("input[name=default_date]").datepicker('show');
		});

		$('#min_date-btn').click(function() {
			$("#min_date").datepicker('show');
		});

		$('#max_date-btn').click(function() {
			$("#max_date").datepicker('show');
		});

		$('#fquestion').validate({
			rules : {
				question : {
					required : true
				}
			}
		});

		$.validator.addMethod('validalphanumeric', function(str) {
			if (str == '') {
				return true;
			}
			var fieldCheck_rule = /^([a-zA-Z0-9_])+$/;
			return (fieldCheck_rule.test(str) ) ? true : false;
		}, ' required a-z, 0-9, and _ only');
	});

	$(".datepicker").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		showOn : 'focus',
		yearRange: "-90:+30"
	});

	$("input[name=question_type]").click(function() {
		var question_type = $("input[name='question_type']:checked").val();
		$("#textfields").hide();
		$("#numberfields").hide();
		$("#datefields").hide();
		$("#timefields").hide();
		$("#choicetypes").hide();
		$("#choicefields").hide();
		$("#gridfields").hide();
		$("#filefields").hide();
		$("#question_required").show();
		$("#question_user_edit").show();
		$("#report").prop( 'checked', false );
		$("#report").prop( 'disabled', false );
		$("#question_title").html( '{LANG.question}' );
		if (question_type == 'textbox' || question_type == 'textarea' || question_type == 'editor') {
			if (question_type == 'textbox') {
				$("#li_alphanumeric").show();
				$("#li_email").show();
				$("#li_url").show();
			} else if (question_type == 'editor') {
				$('#editor_mode').show();
			} else {
				$('#editor_mode').hide();
				$("#li_alphanumeric").hide();
				$("#li_email").hide();
				$("#li_url").hide();
			}
			$("#textfields").show();
		} else if (question_type == 'number') {
			$("#numberfields").show();
		} else if (question_type == 'date') {
			$("#datefields").show();
		} else if (question_type == 'time') {
			$("#timefields").show();
		} else if (question_type == 'grid' || question_type == 'table') {
			$("#gridfields").show();
		} else if (question_type == 'file') {
			$("#filefields").show();
		} else if (question_type == 'plaintext') {
			$("#textfields").hide();
			$("#question_required").hide();
			$("#question_user_edit").hide();
			$("#report").prop( 'checked', true );
			$("#report").prop( 'disabled', true );
			$("#question_title").html( '{LANG.question_type_plaintext_content}' );
		} else {
			$("#choicefields").show();
		}
	});

	$("input[name=match_type]").click(function() {
		$("input[name=match_regex]").attr('disabled', 'disabled');
		$("input[name=match_callback]").attr('disabled', 'disabled');
		var match_type = $("input[name='match_type']:checked").val();
		var max_length = $("input[name=max_length]").val();
		if (match_type == 'number') {
			if (max_length == 255) {
				$("input[name=max_length]").val(11);
			}
		} else if (max_length == 11) {
			$("input[name=max_length]").val(255);
		}
		if (match_type == 'regex') {
			$("input[name=match_regex]").removeAttr("disabled");
		} else if (match_type == 'callback') {
			$("input[name=match_callback]").removeAttr("disabled");
		}
	});
</script>
<!-- END: main -->