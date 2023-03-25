<div class="sidebar-wrapper">
	<div>
		<div class="logo-wrapper">
			<a href="{{route('/')}}"><img class="img-fluid for-light" src="{{asset('assets/img/logo-sidebar.png')}}" alt=""></a>
		</div>
		<div class="logo-icon-wrapper"><a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a></div>
		<nav class="sidebar-main">
			<div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
			<div id="sidebar-menu">
				<ul class="sidebar-links" id="simple-bar">
					<li class="">
						{{-- <a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a>
						<div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div> --}}
					</li>

					<li class="sidebar-list">
						<div class="text-center mb-3 d-none d-sm-block">
							<a class="setting-primary" style="position: absolute; right:25px;" id="changeLogo"><i class="icofont icofont-gear"></i></a>
							<input type="file" id="logo" style="display:none;" accept="image/*" onchange="loadFile(event)"/>
							<img src="{{ Auth::user()->photo != null ? asset('images/logo/'.Auth::user()->photo) : asset('assets/img/user.jpg') }}" class="rounded-circle img-thumbnail mt-3" alt="logo" width="100px" style="border-color: #3177B6">
						</div>
						<div class="text-center profile-details mb-2">
							<h6 style="color: #2c323f">{{Auth::user()->name}}</h6>
							<span class="font-roboto" style="color:#999">Developer</span>
						 </div>
						 {{-- <hr style="background-color: #F09236"> --}}
					</li>
					
					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='/' ? 'active' : '' }}" href="{{route('/')}}"><i data-feather="home"> </i><span>Dashboard</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='prospect.index' ? 'active' : '' }}" href="{{route('prospect.index')}}"><i data-feather="list"> </i><span>Prospect</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='project.index' ? 'active' : '' }}" href="{{route('project.index')}}"><i data-feather="layers"> </i><span>Project</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='agent.index' ? 'active' : '' }}" href="{{route('agent.index')}}"><i data-feather="users"> </i><span>Agent</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='demografi.index' ? 'active' : '' }}" href="{{route('demografi.index')}}"><i data-feather="pie-chart"> </i><span>Demographics</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='setting.index' ? 'active' : '' }}" href="{{route('setting.index')}}"><i data-feather="settings"> </i><span>Setting</span></a></li>

					{{-- <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('agent.index')}}"><i data-feather="dollar-sign"> </i><span>ROAS</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('agent.index')}}"><i data-feather="codepen"> </i><span>Campaign Management</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{route('agent.index')}}"><i data-feather="map"> </i><span>Unit Type</span></a></li> --}}


				</ul>
			</div>
			<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>	
		</nav>
	</div>
</div>