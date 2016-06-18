<div class="topheader">
    <div class="left">
        <h1 class="logo"> POSYANDU </span></h1>
        <span class="slogan"> Beta </span>
        
        <br clear="all" />
        
    </div><!--left-->
    
    <div class="right">
    	<div class="notification">
            <a class="count" href="ajax/notifications.html"><span>9</span></a>
    	</div>
    	<div class="userinfo">
        	<img src="images/thumbs/avatar.png" alt="" />
            {{-- <span>{{ $user->name }}</span> --}}
        </div><!--userinfo-->
        
        <div class="userinfodrop">
        	<div class="avatar">
            	<a href="#"><img src="images/thumbs/avatarbig.png" alt="" /></a>
            </div><!--avatar-->
            <div class="userdata">
            	{{-- <h4>{{ $user->name }}</h4> --}}

                {{-- <span class="email"> {{ $user->email }}</span> --}}

                <ul>
                	<li><a href="editprofile.html">Edit Profile</a></li>
                    
                    <li><a href="{{ route('do-Logout') }}" title="">LOGOUT</a></li>
                </ul>
            </div><!--userdata-->
        </div><!--userinfodrop-->
    </div><!--right-->
</div><!--topheader-->