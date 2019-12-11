function Onload() {
    LoadLastNameArr();
    ShowOverview('birth_s', chartBirth, '#E74C3C');
    ShowOverview('death', chartDeath, '#566573');
    ShowOverview('marriage', chartMarriage, '#BB8FCE');
    Pop_Pyramid();
}

var totalLastNameAsc = new Array();
var top10LastName = new Array();
var nameFreqDescJson;
var monthArr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

// input panel most frequent name and search function
function LoadLastNameArr() {
    $.ajax({
        url: 'backend/api.php',
        data: {
            func: 'GetFreqLastName',
            table: 'birth_s',
            orderby: 'desc'
        },
        dataType: "json",
        crossDomain: true,
        type: 'get',
        success: function (data) {
            let topLnamesforCloud = data.slice(0, 200);
            nameFreqDescJson = data;
            for (i = 0; i < data.length; i++) {
                if (i < 10) {
                    top10LastName.push(data[i]['name']);
                }
                totalLastNameAsc.push(data[i]['name']);
            }
            totalLastNameAsc.sort();
            
            chartWordCloud = echarts.init(document.getElementById('chartWordCloud'));
            LoadWordCloud(chartWordCloud,topLnamesforCloud);
            $('#popLname10').removeClass().addClass('fa fa-flip-horizontal fa-signal')
                .attr('onClick', 'ShowTop10LastName();')
                .attr('data-toggle', 'tooltip')
                .attr('data-placement', 'right')
                .attr('title', 'Popular Last Name');
            $('#inputLname').prop('disabled', false);
            $('#btnLname').prop('disabled', false).html('Search');
        }
    });
}






var totalPersonByLnameJson;

function btnLnameSearch() {
    initFilter();
    let inputName = $('#inputLname').val();
    if (totalLastNameAsc.includes(inputName)) {
        $('#divFilter').show();
        $.ajax({
            url: 'backend/api.php',
            data: {
                func: 'GetPersonByLastName',
                table: 'birth_s',
                lname: inputName,
            },
            dataType: "json",
            crossDomain: true,
            type: 'get',
            success: function (data) {
                totalPersonByLnameJson = data;
                ShowPersonByLnameJson();
            }
        });
    } else {
        $('#divFilter').hide();
        alert('Last Name Not correct');
    }
}


function ShowPersonByLnameJson() {
    let data = totalPersonByLnameJson;

    let fname = $('#inputFname').val().toLowerCase();
    let pname = $('#inputPname').val().toLowerCase();
    let male = $('#checkMale').val();
    let female = $('#checkFemale').val();

    data = $.grep(data, function (n, i) {
        return n.fname.toLowerCase().includes(fname) && n.pname.toLowerCase().includes(pname);
    });

    if (!male) {
        data = $.grep(data, function (n, i) {
            return n.gender != 1;
        });
    }
    if (!female) {
        data = $.grep(data, function (n, i) {
            return n.gender != 0;
        });
    }

    data.sort(function (a, b) {
        return a.fname.localeCompare(b.fname);
    });

    $('#personList').html('');

    for (var k in data) {
        let str = `${NameFormat(data[k].fname)}${NameFormat(data[k].pname)}${NameFormat(data[k].lname)}`;
        let title = `dob: ${data[k].year}-${monthArr[ data[k].month-1]}-${data[k].day}\nplace: ${data[k].place}\ngender: ${data[k].gender==0?'Female':(data[k].gender==1?'Male':'Unknown')}`;
        $('#personList').append(`<a href='#' class='pl-3' data-toggle='tooltip' data-placement='right' title='${title}' value='${data[k].id}' onclick='ChoosePerson(this)'><span class='personListRes'>${str}</span></a>`);
    }
}


// to be extended!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
function ChoosePerson(a) {
    console.log($(a).attr('value'));
}


function NameFormat($name) {
    return $name == '' ? $name : $name + ' ';
}

function initFilter() {
    $('#checkMale').val(1).removeClass('fa-square').addClass('fa-check-square');
    $('#checkFemale').val(1).removeClass('fa-square').addClass('fa-check-square');
    $('#inputFname').val('');
    $('#inputPname').val('');
}

function CheckGender(i) {
    if (i.value == 0) {
        i.classList.remove('fa-square');
        i.classList.add('fa-check-square');
        i.value = 1;
    } else {
        i.classList.remove('fa-check-square');
        i.classList.add('fa-square');
        i.value = 0;
    }
    ShowPersonByLnameJson()
}

