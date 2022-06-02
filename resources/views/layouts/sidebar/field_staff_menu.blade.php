<div class="app-sidebar sidebar-shadow {{Auth::user()->companies[0]->theme}} text-light">
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">App Menus</li>
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
                    <a href="/supervisor/entities-form"><i class="metismenu-icon fa fa-shopping-bag"></i>
                    Accept/Deny Location</a>
                </li>
            </ul>
        </div>
    </div>
</div>