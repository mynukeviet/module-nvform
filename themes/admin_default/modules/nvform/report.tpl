<!-- BEGIN: main -->
<div class="pull-left m-bottom">{COUNT}</div>
<div class="pull-right m-bottom">
	<a href="{URL_ANALYTICS}" target="_blank" class="btn btn-danger btn-xs"> <em class="fa fa-area-chart">&nbsp;</em>{LANG.report_chart}
	</a>
	<!-- Split button -->
	<div class="btn-group">
		<button id="open_modal" data-fid="{FID}" class="btn btn-primary btn-xs" data-lang_ex="{LANG.report_ex}">
			<em class="fa fa-floppy-o">&nbsp;</em>{LANG.report_ex}
		</button>
		<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="caret"></span> <span class="sr-only">Toggle Dropdown</span>
		</button>
		<ul class="dropdown-menu pull-right">
			<li><a href="javascript:void(0)" data-fid="{FID}" id="ex_onine"><em class="fa fa-file-excel-o">&nbsp;&nbsp;</em>{LANG.report_ex_online}</a></li>
		</ul>
	</div>
</div>
<div class="clearfix">&nbsp;</div>

<div class="table-responsive" style="width: 100%; height: 100%; overflow: scroll">
	<table class="table table-striped table-bordered table-hover" id="table_report">
		<colgroup>
			<col width="60" />
			<col width="20" />
			<col class="w150" />
			<col width="120" />
			<col width="120" />
		</colgroup>
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>STT</th>
				<th>{LANG.report_who_answer}</th>
				<th>{LANG.report_answer_time}</th>
				<th>{LANG.report_answer_edit_time}</th>
				<!-- BEGIN: thead -->
				<th><span href="#" style="cursor: pointer" rel='tooltip' data-html="true" data-toggle="tooltip" data-placement="bottom" title="<p class='text-justify'>{QUESTION.title}</p>">{QUESTION.title_cut}</span></th>
				<!-- END: thead -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: tr -->
			<tr>
				<td class="danger text-center"><a href="javascript:void(0);" rel='tooltip' data-html="true" data-toggle="tooltip" data-placement="top" title="{GLANG.delete}" onclick="nv_del_answer({ANSWER.id});"><em class="fa fa-trash-o fa-lg">&nbsp;</em></a> <a href="#" rel='tooltip' data-html="true" data-toggle="tooltip" data-placement="top" title="{LANG.report_viewpage}" onclick="nv_open_windown('{ANSWER.answer_view_url}');"><em class="fa fa-search fa-lg">&nbsp;</em></a></td>
				<td class="success text-center">{ANSWER.no}</td>
				<td class="success">{ANSWER.username}</td>
				<td class="success">{ANSWER.answer_time}</td>
				<td class="success">{ANSWER.answer_edit_time}</td>
				<!-- BEGIN: td -->
				<td>
					<!-- BEGIN: table --> <a href="#" title="" onclick="modalShow('Chức năng đang hoàn thiện', 'Chức năng đang hoàn thiện'); return false;">{LANG.report_viewtable}</a> <!-- END: table --> <!-- BEGIN: files --> <a href="{FILES}" title="">{LANG.question_options_file_dowload}</a> <!-- END: files --> <!-- BEGIN: other --> {ANSWER} <!-- END: other -->
				</td>
				<!-- END: td -->
			</tr>
			<!-- END: tr -->
		</tbody>
	</table>
</div>
<!-- END: main -->