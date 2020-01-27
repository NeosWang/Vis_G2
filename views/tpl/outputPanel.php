<nav class="panel " id="sidebarRight" style="height:100%;">
    <div class="sidebar-header">
        <h3><i class="fa fa-address-card-o"></i> Personal Detail</h3>
    </div>
    <ul class="list-unstyled components">
        <!-- <li>
            <a href="#pageSubmenuRight" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">not decided yet</a>
            <ul class="collapse list-unstyled" id="pageSubmenuRight">
                <li>
                    <a href="#">no comment</a>
                </li>
            </ul>
        </li>
        <li>
            <a>so far, personal information could be retrieve at input panel by hover</a>
        </li> -->
        <div class="pl-3" id='outputPD'>
            <div class="py-1">
                <div><i class="fa fa-user" style="width:30px"></i>Name :</div>
                <div class="pl-5 outputText" id='outputName'></div>
            </div>

            <div class="py-1">
                <div><i class="fa fa-venus-mars" style="width:30px"></i>Gender :</div>
                <div class="pl-5 outputText" id='outputGender'></div>
            </div>
            <div class="py-1">
                <div><i class="fa fa-bookmark-o" style="width:30px"></i>Date of Birth :</div>
                <div class="pl-5 outputText" id='outputDOB'></div>
            </div>
            <div class="py-1">
                <div><i class="fa fa-bookmark" style="width:30px"></i>Date of Death :</div>
                <div class="pl-5 outputText" id='outputDOD'></div>
            </div>

            <div class="py-1">
                <div><i class="fa fa-map-marker" style="width:30px"></i>Region :</div>
                <div class="pl-5 outputText" id='outputPlace'></div>
            </div>
            <div class="py-1">
                <div><i class="fa class=fa fa-female" style="width:30px"></i>Mother :</div>
                <div class="pl-5 outputText" id='outputMother'></div>
            </div>

            <div class="py-1">
                <div><i class="fa class=fa fa-male" style="width:30px"></i>Father :</div>
                <div class="pl-5 outputText" id='outputFather'></div>
            </div>

        </div>
    </ul>
    <ul class="list-unstyled CTAs pl-5">
        <button class="btn btn-outline-light" onclick="loadViewTree()"><i class="fa fa-sitemap mr-1" ></i>Descendent Tree</button>
    </ul>
</nav>