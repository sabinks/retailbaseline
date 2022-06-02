<div class="app-sidebar sidebar-shadow {{Auth::user()->companies[0]->theme}}">
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">App Menus</li>
                <li>
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-user-o"></i>
                        Staffs
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="/mystaffs">
                                <i class="metismenu-icon"></i>
                                My Field Staffs
                            </a>
                        </li>
                        <li>
                            <a href="/staff-attendance">
                                <i class="metismenu-icon"></i>
                                Staff Attendance
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/supervisor/entities-form">
                    <i class="metismenu-icon fa fa-shopping-bag"></i>
                    Entities Forms List</a>
                </li>
                <li>
                    <a href="/map-location">
                        <i class="metismenu-icon fa fa-map"></i>
                        Entity Location
                    </a>
                </li>
                <li>
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-user-o"></i>
                        Stock Register
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="/stock-register">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Stock Summary
                            </a> 
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/report-info/listing">
                        <i class="metismenu-icon fa fa-shopping-bag"></i>
                        Regular Report List
                    </a> 
                </li>
                <li>
                    <a href="/client/entity-form/assigned-list">
                        <i class="metismenu-icon fa fa-shopping-bag"></i>
                        Entity View Access
                    </a> 
                </li>
                <li>
                    <a href="/client/report-form/assigned-list">
                        <i class="metismenu-icon fa fa-shopping-bag"></i>
                        Report View Access
                    </a> 
                </li>
                <!-- <li>
                    <a href="/report-form-assigned">
                        <i class="metismenu-icon fa fa-map"></i>
                        Report Form List
                    </a>
                </li> -->
               
            </ul>
        </div>
    </div>
</div>