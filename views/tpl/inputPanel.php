<nav class="panel" id="sidebarLeft">
    <div class="sidebar-header">
        <h3>Input panel</h3>
    </div>
    <ul class="list-unstyled components">
        <li>
            <a href="#top10Submenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle pl-3">
                <i id="popLname10" class="fa fa-spinner"></i>
                <span class="ml-2">Popular Last Name</span>
            </a>
            <ul class="collapse list-unstyled" id="top10Submenu">
                <!-- load top 10 frequent name -->
            </ul>
        </li>
        <li>
            <a href="#" class=" pl-3" draggable="false">
                <i id="searchIcon" class="fa fa-spinner"></i>
                <span class="ml-2"><input type="text" name="inputLname" class="searchInput" id="inputLname" value="" oninput="testinput(this)" disabled></span>
            </a>
            <ul class="list-unstyled" id="searchSubmenu">
                <!-- <a class='pl-5' href='#'>adf</a>
                <a class='pl-5' href='#'>adf</a>
                <a class='pl-5' href='#'>adf</a>
                <a class='pl-5' href='#'>adf</a> -->
            </ul>
        </li>
    </ul>
    <ul class="list-unstyled CTAs">
    </ul>
</nav>