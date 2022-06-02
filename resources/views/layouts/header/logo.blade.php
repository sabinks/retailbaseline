<div class="app-header__logo">
    <div class="logo-src">
        @if(Auth::user()->companies[0]->company_logo == NULL || Auth::user()->companies[0]->company_logo == '')
            <a href="/">
                <img width="auto" height="45" src="{{asset('images/lemon.png')}}"/>
            </a>
        @endif
        @if(Auth::user()->companies[0]->company_logo != NULL)
            <a href="/">
                <img width="auto" height="45" src="{{asset('storage/images/logos/'.Auth::user()->companies[0]->company_logo)}}"/>
            </a>
        @endif
    </div>
    <div class="header__pane ml-auto">
        <div>
            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
</div>
<a style="margin-left:2%; font-size:32px !important;color:white" href="/"><i title="Home" class="metismenu-icon fa fa-home"></i></a>
<div class="app-header__mobile-menu">
    <div>
        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>
    </div>
</div>