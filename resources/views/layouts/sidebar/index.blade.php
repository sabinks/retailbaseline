{{-- <div class="app-sidebar sidebar-shadow {{Auth::user()->companies[0]->theme}} text-light">
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu"> --}}
                {{-- Sidebar menu for Super Admin --}}
                {{-- @role('Super Admin')
                    <li class="app-sidebar__heading">App Menus</li>
                    <li>
                        <a href="javascript:void()">
                            <i class="metismenu-icon fa fa-user-o"></i>
                            Staffs
                            <i class="metismenu-state-icon fa fa-angle-down"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="{{route('staffs.index')}}">
                                    <i class="metismenu-icon"></i>
                                    Staff List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void()">
                            <i class="metismenu-icon fa fa-building-o"></i>
                                Client
                            <i class="metismenu-state-icon fa fa-angle-down"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="{{route('admins.index')}}">
                                    <i class="metismenu-icon"></i>
                                    Admin List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void()">
                            <i class="metismenu-icon fa fa-map"></i>
                            Regions
                            <i class="metismenu-state-icon fa fa-angle-down"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="{{route('regions.index')}}">
                                    <i class="metismenu-icon"></i>
                                    Region List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/map-location">
                            <i class="metismenu-icon fa fa-map"></i>
                            Entity Location
                        </a>
                    </li>
                    <li>
                        <a href="/entities">
                            <i class="metismenu-icon fa fa-shopping-bag"></i>
                            Entities assaign client
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void()">
                            <i class="metismenu-icon fa fa-shopping-bag"></i>
                            Dynamic Form
                            <i class="metismenu-state-icon fa fa-angle-down"></i>
                        </a>
                        <ul>
                            <li>
                                <a class='metismenu-icon' href="/dynamic-form/" >
                                    Regular Reporting Form List
                                </a>
                            </li>
                            <li>
                                <a class='metismenu-icon' href="/entities-form/" >
                                    Entities Traking Form List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/dynamic-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                            Accept/Reject Form
                        </a>
                    </li> --}}
                    {{-- <li>
                        <a href="entitiesList">
                            <i class="metismenu-icon fa fa-shopping-bag"></i>
                            Accept/Deny Location
                        </a>
                    </li> --}}
                @endrole
                {{-- closing of Super Admin --}}

                {{-- Sidebar menu for Admin --}}
                @role('Admin')
                    {{-- <li class="app-sidebar__heading">Theme Settings</li>
                    <li>
                        <a href="/themeSetting" class="mm-active">
                            <i class="metismenu-icon fa fa-tachometer"></i>
                            Theme Settings
                        </a>
                    </li>
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
                                    My regions
                                </a>
                            </li>
                        </ul>
                    </li>
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
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void()">
                            <i class="metismenu-icon fa fa-building-o"></i>
                                Higher Level Employee
                            <i class="metismenu-state-icon fa fa-angle-down"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="/regionalAdmins">
                                    <i class="metismenu-icon"></i>
                                    My Regional Admin List
                                </a>
                            </li>
                            <li>
                                <a href="/supervisors">
                                    <i class="metismenu-icon"></i>
                                    My Supervisor List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/map-location">
                            <i class="metismenu-icon fa fa-map-marker"></i>
                            Entity Location
                        </a>
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
                                    Assign Report Form
                                </a> 
                            </li>
                            <li>
                                <a href="/report-form">
                                    <i class="metismenu-icon fa fa-shopping-bag"></i>
                                    Report Form
                                </a> 
                            </li>
                            <li>
                                <a href="/report-info">
                                    <i class="metismenu-icon fa fa-shopping-bag"></i>
                                    Report Form Data
                                </a> 
                            </li>
                            <li>
                                <a href="/report-info/listing">
                                    <i class="metismenu-icon fa fa-shopping-bag"></i>
                                    Regular Report List
                                </a> 
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{route('group-entites.index')}}">
                            <i class="metismenu-icon fa fa-map"></i>
                            Entity Group List
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void()">
                            <i class="metismenu-icon fa fa-shopping-bag"></i>
                            Dynamic Form
                            <i class="metismenu-state-icon fa fa-angle-down"></i>
                        </a>
                        <ul>
                            <li>
                                <a class='metismenu-icon' href="/dynamic-form/" >
                                    Regular Reporting Form List
                                </a>
                            </li>
                            <li>
                                <a class='metismenu-icon' href="/entities-form/" >
                                    Entities Traking Form List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/dynamic-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        submit Dynamic Form to super admin
                        </a>
                    </li>
                    <li>
                        <a href="/dynamic-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        view accepted and rejected Dynamic Form
                        </a>
                    </li>
                    <li>
                        <a href="/supervisor/entities-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        Accept/Deny Location
                        </a>
                    </li> --}}
                @endrole
                {{-- closing of Admin --}}

                {{-- Sidebar menu for Regional Admin --}}
                @role('Regional Admin')
                    {{-- <li class="app-sidebar__heading">App Menus</li>
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
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void()">
                            <i class="metismenu-icon fa fa-building-o"></i>
                                Higher Level Employee
                            <i class="metismenu-state-icon fa fa-angle-down"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="/supervisors">
                                    <i class="metismenu-icon"></i>
                                    My Supervisor List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{route('group-entites.index')}}">
                            <i class="metismenu-icon fa fa-map"></i>
                            Entity Group List
                        </a>
                    </li>
                    <li>
                        <a href="/map-location">
                            <i class="metismenu-icon fa fa-map-marker"></i>
                            Entity Location
                        </a>
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
                                    Assign Report Form
                                </a> 
                            </li>
                            <li>
                                <a href="/report-form">
                                    <i class="metismenu-icon fa fa-shopping-bag"></i>
                                    Report Form
                                </a> 
                            </li>
                            <li>
                                <a href="/report-info">
                                    <i class="metismenu-icon fa fa-shopping-bag"></i>
                                    Report Form Data
                                </a> 
                            </li>
                            <li>
                                <a href="/report-info/listing">
                                    <i class="metismenu-icon fa fa-shopping-bag"></i>
                                    Regular Report List
                                </a> 
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/dynamic-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        view accepted and rejected Dynamic Form
                        </a>
                    </li>
                    <li>
                        <a href="/supervisor/entities-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        Accept/Deny Location
                        </a>
                    </li> --}}
                @endrole
                {{-- closing of Regional Admin --}}

                {{-- Sidebar menu for Supervisor --}}
                @role('Supervisor')
                    {{-- <li class="app-sidebar__heading">App Menus</li>
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
                        </ul>
                    </li>
                    <li>
                        <a href="/report-info/listing">
                            <i class="metismenu-icon fa fa-shopping-bag"></i>
                            Regular Report List
                        </a> 
                    </li>
                    
                    <li>
                        <a href="/map-location">
                            <i class="metismenu-icon fa fa-map"></i>
                            Entity Location
                        </a>
                    </li>

                    <li>
                        <a href="/report-form-assigned">
                            <i class="metismenu-icon fa fa-map"></i>
                            Report Form List
                        </a>
                    </li>

                    <li>
                        <a href="/dynamic-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        accept and reject Dynamic Form
                        </a>
                    </li>
                    <li>
                        <a href="/supervisor/entities-form"><i class="metismenu-icon fa fa-shopping-bag"></i>Accept/Deny Location</a>
                    </li> --}}
                @endrole
                {{-- closing of supervisor --}}

                {{-- Sidebar menu for Supervisor --}}
                @role('Field Staff')
                    {{-- <li class="app-sidebar__heading">App Menus</li>
                    <li>
                        <a href="/entities">
                            <i class="metismenu-icon fa fa-shopping-bag"></i>
                            Entities assaign client
                        </a>
                    </li>
                    <li>
                        <a href="/dynamic-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        fill Dynamic Form data and assign entity client if form made by sA otherwise add on your own and submit rejected data
                        </a>
                    </li>
                    <li>
                        <a href="/supervisor/entities-form"><i class="metismenu-icon fa fa-shopping-bag"></i>Accept/Deny Location</a>
                    </li> --}}
                @endrole 
                {{-- closing of field staff --}}
            {{-- </ul>
        </div>
    </div>
</div> --}}