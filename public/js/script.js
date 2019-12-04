function Onload() {
    // LoadLastNameArr();
    // shit('birth', chartBirth, '#E74C3C');
    // shit('death', chartDeath, '#566573');
    // shit('marriage', chartMarriage, '#BB8FCE');
    shit1()
}

var totalLastNameAsc = new Array();
var top10LastName = new Array();

// input panel most frequent name and search function
function LoadLastNameArr() {
    $.ajax({
        url: 'backend/api.php',
        data: {
            func: 'GetFreqLastName',
            table: 'birth',
            orderby: 'desc'
        },
        dataType: "json",
        crossDomain: true,
        type: 'get',
        success: function (data) {
            var top10Submenu = document.getElementById('top10Submenu')
            let i, name, rank;
            let keys = Object.keys(data)
            let length = keys.length;
            for (i = 0; i < length; i++) {
                name = keys[i];
                if (i < 10) {
                    top10LastName.push(name);
                    rank = i + 1;
                    top10Submenu.innerHTML += "<a class='pl-5' href='#'>" + rank + ". " + name +
                        "<button class='btn btn-light btn-sm btncheck float-right top10lname' onclick='testcheck(this)' value='0' name='" +
                        name + "'><i class='fa fa-fw fa-minus'></i></button></a>";
                }
                totalLastNameAsc.push(name);
            }
            totalLastNameAsc.sort();
            $('#popLname10').removeClass().addClass('fa fa-signal fa-flip-horizontal');
            $('#searchIcon').removeClass().addClass('fa fa-search');
            $('#inputLname').prop('disabled', false);
            console.log(top10LastName);
        }
    });
}



function testinput(input) {
    let searchSubmenu = document.getElementById('searchSubmenu')
    searchSubmenu.innerHTML = "";

    let inputName = input.value.toLowerCase();
    let length = inputName.length;
    if (length > 0) {
        let count = 0;
        for (i = 0; i < totalLastNameAsc.length; i++) {
            if (totalLastNameAsc[i].toLowerCase().substring(0, length) == inputName) {
                searchSubmenu.innerHTML += "<a class='pl-5' href='#'>" + totalLastNameAsc[i] +
                    "<button class='btn btn-light btn-sm btncheck float-right' onclick='testcheck(this)' value='0' name='" +
                    ConvertSingleQuote(totalLastNameAsc[i]) + "'><i class='fa fa-fw fa-minus'></i></button></a>";
                count++;
            }
            if (count >= 10) {
                break;
            }
        }
    }
}


function testcheck(button) {
    let check;
    if (button.value == 0) {
        button.innerHTML = "<i class='fa fa-fw fa-check'></i>";
        button.value = 1;
        check = "check";
    } else {
        button.value = 0
        button.innerHTML = "<i class='fa fa-fw fa-minus'></i>";
        check = "uncheck";
    }
    alert(button.name + " " + check);
}




// resize images of main panel while click collapse
function ClickCollapse() {
    setTimeout(function () {
        chartBirth.resize();
    }, 600);
}


function ConvertSingleQuote(str) {
    str = str.replace(/\'/g, '&#39;');
    return str;
}












function shit(table, chart, color) {
    $.ajax({
        url: 'backend/api.php',
        data: {
            func: 'fuck1',
            table: table
        },
        dataType: "json",
        crossDomain: true,
        type: 'get',
        success: function (data) {
            console.log(data);
            var optionBirth = chart.getOption();
            var legend1 = {
                data: data.birthAndDeath.legend
            };

            var xAxis1 = {
                type: 'category',
                boundaryGap: true,
                data: data.birthAndDeath.xAxis
            };


            var series1 = {
                name: table,
                type: 'bar',
                data: data.birthAndDeath.series.data,
                itemStyle: {
                    color: color
                }
            };

            optionBirth.legend = legend1;
            optionBirth.xAxis = xAxis1;
            optionBirth.series = series1;


            chart.setOption(optionBirth);


        }
    });
}


function shit1() {
    // Javascript code to execute after DOM content

    // full ZingChart schema can be found here:
    // https://www.zingchart.com/docs/api/json-configuration/
    let chartConfig = {
        type: 'pop-pyramid',
        globals: {
            fontSize: '14px'
        },
        title: {
            text: 'Population Pyramid by Age Group',
            fontSize: '24px'
        },
        options: {
            // values can be: 'bar', 'hbar', 'area', 'varea', 'line', 'vline'
            aspect: 'hbar'
        },
        legend: {
            shared: true
        },
        // plot represents general series, or plots, styling
        plot: {
            // hoverstate
            tooltip: {
                padding: '10px 15px',
                borderRadius: '3px'
            },
            valueBox: {
                color: '#fff',
                placement: 'top-in',
                thousandsSeparator: ','
            },
            // animation docs here:
            // https://www.zingchart.com/docs/tutorials/design-and-styling/chart-animation/#animation__effect
            animation: {
                //effect: 'ANIMATION_EXPAND_BOTTOM',
                //method: 'ANIMATION_STRONG_EASE_OUT',
                //sequence: 'ANIMATION_BY_NODE',
                //speed: 222
            }
        },
        scaleX: {
            // set scale label
            label: {
                text: 'Age Groups'
            },
            labels: ['0-4', '5-9', '10-14', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', '50-54', '55-59', '60-64', '65-69', '70-74', '75-79', '80-84', '85-89', '90-94', '95-99', '100+'],
        },
        scaleY: {
            // scale label with unicode character
            label: {
                text: 'Population'
            }
        },
        series: [{
                text: 'Male',
                values: [1656154, 1787564, 1981671, 2108575, 2403438, 2366003, 2301402, 2519874, 3360596, 3493473, 1785638, 1447162, 1005011, 1330870, 1130632, 1121208, 2403438, 3360596, 3493473, 1785638, 1447162],
                // two colors with a space makes a gradient
                backgroundColor: '#4682b4',
                dataSide: 1
            },
            {
                text: 'Female',
                values: [1656154, 1787564, 1981671, 2108575, 2403438, 2366003, 2301402, 2304444, 2426504, 2568938, 1785638, 1447162, 1005011, 1330870, 1130632, 1121208, 2108575, 2301402, 2304444, 2426504, 1568938],
                // two colors with a space makes a gradient
                backgroundColor: '#ee7988',
                dataSide: 2
            }
        ]
    };

    // render chart
    zingchart.render({
        id: 'myChart',
        data: chartConfig,
        height: '100%',
        width: '100%',
    });
}