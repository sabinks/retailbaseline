<div class="app-sidebar sidebar-shadow {{Auth::user()->companies[0]->theme}}">
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <!-- <li class="app-sidebar__heading">Theme Settings</li>
                <li>
                    <a href="/themeSetting" class="mm-active">
                        <i class="metismenu-icon fa fa-tachometer"></i>
                        Theme Settings
                    </a>
                </li> -->
                <li class="app-sidebar__heading">App Menus</li>
                <li>
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-map"></i>
                        Regions
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="/myRegion">
                                <i class="metismenu-icon"></i>
                                Group Regions
                            </a>
                        </li>
                        <li>
                            <a href="/regions-list">
                                <i class="metismenu-icon"></i>
                                Regions
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-user-o"></i>
                            Employee
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="/regionalAdmins">
                                <i class="metismenu-icon"></i>
                                My Regional Admins
                            </a>
                        </li>
                        <li>
                            <a href="/supervisors">
                                <i class="metismenu-icon"></i>
                                My Supervisors
                            </a>
                        </li>
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
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-building-o"></i>
                            Entity Tracking
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul> 
                        <li>
                            <a href="/entities-form/" ><i class="metismenu-icon fa fa-shopping-bag"></i>
                                Entity Form List
                            </a>
                        </li>
                        <li>
                            <a href="/assign-entities-form/"><i class="metismenu-icon fa fa-group"></i>
                                Assign Form
                            </a>
                        </li>
                        <li>
                            <a href="/assign-entities-form/remove"><i class="metismenu-icon fa fa-group"></i>
                                Assigned Form
                            </a>
                        </li>
                        <li>
                            <a href="/entities-history"><i class="metismenu-icon fa fa-group"></i>
                                Entities List
                            </a>
                        </li>
                        <li>
                            <a href="{{route('group-entites.index')}}">
                                <i class="metismenu-icon fa fa-map"></i>
                                Entity Group
                            </a>
                        </li>
                        <li>
                            <a href="/map-location">
                                <i class="metismenu-icon fa fa-map-marker"></i>
                                Entity Location
                            </a>
                        </li>
                        <li>
                            <a href="/client/entity-form/assigned-list">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Entity View Access
                            </a> 
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-user-o"></i>
                        Stock Register
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul>
                        <!-- <li>
                            <a href="/stock/category-create">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Create Category
                            </a> 
                        </li> -->
                        <li>
                            <a href="/stock/category-list">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                List Category
                            </a> 
                        </li>
                        <!-- <li>
                            <a href="/stock/item-create">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Create Item
                            </a> 
                        </li> -->
                        <li>
                            <a href="/stock/item-list">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                List Item
                            </a> 
                        </li>
                        <li>
                            <a href="/stock-register">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Stock Summary
                            </a> 
                        </li>
                        <li>
                            <a href="/stock/upload-stock">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Upload Stock
                            </a> 
                        </li>
                        <!-- <li>
                            <a href="/stock/opening">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Opening Stock
                            </a> 
                        </li>
                        <li>
                            <a href="/stock/opening-list">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Opening Stock List
                            </a> 
                        </li> -->
                        <!-- <li>
                            <a href="/stock/inward">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Inward Stock
                            </a> 
                        </li> -->
                        <li>
                            <a href="/stock/inward-list">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Inward Stock List
                            </a> 
                        </li>
                        <li>
                            <a href="/stock/outward-list">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Outward Stock List
                            </a> 
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-user-o"></i>
                        Regular Report
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="/report-assign">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Assign Form
                            </a> 
                        </li>
                        <li>
                            <a href="/report-form">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Form List
                            </a> 
                        </li>
                        <li>
                            <a href="/report-info">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Assign Form List
                            </a> 
                        </li>
                        <li>
                            <a href="/report-info/listing">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Report Data List
                            </a> 
                        </li>
                        <li>
                            <a href="/client/report-form/assigned-list">
                                <i class="metismenu-icon fa fa-shopping-bag"></i>
                                Report View Access
                            </a> 
                        </li>
                    </ul>
                </li>
                <!-- <li>
                    <a href="/dynamic-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        Submit Dynamic Form To Super Admin
                    </a>
                </li> -->
                <!-- <li>
                    <a href="/dynamic-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        Accepted/Rejected Dynamic Form
                    </a>
                </li> -->
            </ul>
        </div>
    </div>
</div>