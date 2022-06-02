<div class="app-sidebar sidebar-shadow {{Auth::user()->companies[0]->theme}}">
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">App Menus</li>
                <li>
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-user-o"></i>
                        Users
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('staffs.index')}}">
                                <i class="metismenu-icon"></i>
                                Staff List
                            </a>
                        </li>
                        <li>
                            <a href="/staff-attendance">
                                <i class="metismenu-icon"></i>
                                Staff Attendance
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admins.index')}}">
                                <i class="metismenu-icon"></i>
                                Company
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li>
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
                </li> --}}
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
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-user-o"></i>
                        Entity Tracking
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="/super/entity-form/create"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Create Entity From 
                            </a>
                        </li>
                        <li>
                            <a href="/super/entity-form/list"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Entity Form List
                            </a>
                        </li>
                        <li>
                            <a href="/super/entity-form/assign"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Assign Entity Form
                            </a>
                        </li>
                        <li>
                            <a href="/super/entity-data/list"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Entity Data List
                            </a>
                        </li>
                        <li>
                            <a href="/super/entity-form/client-list"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Form Client Access
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void()">
                        <i class="metismenu-icon fa fa-user-o"></i>
                            Report Tracking
                        <i class="metismenu-state-icon fa fa-angle-down"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="/super/report-form/create"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Create Report From 
                            </a>
                        </li>
                        <li>
                            <a href="/super/report-form/list"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Report Form List
                            </a>
                        </li>
                        <li>
                            <a href="/super/report-form/assign"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Assign Report Form
                            </a>
                        </li>
                        <li>
                            <a href="/super/report-data/list"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Report Data List
                            </a>
                        </li>
                        <li>
                            <a href="/super/report-form/client-list"><i class="metismenu-icon fa fa-file-excel-o"></i>
                                Form Client Access
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li>
                    <a href="/entities-form/" ><i class="metismenu-icon fa fa-shopping-bag"></i>
                        Entity Form List
                    </a>
                </li> -->
                <li>
                    <a href="/map-location">
                        <i class="metismenu-icon fa fa-map"></i>
                        Entity Location
                    </a>
                </li>
                <!-- <li>
                    <a href="/entities">
                        <i class="metismenu-icon fa fa-shopping-bag"></i>
                        Entities Assaign Client
                    </a>
                </li> -->
                <!-- <li>
                    <a href="/dynamic-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        Accept/Reject Form
                    </a>
                </li> -->

                <li>
                    <a href="/entities-history"><i class="metismenu-icon fa fa-shopping-bag"></i>
                        Entities
                    </a>
                </li>
                <li>
                    <a href="{{route('group-entites.index')}}">
                        <i class="metismenu-icon fa fa-map"></i>
                        Entity Group List
                    </a>
                </li>
                <li>
                    <a href="/generate-report"><i class="metismenu-icon fa fa-file-excel-o"></i>
                        Report Generate
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>