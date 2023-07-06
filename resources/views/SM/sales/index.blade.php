@extends('SM.layouts.simple.master')
@section('title', 'Basic DataTables')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/photoswipe.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

@section('breadcrumb-title')
<h3>Basic DataTables</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Data Tables</li>
<li class="breadcrumb-item active">Basic DataTables</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12 project-list">
		   <div class="card">
			  <div class="row">
				 <div class="col-md-6">
					<div class="left-header col horizontal-wrapper ps-0">
						{{-- <div class="row my-0">
							<div class="col-3">
								<input class="form-control form-control-sm datepicker-here since" name="since" id="since" type="text" data-language="en" placeholder="Since">
							</div>
							<div class="col-3">
								<input class="form-control form-control-sm datepicker-here " name="to" id="to" type="text" data-language="en" placeholder="To">
							</div>
						</div>	 --}}
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
							  <form action="{{route('sm.sales.store', $agent_id)}}" method="POST" enctype="multipart/form-data">
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
										<div class="col-xl-6">
											<label  style="color: #827575">Nick Name</label>
											<input class="form-control mb-2" type="text" name="nick_name">
										</div>
										<div class="col-lg-6">
											<label  style="color: #827575">Full Name</label>
											<input class="form-control mb-2" type="text" name="full_name" required>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-lg-6">
											<label  style="color: #827575">No. Handphone</label>
											<input class="form-control mb-2" type="text" name="hp" placeholder="cth: 0812345678" required>
										</div>
										<div class="col-lg-6">
											<label  style="color: #827575">Email</label>
											<input class="form-control mb-2" type="email" name="email" required>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-lg-6">
											<label style="color: #827575">Birthday</label>
											<div class="col">
												<input class="datepicker-here form-control digits" type="text" data-language="en" data-position="top left" name="birthday">
											</div>
										</div>
										<div class="col-lg-6">
											<label style="color: #827575">Gender</label>
											<div class="col">
												<div class="m-t-10 m-checkbox-inline custom-radio-ml">
													<div class="form-check form-check-inline radio radio-primary">
														<input class="form-check-input" id="radioinline1" type="radio" name="gender" value="Female">
														<label class="form-check-label mb-0" for="radioinline1">Female</label>
													</div>
													<div class="form-check form-check-inline radio radio-primary">
														<input class="form-check-input" id="radioinline2" type="radio" name="gender" value="Male">
														<label class="form-check-label mb-0" for="radioinline2">Male</label>
													</div>
												</div>
											</div>
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
					<h5>Data Sales</h5>
                    @if (session('success'))
						<div class="col-4">
							<div class="alertSuccess alert alert-primary outline alert-dismissible fade show" role="alert">
								<i data-feather="check"></i>
								<span>{{ session('success') }}</span>
								<button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
							 </div>
						</div>
					@endif
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="display f-12" id="basic-1" >
							<thead>
								<tr class="text-center">
                                    <th style="width: 10px">No.</th>
                                    {{-- <th>Sales Code</th> --}}
                                    <th>Name</th>
                                    <th>Sort</th>
                                    <th>Prospect</th>
                                    <th>Closing Amount</th>
                                    <th>Active</th>
                                    <th>Action</th>
								</tr>
							</thead>
                            <tbody>
							@forelse ($data as $sales)
								<tr class="text-center">
									<td>{{$loop->iteration}}</td>
									{{-- <td style="color: #827575">{{$sales->kode_sales}}</td> --}}
									<td class="text-start">
										<div class="d-inline-block align-middle">
											<img class="img-40 m-r-15 rounded-circle align-top" src="{{asset('assets/images/avtar/7.jpg')}}" alt="">
											<div class="d-inline-block">
												<strong>{{$sales->nama_sales}}</strong><br>
												<a href="https://api.whatsapp.com/send?phone=62{{substr($sales->hp, 1)}}" target="_blank"><span class="card-subtitle font-roboto" style="color: #827575; font-size:11px;">{{$sales->hp}}</span></a>
											</div>
										  </div>
									</td>
									<td>{{$sales->sort}}</td>
									<td>{{$sales->total_prospect}}</td>
									<td>
										@if ($sales->closing_amount > 0)
										<span style="color:#51bb25" class="font-roboto">Rp. {{number_format($sales->closing_amount,0, ',' , '.')}}</span>
										@else
										<span style="color:#f73164" class="font-roboto">Rp. {{number_format($sales->closing_amount,0, ',' , '.')}}</span>
										@endif

									</td>
									<td>
										@if (!$sales->active)
										<form action="{{route('sales.activate', $sales->id)}}" method="POST" onsubmit="return confirm('Aktifkan Sales {{$sales->nama_sales}} ?')">
											@method('POST')
                                            @csrf
											<button class="btn" type="submit">
												<span class="badge rounded-pill round-badge-info">Active</span>
											</button>
										</form>
										@else
										<form action="{{route('sales.activate', $sales->id)}}" method="POST" onsubmit="return confirm('Non Aktifkan Sales {{$sales->nama_sales}} ?')">
											@csrf
											<button class="btn" type="submit">
												<span class="badge rounded-pill round-badge-secondary">Non Active</span>
											</button>
										</form>
										@endif
									</td>
									<td class="d-flex justify-content-center">
										<a title="Show Detail" class="mt-1" data-bs-toggle="modal" data-bs-target="#detail{{$sales->id}}"><img src="{{asset('assets/images/button/info.png')}}" alt="info"></a>
                                        <form action="{{url('sm/sales/'.$sales->id)}}" method="post" onsubmit="return confirm('Apakah anda yakin ?')">
											@method('delete')
											@csrf
											<button type="submit" class="btn p-0"><a title="Delete Sales" class="ms-1" href=""><img src="{{asset('assets/images/button/trash.png')}}" alt="Delete Agent"></a></button>
										</form>
									</td>
								</tr>

								<div class="modal fade" id="detail{{$sales->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg" role="document">
									   <div class="modal-content"  style="border-radius: 20px;">
										  <div class="modal-header" style="background-color: #6F9CD3; border-top-left-radius: 20px;border-top-right-radius: 20px;">
											<h2 class="modal-title text-white" style="font-family: Montserrat ,
											sans-serif Medium 500; font-size: 25px;"><strong>MAKUTA</strong> Pro</h2>
											 <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
										  </div>
										  <form action="{{route('sm.sales.update', $sales->id)}}" method="POST" enctype="multipart/form-data">
                                            @method('PUT')
											@csrf
											<input type="hidden" name="sales_id" value="{{$sales->id}}">
											<input type="hidden" name="agent_id" value="{{$sales->agent_id}}">
											<div class="modal-body form">
												<div class="row">
													<div class="user-profile">
														<div class="card hovercard text-center">
															<div class="user-image" style="margin-top: 80px">
																<div class="avatar"><img alt="photo" src="{{ $sales->photo ? $sales->photo : asset('assets/images/avtar/user.jpg')}}" id="photoPreviewEdit{{ $sales->id }}"></div>
																<div class="icon-wrapper" id="changePhotoEdit{{ $sales->id }}"><i class="icofont icofont-pencil-alt-5"></i></div>
																<input type="file" id="photoEdit{{ $sales->id }}" style="display:none;" accept="image/*" onchange="loadFileEdit(event)" name="photo" value="{{  $sales->photo }}"/>
															</div>
														</div>
													</div>
													<h6 class="text-center mb-0">{{$sales->pic}}</h6>
													<p class="text-center mb-3" style="color: #232323">{{$sales->nama_sales}}</p>
												</div>
												<div class="row mb-2">
													<div class="col-lg-6">
														<label style="color: #827575">Join Date</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="text" value="{{date_format(date_create($sales->created_at), "d F Y")}}" disabled>
													</div>
													<div class="col-lg-6">
														<label  style="color: #827575">Username</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="text" value="{{$sales->username}}" name="username" disabled>
													</div>
												</div>
												<div class="row mb-2">
													<div class="col-lg-6">
														<label  style="color: #827575">No. Handphone</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="text" value="{{$sales->hp}}" name="hp">
													</div>
													<div class="col-lg-6">
														<label  style="color: #827575">Email</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="email" value="{{$sales->email}}" name="email" >
													</div>
												</div>
												<div class="row mb-2">
													<div class="col-lg-6">
														<label  style="color: #827575">Nick Name</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="text" value="{{$sales->nick_name}}" name="nick_name">
													</div>
													<div class="col-lg-6">
														<label  style="color: #827575">Full Name</label>
														<input class="form-control mb-2" style="border-radius: 11px;color:#645d5d" type="text" value="{{$sales->nama_sales}}" name="nama_sales" >
													</div>
												</div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-6">
                                                        <label style="color: #827575">Birthday</label>
                                                        <div class="col">
                                                            <input class="datepicker-here form-control digits" type="text" data-language="en" data-position="top left" name="birthday" value="{{ date_format(date_create($sales->birthday), "d/m/Y") }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label style="color: #827575">Gender</label>
                                                        <div class="col">
                                                            <div class="m-t-10 m-checkbox-inline custom-radio-ml">
                                                                <div class="form-check form-check-inline radio radio-primary">
                                                                    <input class="form-check-input" id="radioinline1" type="radio" name="gender" value="Female" {{ $sales->gender == "Female" ? 'checked' : '' }}>
                                                                    <label class="form-check-label mb-0" for="radioinline1">Female</label>
                                                                </div>
                                                                <div class="form-check form-check-inline radio radio-primary">
                                                                    <input class="form-check-input" id="radioinline2" type="radio" name="gender" value="Male" {{ $sales->gender == "Male" ? 'checked' : '' }}>
                                                                    <label class="form-check-label mb-0" for="radioinline2">Male</label>
                                                                </div>
                                                            </div>
                                                        </div>
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
									@forelse ($data as $sales)
										$('#changePhotoEdit{{ $sales->id }}').click(function() {
											$('#photoEdit{{ $sales->id }}').click();
										});

										$('#photoEdit{{ $sales->id }}').change(function(event) {
											var salesId = '{{ $sales->id }}';
											var output = $('#photoPreviewEdit' + salesId)[0];
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
</script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{asset('assets/js/photoswipe/photoswipe.js')}}"></script>

<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>

@endsection
