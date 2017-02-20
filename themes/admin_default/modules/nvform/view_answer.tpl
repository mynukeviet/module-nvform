<!-- BEGIN: main -->
<style type="text/css">
body {
	font-size: 12px;
}

#print {
	padding: 10px;
	background: #FFFFFF;
}

#print h2, #print h3, #print h4 {
	text-transform: uppercase;
	text-align: center;
	font-weight: bold
}

#print ul {
	padding: 0;
	margin-bottom: 30px
}
</style>
<div id="print">
	<div class="text-center m-bottom">
		<h1>{FORM_INFO.title}</h1>
		<span class="hidden-print"><em class="fa fa-print">&nbsp;</em><a href="#" title="{LANG.report_print}" onclick="window.print(); return false;">{LANG.report_print}</a></span>
	</div>
	{FORM_DATA}
</div>
<!-- END: main -->