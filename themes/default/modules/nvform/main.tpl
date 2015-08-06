<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="nvform">
	<h2 class="text-center">{FORM.title}</h2>
	<p class="text-center text-info"><em>{FORM.close_info}</em></p>
	<p>{FORM.description}</p>
</div>
<hr />
<!-- BEGIN: info -->
<div class="alert alert-danger">{INFO}</div>
<!-- END: info -->
<form action="" {FORM_LEFT} method="post" id="question" <!-- BEGIN: enctype -->enctype="multipart/form-data"<!-- END: enctype -->>
	<!-- BEGIN: loop -->
		<div class="form-group">
		<label {LEFT.label}>{QUESTION.title}<!-- BEGIN: required --><span class="text-danger"> (*)</span><!-- END: required -->:</label>
		<div {LEFT.div}>
			<!-- BEGIN: textbox -->
				<input class="{QUESTION.required} {QUESTION.class} form-control" type="text" name="question[{QUESTION.qid}]" value="{QUESTION.value}" {QUESTION.readonly} />
			<!-- END: textbox -->

			<!-- BEGIN: date -->
				<input type="text" class="form-control {QUESTION.datepicker} {QUESTION.required} {QUESTION.class}" id="question[{QUESTION.qid}]" name="question[{QUESTION.qid}]" value="{QUESTION.value}" readonly="readonly">
			<!-- END: date -->

			<!-- BEGIN: time -->
				<input type="time" class="form-control {QUESTION.required} {QUESTION.class}" id="question[{QUESTION.qid}]" name="question[{QUESTION.qid}]" value="{QUESTION.value}">
			<!-- END: time -->

			<!-- BEGIN: textarea -->
			<textarea name="question[{QUESTION.qid}]" class="{QUESTION.class} form-control" {QUESTION.readonly}>{QUESTION.value}</textarea>
			<!-- END: textarea -->

			<!-- BEGIN: editor -->
			{EDITOR}
			<!-- END: editor -->

			<!-- BEGIN: select -->
			<select name="question[{QUESTION.qid}]" class="{QUESTION.class} form-control" {QUESTION.readonly}>
				<!-- BEGIN: loop -->
				<option value="{QUESTION_CHOICES.key}" {QUESTION_CHOICES.selected}>{QUESTION_CHOICES.value}</option>
				<!-- END: loop -->
			</select>
			<!-- END: select -->

			<!-- BEGIN: radio -->
			<label for="lb_{QUESTION_CHOICES.id}"> <input type="radio" name="question[{QUESTION.qid}]" value="{QUESTION_CHOICES.key}" id="lb_{QUESTION_CHOICES.id}" class="{QUESTION.class}" {QUESTION_CHOICES.checked} {QUESTION_CHOICES.readonly}> {QUESTION_CHOICES.value} </label>
			<!-- END: radio -->

			<!-- BEGIN: checkbox -->
			<label for="lb_{QUESTION_CHOICES.id}"> <input type="checkbox" name="question[{QUESTION.qid}][]" value="{QUESTION_CHOICES.key}" id="lb_{QUESTION_CHOICES.id}" class="{QUESTION.class}" {QUESTION_CHOICES.checked} {QUESTION_CHOICES.readonly}> {QUESTION_CHOICES.value} </label>
			<!-- END: checkbox -->

			<!-- BEGIN: multiselect -->
			<select name="question[{QUESTION.qid}][]" multiple="multiple" class="{QUESTION.class} form-control" {QUESTION.readonly}>
				<!-- BEGIN: loop -->
				<option value="{QUESTION_CHOICES.key}" {QUESTION_CHOICES.selected}>{QUESTION_CHOICES.value}</option>
				<!-- END: loop -->
			</select>
			<!-- END: multiselect -->

			<!-- BEGIN: grid -->
			<table class="table">
				<tr>
					<td>&nbsp;</td>
					<!-- BEGIN: col -->
					<td>{COL.value}</td>
					<!-- END: col -->
				</tr>
				<!-- BEGIN: row -->
				<tr>
					<td>{ROW.value}</td>
					<!-- BEGIN: td -->
					<td><input type="radio" name="question[{QUESTION.qid}]" value="{GRID.value}" {GRID.checked} /></td>
					<!-- END: td -->
				</tr>
				<!-- END: row -->
			</table>
			<!-- END: grid -->

			<!-- BEGIN: table -->
			<table class="table table-striped table-bordered table-hover">
				<tr>
					<td>&nbsp;</td>
					<!-- BEGIN: col -->
					<td>{COL.value}</td>
					<!-- END: col -->
				</tr>
				<!-- BEGIN: row -->
				<tr>
					<td>{ROW.value}</td>
					<!-- BEGIN: td -->
					<td><input type="text" class="form-control" name="question[{QUESTION.qid}][{NAME.col}][{NAME.row}]" value="{VALUE}" /></td>
					<!-- END: td -->
				</tr>
				<!-- END: row -->
			</table>
			<!-- END: table -->

			<!-- BEGIN: file -->
			<div class="input-group">
				<input type="text" class="form-control" id="photo_name" value="{QUESTION.value}" disabled>
				<span class="input-group-btn">
				<button class="btn btn-default" onclick="$('#upload_fileimage').click();" type="button"><em class="fa fa-folder-open-o fa-fix">&nbsp;</em> {LANG.file_selectfile}</button>
				</span>
			</div>
			<em class="help-block"><strong>{LANG.field_upload_ext_note}:</strong> {QUESTION.file_type}</em>
			<input type="file" name="question_file_{QUESTION.qid}" id="upload_fileimage" style="visibility: hidden;" />
			<!-- END: file -->

			</div>
		</div>

	<!-- END: loop -->
	<div class="text-center" style="margin-bottom: 20px">
		<input type="submit" value="{LANG.success}" name="submit" class="btn btn-success" />
		<input type="reset" value="{LANG.reset}" class="btn btn-danger" />
	</div>
</form>

<script type="text/javascript">
$(document).ready(function() {
	$(".datepicker").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		showOn: 'focus'
	});

	$('#upload_fileupload').change(function(){
	     $('#file_name').val($(this).val().match(/[-_\w]+[.][\w]+$/i)[0]);
	});

	$('#upload_fileimage').change(function(){
	     $('#photo_name').val($(this).val().match(/[-_\w]+[.][\w]+$/i)[0]);
	});

});
</script>
<!-- END: main -->