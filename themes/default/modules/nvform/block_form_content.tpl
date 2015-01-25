<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<!-- BEGIN: form_info -->
<div class="nvform">
	<h2 class="text-center">{FORM.title}</h2>
	<p>{FORM.description}</p>
</div>
<hr />
<!-- END: form_info -->

<!-- BEGIN: info -->
<div class="alert alert-danger">{INFO}</div>
<!-- END: info -->

<form action="{FORM_ACTION}" {FORM_LEFT} method="post" id="question_form">
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
});
</script>
<!-- END: main -->