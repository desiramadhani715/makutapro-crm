@extends('layouts.simple.master')
@section('title', 'Agent')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/photoswipe.css')}}">
@endsection


@section('style')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

@section('breadcrumb-title')
<h3>Agent</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Agent</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 project-list">
		   <div class="card">
			  <div class="row">
				 <div class="col-md-6">
					<div class="left-header col horizontal-wrapper ps-0">
						<div class="row my-0">
							<div class="col-3">
								<input class="form-control form-control-sm datepicker-here since" name="since" id="since" type="text" data-language="en" placeholder="Since">
							</div>
							<div class="col-3">
								<input class="form-control form-control-sm datepicker-here " name="to" id="to" type="text" data-language="en" placeholder="To">
							</div>
						</div>	
					</div>
				 </div>
				 <div class="col-md-6">
					<div class="form-group mb-0 me-0"></div>
					<a class="btn btn-primary px-2" title="Create New" data-bs-toggle="modal" data-bs-target="#add"> <i data-feather="plus-square"> </i>Add</a>
					<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
						<div class="modal-dialog modal-lg" role="document">
						   <div class="modal-content"  style="border-radius: 20px;">
							  <div class="modal-header" style="background-color: #6F9CD3; border-top-left-radius: 20px;border-top-right-radius: 20px;">
								<h2 class="modal-title text-white" style="font-family: Montserrat ,
								sans-serif Medium 500; font-size: 25px;"><strong>MAKUTA</strong> Pro</h2>
								 <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
							  </div>
							  <form action="{{route('agent.store')}}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="modal-body form">
									<div class="row">
										<div class="user-profile">
											<div class="card hovercard text-center">
												<div class="user-image" style="margin-top: 80px">
													<div class="avatar"><img alt="photo" src="{{asset('assets/images/avtar/user.jpg')}}" id="photoPreview"></div>
													<div class="icon-wrapper" id="changePhoto"><i class="icofont icofont-pencil-alt-5"></i></div>
													<input type="file" id="photo" style="display:none;" accept="image/*" onchange="loadFile(event)" name="photo"/>
												</div>
											</div>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col">
											<label class="control-label">Project</label>
											<select id="project" class="form-select digits" name="project_id">
												<option value="">All</option>
												@foreach ($project as $item)
												<option value="{{$item->id}}">{{$item->nama_project}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xl-6">
											<label  style="color: #827575">PIC</label>
											<input class="form-control mb-2" type="text" name="pic">
										</div>
										<div class="col-lg-6">
											<label  style="color: #827575">Nama</label>
											<input class="form-control mb-2" type="text" name="nama_agent" required>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-lg-6">
											<label  style="color: #827575">Email</label>
											<input class="form-control mb-2" type="email" name="email" required>
										</div>
										<div class="col-lg-6">
											<label  style="color: #827575">No. Handphone</label>
											<input class="form-control mb-2" type="text" name="hp" placeholder="cth: 0812345678" required>
										</div>
									</div>
								</div>
							  <div class="modal-footer">
								<button class="btn  modal-close " style="background-color: #6F9CD3; border-radius: 50px; color: #fff;" type="submit">Save Change</button>
							  </div>
							  </form>
						   </div>
						</div>
					</div>
				</div>
			  </div>
		   </div>
		</div>
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between">
					<h5>Data Agent</h5>
					@if (session('alertSuccess'))
						<div class="col-4">
							<div class="alertSuccess alert alert-primary outline alert-dismissible fade show" role="alert">
								<i data-feather="check"></i>
								<span>Agent account created successfully!</span>
								<button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
							 </div>
						</div>
					@endif
					@if (session('alertDeleted'))
						<div class="col-4">
							<div class="alertDeleted alert alert-primary outline alert-dismissible fade show" role="alert">
								<i data-feather="check"></i>
								<span>Agent account deleted successfully!</span>
								<button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
							 </div>
						</div>
					@endif
					@if (session('alertFailed'))
						<div class="col-4">
							<div class="alertFailed alert alert-danger outline alert-dismissible fade show" role="alert">
								<i data-feather="alert-triangle"></i>
								<span>Failed!. Please contact Support</span>
								<button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
							 </div>
						</div>
					@endif
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="display f-12" id="basic-1">
							<thead>
								<tr class="text-center">
                                    <th style="width: 10px">No.</th>
                                    <th>Nama Agent</th>
                                    <th>Sort</th>
                                    <th>Project</th>
                                    <th>Closing Amount</th>
                                    <th>Active</th>
                                    <th>Action</th>
								</tr>
							</thead>
                            <tbody>
							@forelse ($data as $agent)
								<tr class="text-center">
									<td>{{$loop->iteration}}</td>
									<td class="text-start">
										<div class="d-inline-block align-middle">
											{{-- <img class="img-40 m-r-15 rounded-circle align-top img-thumbnail" src="{{ $agent->photo ? $agent->photo : asset('assets/images/avtar/user.jpg')}}" alt="Pict"> --}}
											<div class="d-inline-block">
												<strong>{{$agent->nama_agent}}</strong><br>
												<a href="https://api.whatsapp.com/send?phone=62{{substr($agent->hp, 1)}}" target="_blank"><span class="card-subtitle font-roboto" style="color: #827575; font-size:11px;">{{$agent->hp}}</span></a>
											</div>
										  </div>
									</td>
									<td>{{$agent->urut_agent}}</td>
									<td>{{$agent->nama_project}}</td>
									<td>
										@if ($agent->closing_amount > 0)
										<span style="color:#51bb25" class="font-roboto">Rp. {{number_format($agent->closing_amount,0, ',' , '.')}}</span>
										@else
										<span style="color:#f73164" class="font-roboto">Rp. {{number_format($agent->closing_amount,0, ',' , '.')}}</span>
										@endif
										
									</td>
									<td>
										@if (!$agent->active)
										<form action="{{route('agent.active')}}" method="POST" onsubmit="return confirm('Non aktifkan Agent {{$agent->nama_agent}} ?')">
											@method('post')
											@csrf
											<input type="hidden" value="{{$agent->id}}" name="agent_id">
											<button class="btn" type="submit">
												<span class="badge rounded-pill round-badge-info">Active</span>
											</button>
										</form>
										@else
										<form action="{{route('agent.nonactive')}}" method="POST" onsubmit="return confirm('Aktifkan Agent {{$agent->nama_agent}} ?')">
											@method('post')
											@csrf
											<input type="hidden" value="{{$agent->id}}" name="agent_id">
											<button class="btn" type="submit">
												<span class="badge rounded-pill round-badge-secondary">Non Active</span>
											</button>
										</form>
										@endif
									</td>
									<td>
										<a title="Show Detail" data-bs-toggle="modal" data-bs-target="#detail{{$agent->id}}"><img src="{{asset('assets/images/button/info.png')}}" alt="info"></a>
										<a title="Sales" class="ms-1" href="{{route('sales.index', $agent->id)}}"><img src="{{asset('assets/images/button/users.png')}}" alt="Sales"></a>
										{{-- <a title="Delete Agent" class="ms-1" href=""><img src="{{asset('assets/images/button/trash.png')}}" alt="Delete Agent"></a> --}}
										<form action="{{url('agent/'.$agent->id)}}" method="post" onsubmit="return confirm('Apakah anda yakin ?')">
											@method('delete')
											@csrf
											<button type="submit" class="btn p-0"><a><img src="{{asset('assets/images/button/trash.png')}}" alt="Delete Agent"></a></button>
										</form>
									</td>
								</tr>

								<div class="modal fade" id="detail{{$agent->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg" role="document">
									   <div class="modal-content"  style="border-radius: 20px;">
										  <div class="modal-header" style="background-color: #6F9CD3; border-top-left-radius: 20px;border-top-right-radius: 20px;">
											<h2 class="modal-title text-white" style="font-family: Montserrat ,
											sans-serif Medium 500; font-size: 25px;"><strong>MAKUTA</strong> Pro</h2>
											 <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
										  </div>
										  <form action="{{route('agent.update', $agent->id)}}" method="POST" enctype="multipart/form-data">
											@method('PUT')
											@csrf
											<div class="modal-body form">
												<div class="row">
													<div class="user-profile">
														<div class="card hovercard text-center">
															<div class="user-image" style="margin-top: 80px">
																<div class="avatar"><img alt="photo" src="{{ $agent->photo ? $agent->photo : asset('assets/images/avtar/user.jpg')}}" id="photoPreviewEdit{{ $agent->id }}"></div>
																<div class="icon-wrapper" id="changePhotoEdit{{ $agent->id }}"><i class="icofont icofont-pencil-alt-5"></i></div>
																<input type="file" id="photoEdit{{ $agent->id }}" style="display:none;" accept="image/*" onchange="loadFileEdit(event)"  name="photo" value="{{  $agent->photo }}"/>
															</div>
														</div>
													</div>
													<h6 class="text-center mb-0">{{$agent->pic}}</h6>
													<p class="text-center mb-3" style="color: #827575">{{$agent->nama_agent}}</p>
													<input type="hidden" name="nama_agent" value="{{ $agent->nama_agent }}">
												</div>
												<div class="row mb-2">
													<div class="col-lg-6">
														<label style="color: #827575">Join Date</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="text" value="{{date_format(date_create($agent->created_at), "d F Y")}}" disabled>
													</div>
													<div class="col-lg-6">
														<label  style="color: #827575">Username</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="text" value="{{$agent->username}}" name="username" disabled>
													</div>
												</div>
												<div class="row mb-2">
													<div class="col-lg-6">
														<label  style="color: #827575">No. Handphone</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="text" value="{{$agent->hp}}" name="hp">
													</div>
													<div class="col-lg-6">
														<label  style="color: #827575">Email</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="email" value="{{$agent->email}}" name="email" >
													</div>
												</div>
												<div class="row">
													<div class="col-lg-12">
														<label  style="color: #827575">Change Password</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="password" value="" name="password">
													</div>
												</div>
											</div>
										  <div class="modal-footer">
											<button class="btn  modal-close " style="background-color: #6F9CD3; border-radius: 50px; color: #fff;" type="submit">Save Change</button>
										  </div>
										  </form>
									   </div>
									</div>
								</div>
							@empty
								
							@endforelse

							<script>
								$(document).ready(function() {
									@forelse ($data as $agent)
										$('#changePhotoEdit{{ $agent->id }}').click(function() {
											$('#photoEdit{{ $agent->id }}').click();
										});
							
										$('#photoEdit{{ $agent->id }}').change(function(event) {
											var agentId = '{{ $agent->id }}';
											var output = $('#photoPreviewEdit' + agentId)[0];
											var file = event.target.files[0];
											var reader = new FileReader();
							
											reader.onload = function(e) {
												output.src = e.target.result;
											};
							
											reader.readAsDataURL(file);
										});
									@empty
										// Handle the case when $data is empty
									@endforelse
								});
							</script>
                            </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$('#changePhoto').click(function() {
		$('#photo').click();
	});

	var loadFile = function(event) {
		var output = document.getElementById('photoPreview');
		output.src = URL.createObjectURL(event.target.files[0]);
		output.onload = function() {
		URL.revokeObjectURL(output.src) // free memory
		}
	};


    // alert success
    window.setTimeout(function() {
		$(".alertSuccess").fadeTo(200, 0).slideUp(200, function(){
			$(this).remove(); 
		});
    }, 5000);

    // alert failed
    window.setTimeout(function() {
		$(".alertFailed").fadeTo(200, 0).slideUp(200, function(){
			$(this).remove(); 
		});
    }, 5000);

    // alert deleted
    window.setTimeout(function() {
		$(".alertDeleted").fadeTo(200, 0).slideUp(200, function(){
			$(this).remove(); 
		});
    }, 5000);

</script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe.js')}}"></script>

@endsection