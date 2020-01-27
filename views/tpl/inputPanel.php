<nav class="panel" id="sidebarLeft">
    <div class="sidebar-header">
        <h3><i class="fa fa-pencil-square-o" ></i> Input</h3>
    </div>
    <ul class="list-unstyled components">
        <li>
            <a href="#" class="pl-3" draggable="false">
                <span class="ml-1">
                    <input type="text" name="inputLname" class="searchInput" id="inputLname" value="" placeholder="Last Name" disabled onkeypress="InputLnameEnter(event)" onfocus="InputLnameSearch(this)" oninput="InputLnameSearch(this)">
                </span>
                <i id="popLname10" class="fa fa-spinner fa-spin"></i>
            </a>
            <ul class="list-unstyled" id="searchLnameSubmenu">
                <!-- load top 10 name -->
            </ul>
        </li>
        <li>
            <div class='mr-3 my-1' align='right'>
                <button type="button" id='btnLname' class="btn btn-sm btn-outline-light" style="margin-left:auto; margin-right:0;" onclick="btnLnameSearch()" disabled>
                    <i class="fa fa-spinner fa-spin"></i>
                </button>
            </div>
        </li>
        <div id='divFilter' style="display: none;">
            <li>


                <a href='#' class='disable pl-3' draggable="false">
                    <span class="ml-1">
                        Person List
                    </span>
                    <i class="fa fa-sliders mr-2 mt-1 float-right dropdown-toggle " href="#inputFilter" data-toggle="collapse" aria-expanded="false"></i>
                </a>
                <ul class="collapse list-unstyled" id="inputFilter">
                    <li>
                        <a href="#" class=" pl-3" draggable="false">
                            <span class="ml-1">
                                <input type="text" name="inputFname" class="searchInput" id="inputFname" value="" placeholder="First Name" oninput="ShowPersonByLnameJson()">
                            </span>
                        </a>
                        <a href="#" class=" pl-3" draggable="false">
                            <span class="ml-1"><input type="text" name="inputPname" class="searchInput" id="inputPname" value="" placeholder="Prefix Name" oninput="ShowPersonByLnameJson()">
                            </span>
                        </a>
                        <a href="#" class=" pl-3" draggable="false">

                            <span class="col">
                                <i class="fa fa-check-square ml-1" id='checkMale' style="cursor: pointer" value=1 onclick="CheckGender(this)"></i>
                                <i class="fa fa-mars ml-1"></i>
                            </span>
                            <span class='col'>
                                <i class="fa fa-check-square ml-1" id='checkFemale' style="cursor: pointer" value=1 onclick="CheckGender(this)"></i>
                                <i class="fa fa-venus ml-1"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <div class='overflow-auto' id='personList'>
                </div>
            </li>

        </div>

    </ul>
</nav>