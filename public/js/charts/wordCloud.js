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
    let op = chartWordCloud.getOption();
    op.series[0].maskImage=maskImage;
    chartWordCloud.clear();
    chartWordCloud.setOption(op);
}

const randomNumber = (min, max) => Math.floor(Math.random() * (max - min + 1) + min)
const randomByte = () => randomNumber(0, 255)

function SwitchRandomColor(){
    let r=randomByte();
    let g=randomByte();
    let b=randomByte();
    let op = chartWordCloud.getOption();
    op.series[0].textStyle.normal.color=function () {
        return 'rgba(' + [
            r, g, b, RandomTransparency(0.25, 1)
        ].join(',') + ')';
    }
    chartWordCloud.clear();
    chartWordCloud.setOption(op);
}


function LoadWordCloud(chartWordCloud, data, layout,r,g,b) {
    let maskImage = new Image();
    // maskImage.src = "data:image/png;base64,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";
    maskImage.src=GetLayout(layout);
    let op = {
        tooltip: {},
        series: [{
            type: 'wordCloud',
            gridSize: 2,
            sizeRange: [8, 35],
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
    chartWordCloud.setOption(op);
    chartWordCloud.on('click', function (param) {
        $('#inputLname').val(param.name);
    })
}

function RandomTransparency(min, max) {
    return Math.round((Math.random() * (max - min) + min) * 100) / 100;
}

