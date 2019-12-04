<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$title = 'Home';
include $rootPath . '/views/tpl/header.php';
?>

<style>html, body {
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
}

.chart--container {
  min-height: 150px;
  width: 100%;
  height: 100%;
}

.zc-ref {
  display: none;
}
zing-grid[loading]{height:450px;}</style>
<script>ZC.LICENSE=["569d52cefae586f634c54f86dc99e6a9", "b55b025e438fa8a98e32482b5f768ff5"];
</script>
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
                <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light"> -->
                <div class="container-fluid">

                    <button type="button" id="sidebarLeftCollapse" onclick="ClickCollapse()" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <!-- <button class="btn btn-outline-secondary d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="wk2ex4b">Ex-4-b</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="wk2ex4c">Ex-4-c</a>
                                </li>
                            </ul>
                        </div> -->
                </div>
                <!-- </nav> -->

                <div id="visApp">
                    <div class="row">
                        <div id="main0" style=" width:100%;height:400px;border-style: dashed;text-align:center">

                            <div id="myChart" class="chart--container">
                            </div>


                        </div>
                    </div>
                    <div class="row">
                        <div id="chartBirth" class="timeline" style=" width:100%;height:100px;">
                        </div>
                    </div>
                    <div class="row">

                        <div id="chartDeath" class="timeline" style=" width:100%;height:100px;">

                        </div>
                    </div>
                    <div class="row">
                        <div id="chartMarriage" class="timeline" style=" width:100%;height:100px;">
                        </div>
                    </div>
                    <script type="text/javascript">
                        var chartBirth = echarts.init(document.getElementById('chartBirth'));
                        var chartDeath = echarts.init(document.getElementById('chartDeath'));
                        var chartMarriage = echarts.init(document.getElementById('chartMarriage'));
                        window.onresize = function() {
                            chartBirth.resize();
                            chartDeath.resize();
                            chartMarriage.resize();
                        }
                        var option = {
                            tooltip: {
                                trigger: 'axis'
                            },
                            xAxis: {
                                type: 'category',
                                name: 'Year',
                                boundaryGap: false,
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
                            }
                        };











                        chartBirth.setOption(option);
                        chartDeath.setOption(option);
                        chartMarriage.setOption(option);
                    </script>
                </div>
            </div>
            <!-- Main Content: End -->
        </div>
    </div>
</body>

</html>