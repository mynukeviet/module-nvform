<!-- BEGIN: main -->
<style type="text/css">
	body {
		font-size: 12px;
	}
	#print{
		padding: 10px;
		background: #FFFFFF;
	}
	#print h2, #print h3, #print h4{
		text-transform: uppercase;
		text-align: center;
		font-weight: bold
	}
	#print ul{
		padding: 0;
		margin-bottom: 30px
	}
</style>
<div id="print">
	<div class="text-center m-bottom">
		<h1>{FORM_INFO.title}</h1>
		<span class="hidden-print"><em class="fa fa-print">&nbsp;</em><a href="#" title="{LANG.report_print}" onclick="window.print(); return false;">{LANG.report_print}</a></span>
	</div>
	<ul>
		<li><strong>{LANG.report_who_answer}:</strong> {ANSWER.username}</li>
		<li><strong>{LANG.report_answer_time}:</strong> {ANSWER.answer_time}</li>
		<li><strong>{LANG.report_answer_edit_time}:</strong> {ANSWER.answer_edit_time}</li>
	</ul>
	<!-- BEGIN: question -->
		<div class="show">
			<span class="m-bottom show"><strong>{LANG.question}:</strong> {QUESTION.title}</span>
			<!-- BEGIN: answer -->
				<span class="m-bottom show"><strong>{LANG.answer}:</strong></span>
				<!-- BEGIN: table -->
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<td>&nbsp;</td>
							<!-- BEGIN: col -->
							<th>{COL.value}</th>
							<!-- END: col -->
						</tr>
					</thead>
					<tbody>
						<!-- BEGIN: row -->
						<tr>
							<th>{ROW.value}</th>
							<!-- BEGIN: td -->
							<td>{VALUE}</td>
							<!-- END: td -->
						</tr>
						<!-- END: row -->
					</tbody>
				</table>
				<!-- END: table -->

				<!-- BEGIN: grid -->
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<!-- BEGIN: col -->
							<th>{COL.value}</th>
							<!-- END: col -->
						</tr>
					</thead>
					<tbody>
						<!-- BEGIN: row -->
						<tr>
							<th>{ROW.value}</th>
							<!-- BEGIN: td -->
							<td>
								<!-- BEGIN: check -->
								<em class="fa fa-check fa-lg">&nbsp;</em>
								<!-- END: check -->

								<!-- BEGIN: no_check -->
								<em class="fa fa-circle-o fa-lg">&nbsp;</em>
								<!-- END: no_check -->
							</td>
							<!-- END: td -->
						</tr>
						<!-- END: row -->
					</tbody>
				</table>
				<!-- END: grid -->

				<!-- BEGIN: other -->
				{ANSWER}
				<!-- END: other -->

			<!-- END: answer -->
		</div>
		<hr />
	<!-- END: question -->
</div>
<!-- END: main -->