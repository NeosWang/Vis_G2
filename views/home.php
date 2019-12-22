<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$title = 'Home';
include $rootPath . '/views/tpl/header.php';
?>

<body onload="Onload()">
	<!-- <body> -->
	<div class="wrapper">
		<!-- Input Panel : Start-->
		<?php include $rootPath . '/views/tpl/inputPanel.php'; ?>
		<!-- Input Panel : End-->

		<div id="content" style="padding:0">
			<!-- Output Panel : Start-->
			<div id="contentRight">
				<?php include $rootPath . '/views/tpl/outputPanel.php'; ?>
			</div>
			<!-- Output Panel : End-->

			<!-- Main Content: Start -->
			<div id="contentLeft">

				<div class="container-fluid">
					<button type="button" id="sidebarLeftCollapse" onclick="ClickCollapse()" class="navbar-btn">
						<span></span>
						<span></span>
						<span></span>
					</button>
				</div>

				<div id="visApp">
					<div class="row" style="height:400px;">
						<!-- <div id="main0" style=" width:100%;height:400px;text-align:center;"> -->
						<div id="chartWordCloud" class="col pl-3"></div>

						<div id="pymaid" class="col pymaid-container" style="padding-right: 20px"></div>
						<!-- </div> -->
					</div>


					<div id="timenets-container" style=" width:100%; height:300px;text-align:center;">
						<script src="public/js/timeNets.js"></script>
					</div>

					<div class="row" style="height:600px;">
						<div class="col-1 pl-5 pt-5"><button onclick=" SwitchTree(this)" value='0'>switch</button></div>
						<script>
							function SwitchTree(button) {
								if ($(button).val() == '0') {
									$(button).val('1');
									BulitHorizontalTree(treeData);
								} else {
									$(button).val('0');
									BulitRadialTree(treeData);
								}
							}
						</script>
						<div id="radialTree" class="col-11"></div>
					</div>



					<div class="row">
						<div id="chartBirth" class="timeline" style=" width:100%;height:200px;">
						</div>
					</div>

					<div class="row mb-5">
						<div id="chartMarriage" class="timeline" style=" width:100%;height:100px;">
						</div>
					</div>
					<script type="text/javascript">
						var chartBirth = echarts.init(document.getElementById('chartBirth'));
						var chartMarriage = echarts.init(document.getElementById('chartMarriage'));
						var radialTree = echarts.init(document.getElementById('radialTree'));
						var chartWordCloud;
						window.onresize = function() {
							chartBirth.resize();
							chartMarriage.resize();
							chartWordCloud.resize();
							radialTree.resize();
						}

						var option = {
							tooltip: {
								trigger: 'axis'
							},
							xAxis: {
								type: 'category',
								name: 'Year',
								boundaryGap: false,
								data: []
							},
							yAxis: {
								name: 'People',
								type: 'value',
								interval: 6000
							},
							grid: {
								left: 70,
								top: 30,
								right: 70,
								bottom: 20
							},
							legend: {
								data: [],
								align: 'left'
							},
							series: []
						};
						chartBirth.setOption(option);
						chartMarriage.setOption(option);
					</script>

				</div>
			</div>
			<!-- Main Content: End -->
		</div>
	</div>
</body>

</html>