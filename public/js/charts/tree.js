var ovTreeChart;
var treeData;

function ShowTree(rid,chart) {
    chart.showLoading();
    $.ajax({
        url: 'backend/api.php',
        data: {
            func: 'test',
            table: 'birth_s',
            rid: rid
        },
        dataType: "json",
        crossDomain: true,
        type: 'get',
        success: function (data) {
            chart.hideLoading();
            treeData = data;
            BulitRadialTree(treeData,chart);
        }
    });
}

function BulitRadialTree(data,chart) {
    chart.clear();
    chart.setOption(option = {
        tooltip: {
            trigger: 'item',
            triggerOn: 'mousemove'
        },
        series: [{
            type: 'tree',
            top: '20%',
            bottom: '20%',
            right: '20%',
            data: [data],
            layout: 'radial',
            label: {
                normal: {
                    fontSize: 9,
                    formatter: function (param) {
                        return `${param.data.fname} ${param.data.lname}`;
                    }
                }
            },
            emphasis: {
                itemStyle: {
                    borderColor:'green'
                },
                lineStyle:{
                    color:'green'
                }
            },
            tooltip: {
                trigger: 'item',
                triggerOn: 'mousemove',
                formatter: function (param) {
                    return `${param.data.fname} ${param.data.pname} ${param.data.lname}<br>
                            gender: ${param.data.gender==0?'female':param.data.gender==1?'male':'unknown'}<br>
                            place: ${param.data.place}<br>
                            year: ${param.data.year}`;
                }
            },
            symbol: 'emptyCircle',
            symbolSize: 7,

            initialTreeDepth: 2,

            animationDurationUpdate: 750

        }]
    });
}

function help(item) {
    if (item.children) {
        item.children.map(i => {
            i.symbol = i.gender == 0 ? 'rect' : i.gender == 1 ? 'triangle' : 'circle';
            help(i);
        })
    }
}


function ChangeSymbol(chart) {
    let op = chart.getOption();
    help(op.series[0].data[0]);
    op.series[0].symbolSize = 12;
    op.series[0].lineStyle.width = 3;
    op.series[0].label.formatter = function (param) {
        if (param.data.gender == 0) {
            return `{a|${param.data.fname} ${param.data.lname}}`;
        } else if (param.data.gender == 1) {
            return `{b|${param.data.fname} ${param.data.lname}}`;
        }
        return `${param.data.fname} ${param.data.lname}`;
    }
    op.series[0].label.rich = {
        a: {
            color: 'red'
        },
        b: {
            color: 'blue'
        }
    }
    chart.clear();
    chart.setOption(op);
}

function BulitHorizontalTree(data,chart) {
    chart.clear();
    chart.setOption(option = {
        tooltip: {
            trigger: 'item',
            triggerOn: 'mousemove'
        },
        series: [{
            type: 'tree',
            top: '20%',
            bottom: '20%',
            right: '20%',
            data: [data],
            label: {
                normal: {
                    position: 'left',
                    verticalAlign: 'middle',
                    align: 'right',
                    fontSize: 9,
                    formatter: function (param) {
                        return `${param.data.fname} ${param.data.lname}`;
                    }            
                }
            },
            leaves: {
                // label: {
                    label: {
                        position: 'right',
                        verticalAlign: 'middle',
                        align: 'left'
                    }
                // }
            },
            tooltip: {
                trigger: 'item',
                triggerOn: 'mousemove',
                formatter: function (param) {
                    return `${param.data.fname} ${param.data.pname} ${param.data.lname}<br>
                            gender: ${param.data.gender==0?'female':param.data.gender==1?'male':'unknown'}<br>
                            year: ${param.data.year}`;
                }
            },

            symbol: 'emptyCircle',
            symbolSize: 7,

            initialTreeDepth: 8,

            animationDurationUpdate: 750,
            emphasis: {
                itemStyle: {
                    borderColor:'green'
                },
                lineStyle:{
                    color:'green'
                }
            }

        }]
    });
}


function CreateTree(id, fatherId){
    var box = document.createElement("div");
    box.setAttribute('class','box');

    var scale=document.createElement("div");
    scale.setAttribute('class','scale');
    
    var item=document.createElement("div");
    item.setAttribute('class','item');
    item.setAttribute('id',id);

    scale.appendChild(item);
    box.appendChild(scale);
    box.innerHTML+=`
    <div class='pl-5'>
        <button onclick=" SwitchTree(this)" value='0'>switch</button>
        <button onclick=" ChangeSymbol(ovTreeChart)" value='0'>encode</button>
    </div>`;
    document.getElementById(fatherId).appendChild(box);
    var theChart=echarts.init(document.getElementById(id));
    return theChart;
}

