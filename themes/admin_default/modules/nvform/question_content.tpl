<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<!-- BEGIN: error -->
<div class="quote">
	<blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<!-- END: error -->

<form action="{FORM_ACTION}" method="post" id="fquestion">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w250" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.question}</td>
					<td><textarea cols="60" rows="3" id="question" name="question" class="form-control w350">{DATAFORM.title}</textarea></td>
				</tr>
				<tr>
					<td>{LANG.question_form}</td>
					<td>
						<!-- BEGIN: form -->
						<select name="question_form" class="form-control w350">
							<!-- BEGIN: flist -->
							<option value="{FLIST.id}" {FLIST.selected}>{FLIST.title}</option>
							<!-- END: flist -->
						</select>
						<!-- END: form -->
						{FORM_TEXT}
					</td>
				</tr>
				<tr>
					<td>{LANG.question_required}</td>
					<td><input name="required" value="1" type="checkbox" {DATAFORM.checked_required}> {LANG.question_required_note}</td>
				</tr>
				<tr>
					<td>{LANG.question_user_edit}</td>
					<td><input name="user_editable" value="1" type="checkbox" {DATAFORM.checked_user_editable}/> {LANG.question_user_edit_note}</td>
				</tr>
				<tr>
					<td>{LANG.question_type}:</td>
					<td>
						<ul style="list-style: none; padding: 0">
							<!-- BEGIN: question_type -->
							<li>
								<label for="f_{FIELD_TYPE.key}"> <input type="radio" {FIELD_TYPE.checked} id="f_{FIELD_TYPE.key}" value="{FIELD_TYPE.key}" name="question_type"> {FIELD_TYPE.value}</label>
							</li>
							<!-- END: question_type -->
						</ul>{FIELD_TYPE_TEXT}
					</td>
				</tr>
				<tr id="classfields" {DATAFORM.classdisabled}>
					<td>{LANG.question_class}</td>
					<td><input class="w350 validalphanumeric form-control" type="text" value="{DATAFORM.class}" name="class" maxlength="50"></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover" id="textfields" {DATAFORM.display_textquestions}>
			<caption>{LANG.question_options_text}</caption>
			<colgroup>
				<col class="w250" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.question_match_type}</td>
					<td>
						<ul style="list-style: none; padding: 0">
							<!-- BEGIN: match_type -->
							<li id="li_{MATCH_TYPE.key}">
								<label for="m_{MATCH_TYPE.key}"> <input type="radio" {MATCH_TYPE.checked} id="m_{MATCH_TYPE.key}" value="{MATCH_TYPE.key}" name="match_type"> {MATCH_TYPE.value}</label>
								<!-- BEGIN: match_input -->
								<input type="text" value="{MATCH_TYPE.match_value}" name="match_{MATCH_TYPE.key}" {MATCH_TYPE.match_disabled}>
								<!-- END: match_input -->
							</li>
							<!-- END: match_type -->
						</ul>
					</td>
				</tr>
				<tr>
					<td>{LANG.question_default_value}:</td>
					<td><input class="w350 form-control" maxlength="255" type="text" value="{DATAFORM.default_value}" name="default_value"></td>
				</tr>
				<tr id="max_length">
					<td>{LANG.question_min_length}:</td>
					<td><input class="w100 number form-control pull-left" type="text" value="{DATAFORM.min_length}" name="min_length"><span style="margin-left: 30px;" class="text-middle pull-left">{LANG.question_max_length}:</span><input class="w100 number form-control" type="text" value="{DATAFORM.max_length}" name="max_length"></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover" id="numberfields" {DATAFORM.display_numberquestions}>
			<caption>{LANG.question_options_number}</caption>
			<colgroup>
				<col class="w250" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.question_number_type}:</td>
					<td><input type="radio" value="1" name="number_type" {DATAFORM.number_type_1}>{LANG.question_integer} <input type="radio" value="2" name="number_type" {DATAFORM.number_type_2}> {LANG.question_real} </td>
				</tr>
				<tr>
					<td>{LANG.question_default_value}:</td>
					<td><input class="w350 required number form-control" maxlength="255" type="text" value="{DATAFORM.default_value_number}" name="default_value_number"></td>
				</tr>
				<tr>
					<td>{LANG.question_min_length}:</td>
					<td><input class="w150 required number form-control pull-left" type="text" value="{DATAFORM.min_number}" name="min_number_length" maxlength="11"><span style="margin-left: 30px;" class="pull-left text-middle">{LANG.question_max_length}:</span><input class="w100 required number form-control" type="text" value="{DATAFORM.max_number}" name="max_number_length" maxlength="11"></td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover" id="datefields" {DATAFORM.display_datequestions}>
			<caption>{LANG.question_options_date}</caption>
			<colgroup>
				<col class="w250" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td>{LANG.question_default_value}:</td>
					<td>
						<label><input type="radio" value="1" name="current_date" {DATAFORM.current_date_1}>{LANG.question_current_date}</label>
						<br />
						<table>
						<tr>
							<td><input type="radio" value="0" name="current_date" {DATAFORM.current_date_0}> {LANG.question_default_date}</td>
							<td>
								<div class="input-group">
									<input class="date form-control" style="width:100px" type="text" value="{DATAFORM.default_date}" name="default_date" id="default_date">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" id="default_date-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
									</span>
								</div>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>{LANG.question_min_date}:</td>
					<td>
						<div class="input-group pull-left">
							<input class="datepicker required date form-control pull-left" style="width:100px" type="text" value="{DATAFORM.min_date}" name="min_date" id="min_date" maxlength="10">
							<span class="input-group-btn pull-left">
								<button class="btn btn-default" type="button" id="min_date-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
							</span>
						</div>
						<span style="margin-left: 30px;" class="pull-left text-middle">{LANG.question_max_date}:</span>
						<div class="input-group pull-left">
							<input class="datepicker required date form-control" style="width:100px" type="text" value="{DATAFORM.max_date}" name="max_date" id="max_date" maxlength="10">
							<span class="input-group-btn pull-left">
								<button class="btn btn-default" type="button" id="max_date-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
							</span>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover" id="choiceitems" {DATAFORM.display_choiceitems}>
		<caption>{LANG.field_options_choice}</caption>
			<colgroup>
				<col class="w250" />
				<col span="3"/>
			</colgroup>
			<thead>
				<tr class="center">
					<th>{LANG.question_number}</th>
					<th>{LANG.question_value}</th>
					<th>{LANG.question_text}</th>
					<th>{LANG.question_default_value}</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4" ><input style="margin-left: 50px;" type="button" class="btn btn-success" value="{LANG.question_add_choice}" onclick="nv_choice_fields_additem();" /></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop_field_choice -->
				<tr class="center">
					<td>{FIELD_CHOICES.number}</td>
					<td><input class="w100 validalphanumeric form-control" type="text" value="{FIELD_CHOICES.key}" name="question_choice[{FIELD_CHOICES.number}]" /></td>
					<td><input class="w350 form-control" type="text" value="{FIELD_CHOICES.value}" name="question_choice_text[{FIELD_CHOICES.number}]" /></td>
					<td><input type="radio" {FIELD_CHOICES.checked} value="{FIELD_CHOICES.number}" name="default_value_choice"></td>
				</tr>
				<!-- END: loop_field_choice -->
			</tbody>
		</table>
	</div>
	<div class="text-center">
		<input type="hidden" value="{DATAFORM.fid}" name="fid">
		<input class="w150 btn btn-primary" type="submit" value="{LANG_SUBMIT}" name="submit">
	</div>
