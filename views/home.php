<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$title = 'Home';
include $rootPath . '/views/tpl/header.php';
?>

<body onload="shit()">


    <script>
        var top10LastName = new Array();

        function shit() {
            $.ajax({
                url: 'backend/GetMostFreqLastName.php',
                dataType: "json",
                crossDomain: true,
                type: 'get',
                success: function(data) {
                    var top10Submenu = document.getElementById('top10Submenu')
                    let i, name, rank;

                    for (i = 0; i < 10; i++) {
                        name = Object.keys(data)[i];
                        top10LastName.push(name);
                        rank = i + 1;
                        top10Submenu.innerHTML += "<a class='pl-5' href='#'>" + rank + ". " + name + "<button class='btn btn-light btn-sm btncheck float-right top10lname' onclick='fuck(this)' value='0' name='" + i + "'><i class='fa fa-fw fa-minus'></i></button></a>";
                    }

                    $('#popLname10').removeClass().addClass('fa fa-signal fa-flip-horizontal');
                }
            });
        }

        function fuck(button) {
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

            alert(top10LastName[button.name] + " " + check);
        }
    </script>
    <div class="wrapper">
        <!-- Left Sidebar Holder -->
        <nav class="panel" id="sidebarLeft">
            <div class="sidebar-header">
                <h3>Input panel</h3>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="#top10Submenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i id="popLname10" class="fa fa-spinner"></i><span class="ml-2">Popular Last Name</span>
                    </a>
                    <ul class="collapse list-unstyled" id="top10Submenu">
                        <!-- load top 10 frequent name -->
                    </ul>
                </li>
                <li>
                    <a href="#">search bar to be follow</a>
                </li>
            </ul>
            <ul class="list-unstyled CTAs">
            </ul>
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarLeftCollapse" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="btn btn-outline-secondary d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-angle-double-down" aria-hidden="true"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="wk2ex4b">Ex-4-b</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="wk2ex4c">Ex-4-c</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div id="visApp">


            </div>
        </div>
        <!-- Right Sidebar Holder -->
        <nav class="panel" id="sidebarRight">
            <div class="sidebar-header">
                <h3>Output panel</h3>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="#pageSubmenuRight" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">some crap</a>
                    <ul class="collapse list-unstyled" id="pageSubmenuRight">
                        <li>
                            <a href="#">some crap</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a>more shit</a>
                </li>
            </ul>
            <ul class="list-unstyled CTAs">
            </ul>
        </nav>
    </div>
    </script>
</body>

</html>