
function Pop_Pyramid() {
    $.ajax({
        url: 'backend/population1850.php',
        data: {},
        dataType: "json",
        crossDomain: true,
        type: 'get',
        success: function (data) {
            let chartConfig = {
                type: 'pop-pyramid',
                globals: {
                    fontSize: '14px'
                },
                title: {
                    text: 'Population distribution on 1850',
                    fontSize: '15px'
                },
                options: {
                    aspect: 'hbar'
                },
                legend: {
                    shared: true
                },
                plot: {
                    tooltip: {
                        padding: '10px 15px',
                        borderRadius: '3px',
                        text: "%t in age %kt\n %vt people"
                    },
                    valueBox: {
                        color: '#fff',
                        placement: 'top-in',
                        thousandsSeparator: ','
                    }
                },
                scaleX: {
                    labels: data.labels,
                },
                scaleY: {
                    label: {
                        text: 'Population'
                    }
                },
                series: [{
                        text: 'Male',
                        values: data.male,
                        backgroundColor: '#4682b4',
                        dataSide: 1
                    },
                    {
                        text: 'Female',
                        values: data.female,
                        backgroundColor: '#ee7988',
                        dataSide: 2
                    }
                ]
            };
            // render chart
            zingchart.render({
                id: 'pymaid',
                data: chartConfig,
                height: '100%',
                width: '90%',
            });
        }
    });
}