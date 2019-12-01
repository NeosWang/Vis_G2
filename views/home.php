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
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">

                        <button type="button" id="sidebarLeftCollapse" onclick="ClickCollapse()" class="navbar-btn">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                        <button class="btn btn-outline-secondary d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                        </div>
                    </div>
                </nav>

                <div id="visApp">
                    <div class="row">
                        <div id="main1" style=" width:100%;height:200px;">
                        </div>
                    </div>
                    <script type="text/javascript">
                        var chartBirthDeath = echarts.init(document.getElementById('main1'));
                        window.onresize = function() {
                            chartBirthDeath.resize();
                        }
                        var option = {
                            tooltip: {
                                trigger: 'axis'
                            },
                            // grid: {
                            //     left: '5%',
                            //     right: '5%',
                            //     containLabel: true
                            // },
                            // toolbox: {
                            //     feature: {
                            //         saveAsImage: {
                            //             pixelRatio: 2
                            //         }
                            //     }
                            // },
                            xAxis: {
                                type: 'category',
                                name: 'Year',
                                boundaryGap: false,
                            },
                            yAxis: {
                                name: 'People',
                                type: 'value'
                            }
                        };











                        chartBirthDeath.setOption(option);
                    </script>
                </div>
            </div>
            <!-- Main Content: End -->
        </div>
    </div>
</body>

</html>