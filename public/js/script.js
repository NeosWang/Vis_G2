document.write(`<script src='public/js/charts/wordCloud.js' type='text/javascript'></script>
                <script src='public/js/charts/timeline.js' type='text/javascript'></script>
                <script src='public/js/charts/tree.js' type='text/javascript'></script>
                <script src='public/js/charts/calendar.js' type='text/javascript'></script>
                <script src='public/js/charts/popPyramid.js' type='text/javascript'></script>
               `)

function Onload() {
    GetOverViewData('birth_s');
    GetOverViewData('death');
    GetOverViewData('marriage_s');

    loadViewTimeline();


    LoadLastNameArr();
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
            // let topLnamesforCloud = data.slice(0, 200);
            nameFreqDescJson = data;
            for (i = 0; i < data.length; i++) {
                if (i < 10) {
                    top10LastName.push(data[i]['name']);
                }
                totalLastNameAsc.push(data[i]['name']);
            }
            totalLastNameAsc.sort();
            if($('#idWordCloudChart').length){
                loadViewWordCloud()
            }
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



function ChoosePerson(a) {
    var rid=$(a).attr('value');
    $.ajax({
        url: 'backend/api.php',
        data: {
            func: 'GetFullPersonalDetail',
            rid: rid
        },
        dataType: "json",
        crossDomain: true,
        type: 'get',
        success: function (data) {   
            $('#outputPD').attr('value',data['self']['rid']);
            $('#outputName').html(NameFormat(data['self']['fname']) + NameFormat(data['self']['pname'])+NameFormat(data['self']['lname'])  );
            $('#outputGender').html(data['self']['gender']==0?'female':'male');
            $('#outputDOB').html(data['self']['dob']);
            $('#outputDOD').html(data['self']['dod']==null?'no data':data['self']['dod']);
            $('#outputPlace').html(data['self']['place']);
            $('#outputFather').html(NameFormat(data['parent']['father']['fname']) + NameFormat(data['parent']['father']['pname'])+NameFormat(data['parent']['father']['lname']));
            $('#outputMother').html(NameFormat(data['parent']['mother']['fname']) + NameFormat(data['parent']['mother']['pname'])+NameFormat(data['parent']['mother']['lname']));

        }
    });
    

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
    setTimeout(() => {
        chartArr.forEach(v => {
            v.resize()
        });
    }, 600);
}

window.onresize = function() {
    setTimeout(() => {
        chartArr.forEach(v => {
            v.resize()
        });
    }, 600);
}

var chartArr=[];

function loadViewTimeline(){
    chartArr.splice(0, chartArr.length);
    $("#mainViz").html("");

    ovCalendarChart=CreateCalendar('idCalendar', 'calendar', '100%', '380px', 'mainViz')
    ShowCalendar('birth_s',1811,ovCalendarChart,'#E74C3C');
    chartArr.push(ovCalendarChart);
    ovBirthDeathChart=CreateTimeline('idTimelineBirthAndDeath', 'timeline', '100%', '220px', 'mainViz')
    chartArr.push(ovBirthDeathChart);
    ovMarryChart=CreateTimeline('idTimelineMarriage', 'timeline', '100%', '220px', 'mainViz')
    chartArr.push(ovMarryChart);
    ShowOverview(ovBirth,ovBirthDeathChart,'#E74C3C','line');
    ShowOverview(ovDeath, ovBirthDeathChart, '#566573', 'line');
    ShowOverview(ovMarry, ovMarryChart, '#BB8FCE', 'bar');
}

function loadViewWordCloud(){
    chartArr.splice(0, chartArr.length);
    $("#mainViz").html("");

    [ovWordCloudChart,ovWordBarChart] = CreateWordCloud('idWordCloudChart','idWordBarChart','mainViz');
    ShowWordCloud(ovWordCloudChart,nameFreqDescJson,1,255,0,0);
    ShowWordBar(ovWordBarChart,nameFreqDescJson);
    chartArr.push(ovWordCloudChart);
    chartArr.push(ovWordBarChart);
}

function loadViewTree(){
    let rid=$('#outputPD').attr('value');
    if(rid!=null){
        chartArr.splice(0, chartArr.length);
        $("#mainViz").html("");
        
        ovTreeChart=CreateTree('idTreeChart','mainViz');
        chartArr.push(ovTreeChart);
        ShowTree(rid,ovTreeChart);
    }else{
        alert('Search a person through Input panel')
    }
}

function SwitchTree(button) {
    if ($(button).val() == '0') {
        $(button).val('1');
        BulitHorizontalTree(treeData,ovTreeChart);
    } else {
        $(button).val('0');
        BulitRadialTree(treeData,ovTreeChart);
    }
}

function loadViewStat(){
    chartArr.splice(0, chartArr.length);
    $("#mainViz").html(`
        <div id="visApp">
            <div class="row ml-5" style="height:500px;">
                <div id="pymaid" class="col pymaid-container" style="padding-right: 20px"></div>
            </div>
        </div>
        <div id="timenets-container" style=" width:100%; height:300px;text-align:center;">
            <script src="public/js/charts/timeNets.js"></script>
        </div> 
    `);
    ShowPopulationPyramid(pyramidData);
}
