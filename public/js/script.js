function Onload() {
    LoadLastNameArr();
    shit();
}

var totalLastNameAsc = new Array();
var top10LastName = new Array();

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

function ClickCollapse() {
    setTimeout(function () {
        chartBirthDeath.resize();
    }, 600);
}



function ConvertSingleQuote(str) {
    str = str.replace(/\'/g, '&#39;');
    return str;
}












function shit() {
    $.ajax({
        url: 'backend/api.php',
        data: {
            func: 'fuck1'
        },
        dataType: "json",
        crossDomain: true,
        type: 'get',
        success: function(data) {
            var optionBirthDeath = chartBirthDeath.getOption();


            var legend1 = {
                data: data.birthAndDeath.legend
            };
           
            var xAxis1 = {
                type: 'category',
                boundaryGap: false,
                data: data.birthAndDeath.xAxis
            };
          

            var series1 = {
                    name: 'Birth',
                    type: 'bar',
                    data: data.birthAndDeath.series.Birth
                };
          
            // optionBirthDeath.legend = legend1;
            optionBirthDeath.xAxis = xAxis1;
            optionBirthDeath.series = series1;

        
            chartBirthDeath.setOption(optionBirthDeath);

            console.log(data);
        }
    });
}