function Onload() {
    LoadLastNameArr();
    shit('birth', chartBirth, '#E74C3C');
    shit('death', chartDeath, '#566573');
    shit('marriage', chartMarriage, '#BB8FCE');
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
                    fontSize: '20px'
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
                        text:"%t in age %kt\n %vt people"
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
                width: '100%',
            });




        }
    });


 
}