<div class="header-btn-lg pr-0">
    <div class="widget-content p-0">
        <div class="widget-content-wrapper">
            <div class="widget-content-left">
                <div class="btn-group">
                    <div class="dropdown">
                        <a class="btn btn-sm btn-default" type="button" data-toggle="dropdown">
                            @if(Auth::user()->profile_image=='' || Auth::user()->profile_image == NULL)
                                <img width="45" height="45" class="rounded-circle " src="{{asset('images/user.png')}}" alt="">
                            @endif
                            @if(Auth::user()->profile_image ==! NULL)
                                <img width="45" height="45" class="rounded-circle " src="{{asset('storage/images/profiles/'.Auth::user()->profile_image)}}" alt="">
                            @endif
                            <i class="fa fa-angle-down ml-2 opacity-8" style="color: #000; "></i>
                        </a>
                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-submenu">
                                <a class="dropdown-item test" tabindex="-1" href="#" id="setting" onclick="myFunction()">Settings</a>
                                <div id="myDIV">
                                    <a class="btn btn-sm dropdown-item" id="page_link" href="{{route('profile.edit',Auth::user()->id)}}">Update Profile</a>
                                    <a class="btn btn-sm dropdown-item" id="page_link" href="/editPassword">Change Password</a>
                                </div>
                            </div>
                            @if(Auth::user()->hasRole(['Super Admin','Admin']))
                                <a class="dropdown-item" href="/updatecompany">Company Setting</a>
                            @endif
                            @if(Auth::user()->hasRole(['Super Admin', 'Admin']))
                                <a class="dropdown-item" href="/themeSetting">Theme Setting</a>
                            @endif
                            @if(Auth::user()->hasRole('Super Admin'))
                                <a class="dropdown-item" href="{{asset('resetPassword')}}">Reset Password</a>
                            @endif
                            <div tabindex="-1" class="dropdown-divider"></div>
                            <a  class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-content-left  ml-1 header-user-info">
                <div class="widget-heading">
                    {{ Auth::user()->name }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
  $('a.test').on("click", function(e){
    e.stopPropagation();
    e.preventDefault();
  });
});
function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>
