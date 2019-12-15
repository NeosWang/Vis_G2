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
					<div class="row">
						<div id="main0" style=" width:100%;height:400px;text-align:center;">
							<div id="chartWordCloud" class="float-left pl-3" style="width: 50%; height:100%"></div>

							<div id="pymaid" class="pymaid-container pr-4 float-right"></div>
						</div>
					</div>


					<div id="chart-container" style=" width:100%; height:300px;text-align:center;"></div>
					<script>
						// TimeNets by D3
						var chartDiv = document.getElementById("chart-container");
						var width = chartDiv.clientWidth;
						var height = 300;
						var padding = 20;


						var div = d3.select("#chart-container").append('div')
							.attr('class', 'd3-tip')
							.style('opacity', 0);

						var svg = d3.select("#chart-container")
							.append("svg")
							.attr("preserveAspectRatio", "xMidYMid meet")
							.attr("viewBox", `0 0 ${width} ${height}`)
							.style("max-height", "300px");

						var xScale = d3.scaleTime()
							.domain([new Date("1800-01-01"), new Date("1930-01-01")])
							.range([padding, width - padding * 2]);
						var yScale = d3.scaleLinear()
							.domain([0, 100])
							.range([height - padding * 2, padding]);

						var xAxis = d3.axisBottom(xScale)
							.tickFormat(function(date) {
								if (d3.timeYear(date) < date) {
									return d3.timeFormat('%b')(date);
								} else {
									return d3.timeFormat('%Y')(date);
								}
							});

						svg.append('g')
							.attr('class', 'axis')
							.attr("transform", "translate(0," + (height - padding) + ")")
							.call(xAxis);


						var startPoint;

						var lineMale = d3.line()
							.curve(d3.curveStepAfter)
							.x(function(d) {
								return xScale(new Date(d.time));
							})
							.y(function(d) {
								if (d.event == 'birth') {
									startPoint = 100 / (datasetM.length + 1) * (datasetM.length + 1 - d.family) + 4;
									if (d.from) {
										svg.append('line')
											.attr('x1', xScale(new Date(d.time)))
											.attr('y1', yScale(startPoint))
											.attr('x2', xScale(new Date(d.time)))
											.attr('y2', yScale(100 / (datasetM.length + 1) * (datasetM.length + 1 - d.from)))
											.attr('stroke', '#aed6f1')
											.attr('stroke-width', '1px');
									}
								} else if (d.event == 'marriage') {
									startPoint -= 3;
								}
								return yScale(startPoint);
							});

						var lineFemale = d3.line()
							.curve(d3.curveStepAfter)
							.x(function(d) {
								return xScale(new Date(d.time));
							})
							.y(function(d) {
								if (d.event == 'birth') {
									startPoint = 100 / (datasetM.length + 1) * (datasetM.length + 1 - d.family) - 4;
									if (d.from) {
										svg.append('line')
											.attr('x1', xScale(new Date(d.time)))
											.attr('y1', yScale(startPoint))
											.attr('x2', xScale(new Date(d.time)))
											.attr('y2', yScale(100 / (datasetM.length + 1) * (datasetM.length + 1 - d.from)))
											.attr('stroke', '#fadbd8')
											.attr('stroke-width', '1px');
									}
								} else if (d.event == 'marriage') {
									startPoint += 3;
								}
								return yScale(startPoint);
							});

						svg.selectAll("lifeLine")
							.data(datasetM)
							.enter()
							.append("path")
							.attr("d", lineMale)
							.attr("stroke", "#2874a6")
							.attr("stroke-width", 2)
							.attr("fill", "none")
							.on('mouseover', function(d) {
								div.transition()
									.duration(200)
									.style('opacity', .9);
								div.html(`<span id='tip' style='color: #2874a6'>${d[0].name}<br>
                        birth: ${d[0].time}<br>
                        death: ${d[2].time}<br>
                        region: ${d[0].place}</span>`)
									.style('left', (d3.event.pageX - 60) + 'px')
									.style('top', (d3.event.pageY - 50) + 'px')
							});


						svg.selectAll("lifeLine")
							.data(datasetF)
							.enter()
							.append("path")
							.attr("d", lineFemale)
							.attr("stroke", "#ec7063")
							.attr("stroke-width", 2)
							.attr("fill", "none")
							.on('mouseover', function(d) {
								div.transition()
									.duration(200)
									.style('opacity', .9);
								div.html(`<span id='tip' style='color: #ec7063'>${d[0].name}<br>
                        birth: ${d[0].time}<br>
                        death: ${d[2].time}<br>
                        region: ${d[0].place}</span>`)
									.style('left', (d3.event.pageX - 60) + 'px')
									.style('top', (d3.event.pageY - 50) + 'px')
							});
					</script>



					<div class="row">
						<div id="chartBirth" class="timeline" style=" width:100%;height:200px;">
						</div>
					</div>
					<!-- <div class="row">

						<div id="chartDeath" class="timeline" style=" width:100%;height:100px;">

						</div>
					</div> -->
					<div class="row">
						<div id="chartMarriage" class="timeline" style=" width:100%;height:100px;">
						</div>
					</div>
					<script type="text/javascript">
						var chartBirth = echarts.init(document.getElementById('chartBirth'));
						// var chartDeath = echarts.init(document.getElementById('chartDeath'));
						var chartMarriage = echarts.init(document.getElementById('chartMarriage'));
						var chartWordCloud;
						// var chartWordCloud = echarts.init(document.getElementById('chartWordCloud'));
						window.onresize = function() {
							chartBirth.resize();
							// chartDeath.resize();
							chartMarriage.resize();
							chartWordCloud.resize();
						}

						var option = {
							tooltip: {
								trigger: 'axis'
							},
							xAxis: {
								type: 'category',
								name: 'Year',
								boundaryGap: false,
								data:[]
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
							series:[]
						};
						chartBirth.setOption(option);
						// chartDeath.setOption(option);
						chartMarriage.setOption(option);
					</script>

				</div>
			</div>
			<!-- Main Content: End -->
		</div>
	</div>
</body>

</html>