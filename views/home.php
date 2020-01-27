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
					<nav class="navbar navbar-expand-lg navbar-light bg-light">
						<button type="button" id="sidebarLeftCollapse" onclick="ClickCollapse()" class="navbar-btn">
							<span></span>
							<span></span>
							<span></span>
						</button>
						<div>
							<div class="ml-5">
								<button type="button" class="btn btn-outline-primary ml-1" style="width:50px;" onclick="loadViewTimeline()"><i class="fa fa-line-chart fa-lg"></i></button>
								<button type="button" class="btn btn-outline-info ml-1" style="width:50px;" onclick="loadViewWordCloud()"><i class="fa fa-cloud fa-lg"></i></button>
								<button type="button" class="btn btn-outline-success ml-1" style="width:50px;" onclick="loadViewStat()"><i class="fa fa-align-center fa-lg "></i></button>
							</div>

						</div>
					</nav>
				</div>



				<div class="row">
					<div id="mainViz" class="pl-2 pr-4" style='width:100%;height:100%'>
					</div>
				</div>

				<!-- <div id="visApp">
					<div class="row" style="height:500px;">
						<div id="pymaid" class="col pymaid-container" style="padding-right: 20px"></div>
					</div>
				</div>
				<div id="timenets-container" style=" width:100%; height:300px;text-align:center;">
					<script src="public/js/charts/timeNets.js"></script>
				</div> -->
			</div>


		</div>
		<!-- Main Content: End -->
	</div>
	</div>
</body>

</html>