<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$title = 'EX4B';
include $rootPath . '/views/tpl/header.php';
?>


<script>
    function shit() {
        $.ajax({
            url: 'backend/wk2_ex4b.php',
            dataType: "json",
            crossDomain: true,
            type: 'get',
            success: function(data) {
                var optionBirthDeath = chartBirthDeath.getOption();
                var optionPop = chartPop.getOption();

                var legend1 = {
                    data: data.birthAndDeath.legend
                };
                var legend2 = {
                    data: data.havingLived.legend
                };
                var xAxis1 = {
                    type: 'category',
                    boundaryGap: false,
                    data: data.birthAndDeath.xAxis
                };
                var xAxis2 = {
                    type: 'category',
                    boundaryGap: false,
                    data: data.havingLived.xAxis
                };

                var series1 = [{
                        name: 'Birth',
                        type: 'line',
                        data: data.birthAndDeath.series.Birth
                    },
                    {
                        name: 'Death',
                        type: 'line',
                        data: data.birthAndDeath.series.Death
                    }
                ];
                var series2 = [{
                    name: 'having lived',
                    type: 'line',
                    data: data.havingLived.series
                }]
                optionBirthDeath.legend = legend1;
                optionBirthDeath.xAxis = xAxis1;
                optionBirthDeath.series = series1;

                optionPop.legend = legend2;
                optionPop.xAxis = xAxis2;
                optionPop.series = series2;

                chartBirthDeath.setOption(optionBirthDeath);
                chartPop.setOption(optionPop);
                console.log(data);
            }
        });
    }
</script>


<body onload="shit()">

    <?php
    include $rootPath . '/views/tpl/naviBar.php'
    ?>

    <div class="container">
        <div class="row d-flex justify-content-center my-3">
            <div class="text-center">Being Born & Having Died per Year</div>
            <div id="main1" style="width: 100%;height:500px;"></div>
        </div>
        <div class="row d-flex justify-content-center my-3">
            <div class="text-center">Having Lived per Year</div>
            <div id="main2" style="width: 100%;height:500px;"></div>
            <div class="col"><a href="https://www.rug.nl/research/portal/files/15865622/articlesardinie21sep2014.pdf">https://www.rug.nl/research/portal/files/15865622/articlesardinie21sep2014.pdf</a><br>
                base on the research of University of Groningen, the estimate population at North Brabant in 1800 is 272,000
            </div>
        </div>

    </div>

    <script type="text/javascript">
        var chartBirthDeath = echarts.init(document.getElementById('main1'));
        var chartPop = echarts.init(document.getElementById('main2'));

        var option = {
            tooltip: {
                trigger: 'axis'
            },
            grid: {
                left: '5%',
                right: '5%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {
                        pixelRatio: 2
                    }
                }
            },
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
        chartPop.setOption(option);
        window.onresize = function() {
            chartBirthDeath.resize();
            chartPop.resize();
        }
    </script>
</body>

<?php include $rootPath . '/views/tpl/footer.php'; ?>