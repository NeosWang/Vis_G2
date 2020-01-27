var ovBirth;
var ovDeath;
var ovMarry;

var ovBirthDeathChart;
var ovMarryChart;

function GetOverViewData(table) {
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
            switch (table) {
                case 'birth_s':
                    ovBirth = data;
                    if (ovBirthDeathChart != null) {
                        ShowOverview(ovBirth, ovBirthDeathChart, '#E74C3C', 'line');
                    }
                    break;
                case 'death':
                    ovDeath = data;
                    if (ovBirthDeathChart != null) {
                        ShowOverview(ovDeath, ovBirthDeathChart, '#566573', 'line');
                    }
                    break;
                case 'marriage_s':
                    ovMarry = data;
                    if (ovMarryChart != null) {
                        ShowOverview(ovMarry, ovMarryChart, '#BB8FCE', 'line');
                    }
                    break;
                default:
                    break;
            }
        }
    });
}


// query table, load data to chart, 
function ShowOverview(data, chart, color, type) {
    if (data != null && data != undefined) {
        let op = chart.getOption();
        let legend = data.legend.split('_')[0];
        op.legend[0].data.push(legend);
        op.series.push({
            name: legend,
            type: type,
            data: data.series.data,
            itemStyle: {
                color: color
            },
            showSymbol: false
        });
        let dataZoom = [{
                show: true,
                start: 25,
                end: 75
            },
            {
                show: true,
                yAxisIndex: 0,
                filterMode: 'empty',
                width: 25,
                height: '65%',
                left: '97%'
            }
        ]
        op.dataZoom = dataZoom;
        op.xAxis[0].data = arrayUnique(op.xAxis[0].data.concat(data.xAxis));
        chart.setOption(op);
       

    }

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



function CreateTimeline(id, c, w, h, fatherId) {
    var charDiv = document.createElement("div");
    charDiv.setAttribute('id', id);
    charDiv.setAttribute('class', c);
    charDiv.setAttribute('style', 'width:' + w);
    charDiv.setAttribute('style', 'height:' + h);

    document.getElementById(fatherId).appendChild(charDiv);
    var theChart = echarts.init(document.getElementById(id));
    var op = {
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
            left: '3%',
            top: 50,
            right: 120,
            // bottom: 20
            containLabel: true
        },
        legend: {
            data: [],
            align: 'left'
        },
        series: []
    };
    theChart.setOption(op);
    theChart.on('click',function(params){
        let dyear=params.name;
        let dtable=params.seriesName=='birth'?'birth_s':params.seriesName=='marriage'?'marriage_s':'death';
        let dcolor=params.color;
        ShowCalendar(dtable,dyear,ovCalendarChart,dcolor);
    });
    return theChart;
}