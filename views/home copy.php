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

				<!-- <div class="container-fluid">
					<button type="button" id="sidebarLeftCollapse" onclick="ClickCollapse()" class="navbar-btn">
						<span></span>
						<span></span>
						<span></span>
					</button>
				</div> -->




				<nav class="navbar navbar-expand-lg navbar-light bg-light">
					<button type="button" id="sidebarLeftCollapse" onclick="ClickCollapse()" class="navbar-btn">
						<span></span>
						<span></span>
						<span></span>
					</button>
					<div>
						<div class="collapse navbar-collapse" id="navbarNav">
							<button type="button" class="btn btn-outline-primary" onclick="loadViewTimeline()">111</button>
							<button type="button" class="btn btn-outline-secondary">Secondary</button>
							<button type="button" class="btn btn-outline-success">Success</button>
							<button type="button" class="btn btn-outline-danger">Danger</button>
							<button type="button" class="btn btn-outline-warning">Warning</button>
							<button type="button" class="btn btn-outline-info">Info</button>
							<button type="button" class="btn btn-outline-dark">Dark</button>
						</div>
					</div>
				</nav>

				<div id='mainViz'>
				</div>









				<div id="visApp">
					<div class="row" style="height:400px;">
						<!-- <div id="main0" style=" width:100%;height:400px;text-align:center;"> -->

						<div id="chartWordCloud" class="col pl-3"></div>


						<div id="pymaid" class="col pymaid-container" style="padding-right: 20px"></div>
						<!-- </div> -->
					</div>
					<div>
						<button style="width:60px;height:40px" onclick="SwitchLayout(1)">
							<img src="./public/images/map.jpg" style="width:100%;height:100%">
						</button>
						<button style="width:60px;height:40px" onclick="SwitchLayout(2)">
							<img src="./public/images/house.jpg" style="width:100%;height:100%">
						</button>
						<button style="width:60px;height:40px" onclick="SwitchRandomColor()">
							<img src="./public/images/color.jpg" style="width:100%;height:100%">
						</button>
					</div>


					<div id="timenets-container" style=" width:100%; height:300px;text-align:center;">
						<script src="public/js/charts/timeNets.js"></script>
					</div>

					<div class="row" style="height:1000px;">
						<div class="col-1 pl-5 pt-5">
							<button onclick=" SwitchTree(this)" value='0'>switch</button>
							<button onclick=" ChangeSymbol()" value='0'>encode</button>
						</div>
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
						<div id="chartBirth" class="timeline" style=" width:100%;height:350px;">
						</div>
					</div>

					<div class="row mb-5">
						<div id="chartMarriage" class="timeline" style=" width:100%;height:200px;">
						</div>
					</div>
					<script type="text/javascript">
						var chartBirth = echarts.init(document.getElementById('chartBirth'));
						var chartMarriage = echarts.init(document.getElementById('chartMarriage'));
						var radialTree = echarts.init(document.getElementById('radialTree'));
						var chartWordCloud = echarts.init(document.getElementById('chartWordCloud'));
						window.onresize = function() {
							chartBirth.resize();
							chartMarriage.resize();
							chartWordCloud.resize();
							radialTree.resize();
						}

						var option = {
							tooltip: {
								trigger: 'axis',
								axisPointer: {
									type: 'shadow',
									label: {
										show: true
									}
								}
							},
							toolbox: {
								show: true,
								feature: {
									saveAsImage: { //保存图片
										show: true
									},
									mark: {
										show: true
									},
									magicType: {
										show: true,
										type: ['line', 'bar']
									},
									restore: {
										show: true
									}
								},
								right: 30

							},
							// calculable: true,
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
								left: 50,
								top: 50,
								right: 140,
								// bottom: 20
								containLabel: true
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