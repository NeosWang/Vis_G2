function BulitRadialTree(data) {
    radialTree.clear();
    radialTree.setOption(option = {
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
                        // if (param.data.gender == 0) {
                        //     return `{a|${param.data.fname} ${param.data.lname}}`;
                        // } else if (param.data.gender == 1) {
                        //     return `{b|${param.data.fname} ${param.data.lname}}`;
                        // }
                        return `${param.data.fname} ${param.data.lname}`;
                    },
                    // rich: {
                    //     a: {
                    //         color: 'red'
                    //     },
                    //     b: {
                    //         color: 'blue'
                    //     }
                    // }
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

            initialTreeDepth: 8,

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


function ChangeSymbol() {
    let op = radialTree.getOption();
    help(op.series[0].data[0]);
    // op.series[0].data[0].children.map((item) => {
    //         item.symbol=item.gender==0?'rect':item.gender==1?'triangle':'circle';
    //         if (item.children) {
    //             item.children.map(i => {
    //                 i.symbol=i.gender==0?'rect':i.gender==1?'triangle':'circle';
    //             })
    //         }
    //     }

    // )
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
    radialTree.clear();
    radialTree.setOption(op);
}

function BulitHorizontalTree(data) {
    radialTree.clear();
    radialTree.setOption(option = {
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
                        // if (param.data.gender == 0) {
                        //     return `{a|${param.data.fname} ${param.data.lname}}`;
                        // } else if (param.data.gender == 1) {
                        //     return `{b|${param.data.fname} ${param.data.lname}}`;
                        // }
                        return `${param.data.fname} ${param.data.lname}`;
                    },
                    // rich: {
                    //     a: {
                    //         color: 'red'
                    //     },
                    //     b: {
                    //         color: 'blue'
                    //     }
                    // }
                }
            },
            leaves: {
                label: {
                    normal: {
                        position: 'right',
                        verticalAlign: 'middle',
                        align: 'left'
                    }
                }
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