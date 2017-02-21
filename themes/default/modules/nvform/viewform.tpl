<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="nvform">
	<!-- BEGIN: dis_title -->
	<h2 class="text-center">{FORM.title}</h2>
	<!-- END: dis_title -->
	<!-- BEGIN: dis_form_info -->
	<p class="text-center text-info">
		<em>{FORM.close_info}</em>
	</p>
	<!-- END: dis_form_info -->
	<!-- BEGIN: dis_description -->
	<p>{FORM.description}</p>
	<!-- END: dis_description -->
	<!-- BEGIN: dis_description_html -->
	<p>{FORM.description_html}</p>
	<!-- END: dis_description_html -->
</div>
<hr />
<!-- BEGIN: info -->
<div class="alert alert-danger">{INFO}</div>
<!-- END: info -->
<form action="{FORM_ACTION}" method="post" id="question"
	<!-- BEGIN: display_left_form -->
	class="form-horizontal"
	<!-- END: display_left_form -->
	<!-- BEGIN: enctype -->
	enctype="multipart/form-data"
	<!-- END: enctype -->
	> <input type="hidden" id="page" value="1" /> <input type="hidden" id="max_page" value="{MAX_PAGE}" />
	<!-- BEGIN: loop -->
	<div class="question_row <!-- BEGIN: display_two_column -->col-xs-24 col-sm-12 col-md-12<!-- END: display_two_column -->" data-page="{PAGE}">
		<div class="form-group">
			<label
				<!-- BEGIN: display_left_label -->class="col-xs-24 col-sm-6 col-md-6 control-label"<!-- END: display_left_label -->>{QUESTION.title}<!-- BEGIN: required -->
				<span class="text-danger"> (*)</span>
			<!-- END: required --></label>
			<div
				<!-- BEGIN: display_left_div -->
				class="col-xs-24 col-sm-18 col-md-18"
				<!-- END: display_left_div -->
				>
				<!-- BEGIN: textbox -->
				<input class="{QUESTION.required} form-control" type="text" name="question[{QUESTION.qid}]" value="{QUESTION.value}" {QUESTION.readonly} />
				<!-- END: textbox -->

				<!-- BEGIN: date -->
				<div class="form-group">
					<div class="input-group">
						<input class="form-control {QUESTION.datepicker} {QUESTION.required}" value="{QUESTION.value}" type="text" id="question_{QUESTION.qid}" name="question[{QUESTION.qid}]" readonly="readonly" /> <span class="input-group-btn">
							<button class="btn btn-default" type="button" onclick="$('#question_{QUESTION.qid}').datepicker('show');">
								<em class="fa fa-calendar fa-fix">&nbsp;</em>
							</button>
						</span>
					</div>
				</div>
				<!-- END: date -->

				<!-- BEGIN: time -->
				<input type="time" class="form-control {QUESTION.required}" id="question_{QUESTION.qid}" name="question[{QUESTION.qid}]" value="{QUESTION.value}">
				<!-- END: time -->

				<!-- BEGIN: textarea -->
				<textarea name="question[{QUESTION.qid}]" class="form-control"{QUESTION.readonly}>{QUESTION.value}</textarea>
				<!-- END: textarea -->

				<!-- BEGIN: editor -->
				{EDITOR}
				<!-- END: editor -->

				<!-- BEGIN: select -->
				<select name="question[{QUESTION.qid}]" data-toggle="{QUESTION.qid}" class="m-bottom form-control"{QUESTION.readonly}>
					<!-- BEGIN: loop -->
					<option value="{QUESTION_CHOICES.key}"{QUESTION_CHOICES.selected}>{QUESTION_CHOICES.value}</option>
					<!-- END: loop -->
				</select>
				<!-- BEGIN: choice_extend -->
				<blockquote class="form-inline toggle_{QUESTION.qid} {QUESTION_CHOICES.key}"{QUESTION_CHOICES.display}>
					<!-- BEGIN: loop -->
					<input type="text" class="form-control m-bottom" name="question_extend[{QUESTION.qid}][{FIELD_CHOICES_EXTEND.number}][{FIELD_CHOICES_EXTEND.key}]" value="{FIELD_CHOICES_EXTEND.value}" placeholder="{FIELD_CHOICES_EXTEND.text}" {FIELD_CHOICES_EXTEND.readonly} />
					<!-- END: loop -->
				</blockquote>
				<!-- END: choice_extend -->
				<!-- END: select -->

				<!-- BEGIN: radio -->
				<label for="lb_{QUESTION_CHOICES.id}" class="none_weight show"> <input type="radio" name="question[{QUESTION.qid}]" data-toggle="{QUESTION.qid}" value="{QUESTION_CHOICES.key}" data-id="lb_{QUESTION_CHOICES.id}" {QUESTION_CHOICES.checked} {QUESTION_CHOICES.readonly} /> {QUESTION_CHOICES.value}
				</label>
				<!-- BEGIN: choice_extend -->
				<blockquote class="form-inline toggle_{QUESTION.qid} lb_{QUESTION_CHOICES.id}"{QUESTION_CHOICES.display}>
					<!-- BEGIN: loop -->
					<input type="text" class="form-control m-bottom" name="question_extend[{QUESTION.qid}][{QUESTION_CHOICES.number}][{FIELD_CHOICES_EXTEND.key}]" value="{FIELD_CHOICES_EXTEND.value}" placeholder="{FIELD_CHOICES_EXTEND.text}" {FIELD_CHOICES_EXTEND.readonly} />
					<!-- END: loop -->
				</blockquote>
				<!-- END: choice_extend -->
				<!-- END: radio -->

				<!-- BEGIN: checkbox -->
				<label for="lb_{QUESTION_CHOICES.id}" class="none_weight show"> <input type="checkbox" name="question[{QUESTION.qid}][]" value="{QUESTION_CHOICES.key}" id="lb_{QUESTION_CHOICES.id}"{QUESTION_CHOICES.checked} {QUESTION_CHOICES.readonly}> {QUESTION_CHOICES.value}
				</label>
				<!-- END: checkbox -->

				<!-- BEGIN: multiselect -->
				<select name="question[{QUESTION.qid}][]" multiple="multiple" class="form-control"{QUESTION.readonly}>
					<!-- BEGIN: loop -->
					<option value="{QUESTION_CHOICES.key}"{QUESTION_CHOICES.selected}>{QUESTION_CHOICES.value}</option>
					<!-- END: loop -->
				</select>
				<!-- END: multiselect -->

				<!-- BEGIN: grid -->
				<table class="table">
					<tr>
						<td>&nbsp;</td>
						<!-- BEGIN: col -->
						<th>{COL.value}</th>
						<!-- END: col -->
					</tr>
					<!-- BEGIN: row -->
					<tr>
						<th>{ROW.value}</th>
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
						<th>{COL.value}</th>
						<!-- END: col -->
					</tr>
					<!-- BEGIN: row -->
					<tr>
						<th>{ROW.value}</th>
						<!-- BEGIN: td -->
						<td><input type="text" class="form-control" name="question[{QUESTION.qid}][{NAME.col}][{NAME.row}]" value="{VALUE}" /></td>
						<!-- END: td -->
					</tr>
					<!-- END: row -->
				</table>
				<!-- END: table -->

				<!-- BEGIN: file -->
				<div class="input-group">
					<input type="text" class="form-control" id="photo_name" value="{QUESTION.value}" disabled> <span class="input-group-btn">
						<button class="btn btn-default" onclick="$('#upload_fileimage').click();" type="button">
							<em class="fa fa-folder-open-o fa-fix">&nbsp;</em> {LANG.file_selectfile}
						</button>
					</span>
				</div>
				<em class="help-block"><strong>{LANG.field_upload_ext_note}:</strong> {QUESTION.file_type}</em> <input type="file" name="question_file_{QUESTION.qid}" id="upload_fileimage" style="visibility: hidden;" />
				<!-- END: file -->

			</div>
		</div>
	</div>
	<!-- END: loop -->
	<div class="clearfix"></div>
	<div class="m-bottom">
		<div class="pull-left">
			<button class="btn btn-danger btn-sm" id="btn-prev"{BREAK_PAGE}>{LANG.prev}</button>
		</div>
		<div class="pull-right">
			<button class="btn btn-danger btn-sm" id="btn-next">{LANG.next}</button>
			<input type="submit" value="{LANG.success}" name="submit" class="btn btn-success" id="btn-submit" style="display: none" />
		</div>
		<div class="clearfix"></div>
	</div>
</form>

<!-- BEGIN: datepicker -->
<script>
$(document).ready(function() {
	$(".datepicker").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		showOn: 'focus',
		yearRange: "-90:+30"
	});
});
</script>
<!-- END: datepicker -->

<!-- END: main -->