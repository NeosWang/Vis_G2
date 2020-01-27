var ovWordCloudChart;
var ovWordBarChart;

function GetLayout(layout){
    if (layout == 1) {
        return "./public/images/map2.jpg";
    } else{
        return "./public/images/house.jpg";
    }
}

function SwitchLayout(layout){
    let maskImage = new Image();
    maskImage.src=GetLayout(layout);
    let op = ovWordCloudChart.getOption();
    op.series[0].maskImage=maskImage;
    ovWordCloudChart.clear();
    ovWordCloudChart.setOption(op);
}

const randomNumber = (min, max) => Math.floor(Math.random() * (max - min + 1) + min)
const randomByte = () => randomNumber(0, 255)

function SwitchRandomColor(){
    let r=randomByte();
    let g=randomByte();
    let b=randomByte();
    let op = ovWordCloudChart.getOption();
    op.series[0].textStyle.normal.color=function () {
        return 'rgba(' + [
            r, g, b, RandomTransparency(0.25, 1)
        ].join(',') + ')';
    }
    ovWordCloudChart.clear();
    ovWordCloudChart.setOption(op);
}

function RandomTransparency(min, max) {
    return Math.round((Math.random() * (max - min) + min) * 100) / 100;
}



function CreateWordCloud(idL, idR,fatherId){
    var box=document.createElement("div");
    box.setAttribute('class','box');

    var scaleL=document.createElement("div");
    scaleL.setAttribute('class','scale_l');
    
    var scaleR=document.createElement("div");
    scaleR.setAttribute('class','scale_r');

    var itemL=document.createElement("div");
    itemL.setAttribute('class','item');
    itemL.setAttribute('id',idL);

    scaleL.appendChild(itemL);

    var itemR=document.createElement("div");
    itemR.setAttribute('class','item');
    itemR.setAttribute('id',idR);

    scaleL.appendChild(itemL);
    scaleR.appendChild(itemR);

    box.appendChild(scaleL);
    box.appendChild(scaleR);
    box.innerHTML+=`
    <div class="ml-5">
        <button style="width:60px;height:40px" onclick="SwitchLayout(1)">
            <img src="./public/images/map.jpg" style="width:100%;height:100%">
        </button>
  
        <button style="width:60px;height:40px" onclick="SwitchLayout(2)">
            <img src="./public/images/house.jpg" style="width:100%;height:100%">
        </button>

        <button style="width:60px;height:40px" onclick="SwitchRandomColor()">
            <img src="./public/images/color.jpg" style="width:100%;height:100%">
        </button>
    </div>
    `;
    document.getElementById(fatherId).appendChild(box);
    var theChartL=echarts.init(document.getElementById(idL));
    theChartL.showLoading();
    var theChartR=echarts.init(document.getElementById(idR));
    theChartR.showLoading();
    return [theChartL,theChartR];
}

function ShowWordCloud(chart, data, layout,r,g,b) {
    if(chart!=null && data!=null){
        chart.hideLoading();
        let maskImage = new Image();
        maskImage.src=GetLayout(layout);
        let op = {
            tooltip: {},
            series: [{
                type: 'wordCloud',
                gridSize: 2,
                sizeRange: [10, 50],
                rotationRange: [-45, 45],
                rotationStep: 90,
                maskImage: maskImage,
                width: '100%',
                height: '100%',
                textStyle: {
                    normal: {
                        fontFamily: 'tahoma',
                        fontWeight: 'bold',
                        color: function () {
                            return 'rgba(' + [
                                r, g, b, RandomTransparency(0.25, 1)
                            ].join(',') + ')';
                        }
                    },
                    emphasis: {
                        shadowBlur: 10,
                        shadowColor: '#f00',
                        color: '#F0F8FF'
                    }
                },
                data: data
            }]
        };
        chart.setOption(op);
        chart.on('click', function (param) {
            $('#inputLname').val(param.name);
        })
    } 
}

function ShowWordBar(chart,data){
    let data_p=data.slice(0, 100);
    var names=[];
    var values=[];
    for( let i= data_p.length-1 ; i>=0 ;i-- ) {
        names.push((i+1)+'. '+data_p[i].name);
        values.push(data_p[i].value);
    }
    chart.hideLoading();
    let op = {
        title: {
            text: 'Top 100 Ranking',
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        grid: {
            left: '3%',
            right: '10%',
            bottom: '3%',
            containLabel: true
        },
        xAxis: {
            type: 'value'
        },
        yAxis: {
            type: 'category',
            data: names
        },
        dataZoom: [
            {
                show: true,
                start:81,
                end:100,
                yAxisIndex: 0,
                filterMode: 'empty',
                width: 30,
                height: '90%',
                showDataShadow: false,
                left: "90%"
            }
        ],
        series: [
            {
                name: 'amount',
                type: 'bar',
                data: values

            }
        ]
    };
    chart.setOption(op);
    chart.on('click', function (param) {
        $('#inputLname').val(param.name.split('. ')[1]);
    })
}