/* ------------------------- Function on Most frequent lastname------------------------------*/
/*
 *@description      display elements from top10LastName
 *@trigger          onclick
 */
function ShowTop10LastName() {
    $('#searchLnameSubmenu').html('');
    $('#inputLname').val('');
    $('#popLname10').attr('check', 1);
    for (let name of top10LastName) {
        $('#searchLnameSubmenu').append("<a class='pl-5' href='#' onclick='SelectLastName(this.innerHTML)'>" + name + "</a>");
    }
}

/* -------------------------- Function on Input lastname-------------------------------------*/
/*
 *@description      check if elements of totalLastNameAsc match the value of input then display(most 10)
 *@trigger          onfocus oninput
 *@param            inputself
 */
function InputLnameSearch(input) {
    let searchLnameSubmenu = $('#searchLnameSubmenu');
    searchLnameSubmenu.html('');
    let inputName = input.value.toLowerCase();
    let length = inputName.length;
    if (length > 0) {
        let count = 0;
        for (i = 0; i < totalLastNameAsc.length; i++) {
            if (totalLastNameAsc[i].toLowerCase().substring(0, length) == inputName) {
                searchLnameSubmenu.append("<a class='pl-5' href='#' onclick='SelectLastName(this.innerHTML)'>" + totalLastNameAsc[i] + "</a>");
                count++;
            }
            if (count >= 10) {
                break;
            }
        }
    }
}
/*
 *@description      get first element of totalLastNameAsc matches the value of input while keypress 'enter'
 *@trigger          onkeypress
 *@param            event
 */
function InputLnameEnter(e) {
    if (e.keyCode == 13) {
        let inputName = $('#inputLname').val().toLowerCase();
        let length = inputName.length;
        if (length > 0) {
            for (let name of totalLastNameAsc) {
                if (name.toLowerCase().substring(0, length) == inputName) {
                    $('#inputLname').val(name);
                    $('#searchLnameSubmenu').html('');
                    break;
                }
            }
        }
    }
};

/* -------------------------- Function on mathced lastname -------------------------------------*/
/*
 *@description      get value of selected name from matched list to input, clear matched list
 *@trigger          onclick
 *@param            innerHtml
 */
function SelectLastName(html) {
    $('#inputLname').val(html);
    $('#searchLnameSubmenu').html('');
}








// resize images of main panel while click collapse
function ClickCollapse() {
    setTimeout(function () {
        chartBirth.resize();
        chartDeath.resize();
        chartMarriage.resize();
    }, 600);
}


function ConvertSingleQuote(str) {
    str = str.replace(/\'/g, '&#39;');
    return str;
}




// query table, load data to chart, 
function ShowOverview(table, chart, color) {
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
            let legend1 = {
                data: data.birthAndDeath.legend
            };
            let xAxis1 = {
                type: 'category',
                boundaryGap: true,
                data: data.birthAndDeath.xAxis
            };
            let series1 = {
                name: table,
                type: 'bar',
                data: data.birthAndDeath.series.data,
                itemStyle: {
                    color: color
                }
            };
            op.legend = legend1;
            op.xAxis = xAxis1;
            op.series = series1;
            chart.setOption(op);
        }
    });
}


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
                width: '100%',
            });
        }
    });
}

function LoadWordCloud(chartWordCloud,data) {
    let maskImage = new Image();
    maskImage.src= "data:image/png;base64,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";

    let op = {
        tooltip: {},
        series: [{
            type: 'wordCloud',
            gridSize: 1,
            sizeRange: [10, 30],
            rotationRange: [-90, 90],
            rotationStep: 45,
            maskImage: maskImage,
            width: '100%',
            height: '100%',
            // drawOutOfBound: true,
            textStyle: {
                normal: {
                    fontFamily: 'tahoma',
                    fontWeight: 'bold',
                    color: function () {
                        return 'rgba(' + [
                            255, 0, 0, RandomTransparency(0.25, 1)
                        ].join(',') + ')';
                    }
                },
                emphasis: {
                    shadowBlur: 10,
                    shadowColor: '#333'
                }
            },
            data: data
        }]
    };
    chartWordCloud.setOption(op);
}


function RandomTransparency(min, max) {
    return Math.round((Math.random() * (max - min) + min) * 100) / 100;
}



