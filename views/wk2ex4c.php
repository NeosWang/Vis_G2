<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$title = 'EX4C';
include $rootPath . '/views/tpl/header.php';
?>
<script>
    function shit() {
        $.ajax({
            url: 'backend/wk2_ex4c.php',
            dataType: "json",
            crossDomain: true,
            type: 'get',
            success: function(data) {
                var optionBirth = chartBirth.getOption();
                var optionDeath = chartDeath.getOption();
                var optionPop = chartPop.getOption();

                var legendBirth = {
                    data: data.birth.legend
                };
                var xAxisBirth = {
                    type: 'category',
                    boundaryGap: false,
                    data: data.birth.xAxis
                };
                var seriesBirth = [{
                        name: 'female',
                        type: 'bar',
                        data: data.birth.series.female,
                        itemStyle: {
                            normal: {
                                color: '#F39C12'
                            }
                        }
                    },
                    {
                        name: 'male',
                        type: 'bar',
                        data: data.birth.series.male,
                        itemStyle: {
                            normal: {
                                color: '#3498DB'
                            }
                        }
                    }
                ]
                optionBirth.legend = legendBirth;
                optionBirth.xAxis = xAxisBirth;
                optionBirth.series = seriesBirth;
                chartBirth.setOption(optionBirth);


                var legendDeath = {
                    data: data.death.legend
                };
                var xAxisDeath = {
                    type: 'category',
                    boundaryGap: false,
                    data: data.death.xAxis
                };
                var seriesDeath = [{
                        name: 'female',
                        type: 'bar',
                        data: data.death.series.female,
                        itemStyle: {
                            normal: {
                                color: '#F39C12'
                            }
                        }
                    },
                    {
                        name: 'male',
                        type: 'bar',
                        data: data.death.series.male,
                        itemStyle: {
                            normal: {
                                color: '#3498DB'
                            }
                        }
                    }
                ]
                optionDeath.legend = legendDeath;
                optionDeath.xAxis = xAxisDeath;
                optionDeath.series = seriesDeath;
                chartDeath.setOption(optionDeath);

                var legendPop = {
                    data: data.havingLived.legend
                };
                var xAxisPop = {
                    type: 'category',
                    boundaryGap: false,
                    data: data.havingLived.xAxis
                };
                var seriesPop = [{
                        name: 'female',
                        type: 'bar',
                        data: data.havingLived.series.female,
                        itemStyle: {
                            normal: {
                                color: '#F39C12'
                            }
                        }
                    },
                    {
                        name: 'male',
                        type: 'bar',
                        data: data.havingLived.series.male,
                        itemStyle: {
                            normal: {
                                color: '#3498DB'
                            }
                        }
                    }
                ]
                optionPop.legend = legendPop;
                optionPop.xAxis = xAxisPop;
                optionPop.series = seriesPop;
                chartPop.setOption(optionPop);

                console.log(data);
            }
        });
    }
</script>
</head>

<body onload="shit()">

    <?php
    include $rootPath . '/views/tpl/naviBar.php'
    ?>


    <div class="container">
        <div class="row">
            <div class="col">
                <div class="text-center">Being Born by gender</div>
                <div id="mainBirth" style="width: 100%;height:500px;"></div>
            </div>

            <div class="col">
                <div class="text-center">Having Died by gender</div>
                <div id="mainDeath" style="width: 100%;height:500px;"></div>
            </div>
        </div>
        <div class="row d-flex justify-content-center my-3">
            <div class="text-center">Having Lived per Year</div>
            <div id="mainPop" style="width: 100%;height:500px;"></div>
        </div>
    </div>

    <script type="text/javascript">
        var chartBirth = echarts.init(document.getElementById('mainBirth'));
        var chartDeath = echarts.init(document.getElementById('mainDeath'));
        var chartPop = echarts.init(document.getElementById('mainPop'));
        option = {
            grid: {
                left: '8%',
                right: '8%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {
                        pixelRatio: 2
                    }
                }
            },
            tooltip: {},
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
        chartBirth.setOption(option);
        chartDeath.setOption(option);
        chartPop.setOption(option);

        window.onresize = function() {
            chartBirth.resize();
            chartDeath.resize();
            chartPop.resize();
        }
    </script>
</body>

<?php include $rootPath . '/views/tpl/footer.php'; ?>