// query table, load data to chart, 
function ShowOverview(table, chart, color, type) {
    $.ajax({
        url: 'backend/api.php',
        data: {
            func: 'GetOverview',
            table: table
        },
        dataType: "json",
        crossDomain: true,
        type: 'get',
        success: function (data) {
            let op = chart.getOption();
            let legend = data.legend.split('_')[0];
            op.legend[0].data.push(legend);
            op.series.push({
                name: legend,
                type: type,
                data: data.series.data,
                itemStyle: {
                    color:color
                },
                showSymbol: false
            });
            let dataZoom = [{
                    show: true,
                    start: 0,
                    end: 40
                },
                {
                    show: true,
                    yAxisIndex: 0,
                    filterMode: 'empty',
                    width: 30,
                    height: '75%',
                    left: '95%'
                }
            ]
            op.dataZoom=dataZoom;
            op.xAxis[0].data = arrayUnique(op.xAxis[0].data.concat(data.xAxis));
            chart.setOption(op);
        }
    });
}

function arrayUnique(array) {
    for (var i = 0; i < array.length; ++i) {
        for (var j = i + 1; j < array.length; ++j) {
            if (array[i] === array[j])
                array.splice(j--, 1);
        }
    }
    return array;
}