var ovCalendarChart;

function CreateCalendar(id, c, w, h, fatherId) {
    var charDiv = document.createElement("div");
    charDiv.setAttribute('id', id);
    charDiv.setAttribute('class', c);
    charDiv.setAttribute('style', 'width:' + w);
    charDiv.setAttribute('style', 'height:' + h);

    document.getElementById(fatherId).appendChild(charDiv);
    var theChart=echarts.init(document.getElementById(id));
    theChart.showLoading();
    return theChart;
}

function ShowCalendar(table,year,chart,color){

    let t= table=='birth_s'?'birth':table=='marriage_s'?'marriage':table;
    chart.showLoading({ 
        text:'Data w.r.t '+t.charAt(0).toUpperCase() + t.substring(1)+' On Loading',
        color:color,
    })
    $.ajax({
        url: 'backend/api.php',
        data: {
            func: 'GetFreqPerDay',
            table: table,
            year: year
        },
        dataType: "json",
        crossDomain: true,
        type: 'get',
        success:function(data){
            chart.hideLoading();
            let ovCalendar=[];
            for(key in data){
                ovCalendar.push([key,data[key]])
            }
            let valueArr=Object.values(data);
            let min=Math.min(...valueArr);
            let max=Math.max(...valueArr);
            let op = {
                title: {
                    text: 'Heatmap in '+year+" on "+t,
                    left: 'center',
                    textStyle: {
                        color: '#808b96'
                    }
                },
                tooltip: {
                    position: 'top',
                    formatter: function (p) {
                        var format = echarts.format.formatTime('yyyy-MM-dd', p.data[0]);
                        return `${format}<br>
                                ${p.data[1]} people`;
                    }
                },
                visualMap: {
                    min: min,
                    max: max,
                    calculable: true,
                    left: 'center',
                    top: 35,
                    inRange: {
                        color: ['#eaecee', color]
                    },
                    orient: 'horizontal'
                },
            
                calendar: [{
                    top:100,
                    range: year,
                    cellSize: ['auto', 30],
                    yearLabel: {
                        formatter: '',
                        textStyle: {
                            color: '#fff'
                        }
                    }
                }],
            
                series: [{
                    type: 'heatmap',
                    coordinateSystem: 'calendar',
                    calendarIndex: 0,
                    data: ovCalendar
                }] 
            };
            chart.setOption(op);
        }
    });
}
