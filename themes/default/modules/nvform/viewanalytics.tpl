<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/{MODULE_FILE}_Chart.min.js"></script>

<!-- BEGIN: loop -->
<label>{QUESTION.title}</label>

<!-- BEGIN: radio -->
<div class="row">
	<div class="col-xs-12">
		<canvas id="pieChart_{QUESTION.qid}">&nbsp;</canvas>
	</div>
	<div class="col-xs-12">
		<div id="pieChart_des_{QUESTION.qid}">&nbsp;</div>
	</div>
</div>

<script>
	var pieData = {QUESTION.data};
	var ctx = document.getElementById("pieChart_{QUESTION.qid}").getContext("2d");
	window.myPie = new Chart(ctx).Pie(pieData,{
		 legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<pieData.length; i++){%><li><span style=\"background-color:<%=pieData[i].color%>; width: 23px; height: 11px; display: inline-block; margin-right: 5px	\"></span><%if(pieData[i].label){%><%=pieData[i].label%> (<%=pieData[i].value%>)<%}%></li><%}%></ul>"
	});

	var legend = myPie.generateLegend();
	$('#pieChart_des_{QUESTION.qid}').append(legend);

</script>
<!-- END: radio -->

<!-- END: loop -->

<!-- END: main -->