</form>

<script type="text/javascript">
	var items = '{FIELD_CHOICES_NUMBER}';
	function nv_choice_fields_additem() {
		items++;
		var newitem = '<tr class="center">';
		newitem += '	<td>' + items + '</td>';
		newitem += '	<td><input class="w100 validalphanumeric form-control" type="text" value="" name="question_choice[' + items + ']"></td>';
		newitem += '	<td><input class="w350 form-control" type="text" value="" name="question_choice_text[' + items + ']"></td>';
		newitem += '	<td><input type="radio" value="' + items + '" name="default_value_choice"></td>';
		newitem += '	</tr>';
		$('#choiceitems').append(newitem);
	}
	
	$(document).ready(function() {
		nv_load_current_date();
		
		$('#default_date-btn').click(function(){
			$("#default_date").datepicker('show');
		});
		
		$('#min_date-btn').click(function(){
			$("#min_date").datepicker('show');
		});
		
		$('#max_date-btn').click(function(){
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
	
	function nv_load_current_date() {
		if ($("input[name=current_date]:checked").val() == 1) {
			$("input[name=default_date]").attr('disabled', 'disabled');
			$("input[name=default_date]").datepicker("destroy");
		} else {
			$("input[name=default_date]").datepicker({
				dateFormat : "dd/mm/yy",
				changeMonth : true,
				changeYear : true,
				showOtherMonths : true,
				showOn: 'focus'
			});
			$("input[name=default_date]").removeAttr("disabled");
			$("input[name=default_date]").focus();
		}
	}
	
	$(".datepicker").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		showOn: 'focus'
	});
	
	$("input[name=question_type]").click(function() {
		var question_type = $("input[name='question_type']:checked").val();
		$("#textfields").hide();
		$("#numberfields").hide();
		$("#datefields").hide();
		$("#choicetypes").hide();
		$("#choiceitems").hide();
		if (question_type == 'textbox' || question_type == 'textarea' || question_type == 'editor') {
			if (question_type == 'textbox') {
				$("#li_alphanumeric").show();
				$("#li_email").show();
				$("#li_url").show();
			} else {
				$("#li_alphanumeric").hide();
				$("#li_email").hide();
				$("#li_url").hide();
			}
			$("#textfields").show();
		} else if (question_type == 'number') {
			$("#numberfields").show();
		} else if (question_type == 'date') {
			$("#datefields").show();
		} else {
			$("#choiceitems").show();
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
