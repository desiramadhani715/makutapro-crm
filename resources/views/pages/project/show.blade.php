@extends('layouts.simple.master')
@section('title', 'Validation Forms')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/quill.core.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/quill.snow.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- Include SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

<!-- Include Lottie library -->
<script src="https://cdn.jsdelivr.net/npm/lottie-web@5.7.13/build/player/lottie.min.js"></script>

@endsection

@section('style')
<style>
  .preview-image {
      display: flex;
      flex-wrap: wrap;
  }

  .preview-image img {
      width: 200px;
      height: auto;
      margin-right: 10px;
      margin-bottom: 10px;
  }

.loader-container {
    text-align: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Latar belakang transparan */
    display: none; /* Mulai dengan elemen tersembunyi */
    z-index: 9999;
}
</style>
@endsection

@section('breadcrumb-title')
<h3>Validation Forms</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Form Controls</li>
<li class="breadcrumb-item active">Validation Forms</li>
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-xl-8 xl-100">
			<div class="card">
				<div class="card-header">
					<h5>Project {{ $project->nama_project }}</h5>
					<span>can edit <code>project detail</code> and <code>move leads</code> here</span>
				</div>
				<div class="card-body">
					<ul class="nav nav-tabs border-tab nav-primary" id="info-tab" role="tablist">
						<li class="nav-item"><a class="nav-link active" id="info-detail-tab" data-bs-toggle="tab" href="#info-detail" role="tab" aria-controls="info-detail" aria-selected="true"><i class="icofont icofont-info-circle"></i>Detail Info</a></li>
						<li class="nav-item"><a class="nav-link" id="move-prospect-tab" data-bs-toggle="tab" href="#move-prospect" role="tab" aria-controls="move-prospect" aria-selected="false"><i class="icofont icofont-hand-drag1"></i>Move Prospect</a></li>
						<li class="nav-item"><a class="nav-link" id="image-banner-tab" data-bs-toggle="tab" href="#image-banner" role="tab" aria-controls="image-banner" aria-selected="false"><i class="icofont icofont-image"></i>Banner</a></li>
					</ul>
					<div class="tab-content" id="info-tabContent">
						<div class="tab-pane fade show active" id="info-detail" role="tabpanel" aria-labelledby="info-detail-tab">
							<div class="card-body">
                                <input type="hidden" value="{{$project->id}}" id="project_id" name="project">
                                <form method="POST" action="{{route('project.update',$project->id)}}" role="form" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="validationCustom01">Kode Project</label>
                                            <input class="form-control" id="validationCustom01" type="text" required="" name="kode_project" value="{{$project->kode_project}}" disabled>
                                        </div>
                                        <div class="col mb-3">
                                            <label for="validationCustom02">Nama Project</label>
                                            <input class="form-control" id="validationCustom02" type="text" required="" name="nama_project" value="{{$project->nama_project}}">
                                        </div>
                                    </div>

                                    <div class="preview-image"></div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="validationCustom02"></label>
                                            <div class="input-group">
                                                <button class="btn btn-primary mt-2" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
						</div>
						<div class="tab-pane fade" id="move-prospect" role="tabpanel" aria-labelledby="move-prospect-tab">
							<div class="card-body">

                                <div class="row mb-4">
                                    <label for="" style="text"><code>Filter Column</code></label>
                                    <div class="col-12 col-lg-3 table-filters ">
                                        <div class="filter-container">
                                            <select id="agent" class="js-example-disabled-results"  onchange="refreshDatatable()">
                                                <option value="">Select Agent</option>
                                                @foreach ($agent as $item)
                                                    <option value="{{$item->id}}">{{$item->nama_agent}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3 table-filters ">
                                        <div class="filter-container">
                                            <select id="sales" class="js-example-disabled-results"  onchange="refreshDatatable()">
                                                <option value="">Select Sales</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3 table-filters ">
                                        <div class="filter-container">
                                            <select id="status" class="js-example-disabled-results"  onchange="refreshDatatable()">
                                                <option value="">Select Status</option>
                                                @foreach ($status as $item)
                                                <option value="{{$item->id}}">{{$item->status}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <label for=""><code>Move To</code></label>
                                    <div class="col-12 col-lg-3 table-filters ">
                                        <div class="filter-container">
                                            <select id="agentNext" class="js-example-disabled-results" name="agentNext">
                                                <option value="">Select Next Agent</option>
                                                @foreach ($agent as $item)
                                                    <option value="{{$item->id}}">{{$item->nama_agent}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3 table-filters ">
                                        <div class="filter-container">
                                            <select id="salesNext" class="js-example-disabled-results" name="salesNext">
                                                <option value="">Select Next Sales</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3 table-filters ">
                                        <div class="filter-copntainer">
                                            <a class="btn btn-primary btn-outline" id="move-prospects">Move</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <div class="overlay">
                                        <div id="loader-container" class="loader-container"></div>
                                    </div>
                                    <table class="display datatables" id="prospect-project-datatable"  style="font-size: 12px;width:100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" class="data-checkbox" id="checkAllProspect">
                                                </th>
                                                <th>ID</th>
                                                <th>Nama & No Hp</th>
                                                <th>Source</th>
                                                <th>Plarform</th>
                                                <th>Project</th>
                                                <th>Agent & Sales</th>
                                                <th>Status</th>
                                                <th>Input Date</th>
                                                <th>Process Date</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>
                                                    ID
                                                </th>
                                                <th>Nama & No Hp</th>
                                                <th>Source</th>
                                                <th>Plarform</th>
                                                <th>Project</th>
                                                <th>Agent & Sales</th>
                                                <th>Status</th>
                                                <th>Input Date</th>
                                                <th>Process Date</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
						</div>
                        <div class="tab-pane fade" id="image-banner" role="tabpanel" aria-labelledby="image-banner-tab">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <a class="btn btn-primary" href="{{ route('project.banner.create', ['id_project' => $project->id]) }}">Create New Banner</a>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-responsive table-striped table-hover" id="banner-table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Title</th>
                                                    <th>Subtitle</th>
                                                    <th>Banner</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($project->banner as $banner)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $banner->title }}</td>
                                                        <td>{{ $banner->subtitle }}</td>
                                                        <td><img src="{{ $banner->banner }}" alt="" width="100px"></td>
                                                        <td>
                                                            <form action="{{ route('project.banner.destroy', ['id_project' => $project->id, 'id_banner' => $banner->id]) }}" method="POST" onsubmit="return confirm('Are you sure want to delete this banner?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="{{ route('project.banner.edit', ['id_project' => $project->id, 'id_banner' => $banner->id]) }}"><img src="{{asset('assets/images/button/info.png')}}" alt="info" class="mt-2"></i></a>
                                                                <button type="submit" class="btn p-0"><a title="Delete Agent" class="ms-1" href=""><img src="{{asset('assets/images/button/trash.png')}}" alt="Delete Agent"></a></button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>

<!-- scrollbar js-->
<script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
{{-- quill --}}
{{-- <script src="{{ asset('assets/js/sidebarzn.js') }}"></script> --}}
<script src="{{ asset('assets/js/editor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/editor/quill/quill.config.js') }}"></script>
{{-- <script src="{{ asset('assets/js/editor/summernote/summernote.custom.js') }}"></script> --}}
<script src="{{ asset('assets/js/tooltip-init.js') }}"></script>

<script>
	function refreshDatatable(){
		$('#prospect-project-datatable').DataTable({
			"serverSide": true,
			"destroy": true,
			"order" : [[0, 'desc']],
			"ajax": {
				"url": "{{ route('prospect.all') }}",
				"data": {
					"project": {{ $project->id }},
					"agent": $("#agent").val(),
					"sales": $("#sales").val(),
					"status": $("#status").val(),
				}
			},
			"aoColumnDefs": [
				{ "bSortable": false, "aTargets": [ 0, 2, 3, 4, 5, 6, 7 ] },
				// { "width" : "100%", "targets": 9}
			],
			"columns": [
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<input type="checkbox" class="prospect-checkbox" value="' + row.id + '" name="prospect_id[]">';
                    }
                },
				{ data: 'id' },
				{
					mRender: function(data, type, row) {
						return `
							<span>${row.nama_prospect}</span><br><a href='https://api.whatsapp.com/send?phone=${row.kode_negara.substring(1)}${row.hp.substring(1)}' target='_blank'><span class='card-subtitle' style='color:#6F9CD3'>${row.hp}</span></a>
						`
					}
				},
				{ data: 'nama_sumber' },
				{ data: 'nama_platform' },
				{ data: 'nama_project' },
				{
					mRender: function(data, type, row) {
						return `
						<span style="color:#6F9CD3">${row.kode_agent}</span><br><span class="card-subtitle">${row.nama_sales}</span>
						`
					}
				},
				{
					mRender: function(data, type, row) {
						return `
						<span class="span badge rounded-pill pill-badge-${row.status_id} text-light">${row.status}</span>
						`
					}
				},
				{
                    mRender: function(data, type, row) {
						var date = new Date(row.created_at);
						var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
						var process_date = '';

						if(row.created_at != null && row.created_at != '0000-00-00 00:00:00')
							 process_date = `<small class="card-subtitle" style='font-size: 11px;color: #020202;'>`+ date.getHours()+':'+date.getMinutes() +' '+ date.getDate() + ', ' + monthNames[date.getMonth()] + ' '+ date.getFullYear().toString().substring(2)+`</small>`;

						return process_date;
				    }
                },
				{
                    mRender: function(data, type, row) {
						var date = new Date(row.accept_at);
						var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
						var process_date = '';

						if(row.accept_at != null && row.accept_at != '0000-00-00 00:00:00')
							 process_date = `<small class="card-subtitle" style='font-size: 11px;color: #020202;'>`+ date.getHours()+':'+date.getMinutes() +' '+ date.getDate() + ', ' + monthNames[date.getMonth()] + ' '+ date.getFullYear().toString().substring(2)+`</small>`;

						return process_date;
				    }
                 }
			],
			"deferRender": true,
		});
	}

    $('#banner-table').DataTable();

	refreshDatatable();

	$('#agent').change(function(){
        var agent = $(this).val();
        if(agent){
            $.ajax({
            type:"GET",
            url:"/getsales?agent="+agent,
            dataType: 'JSON',
            success:function(res){
                if(res){
                    $("#sales").empty();
                    $("#sales").append('<option value="">All</option>');
                    $.each(res,function(sales_id,nama_sales){
                        $("#sales").append('<option value="'+sales_id+'">'+nama_sales+'</option>');
                    });
                }else{
                $("#sales").empty();
                }
            }
            });
        }else{
            $("#sales").empty();
        }
    });

	$('#agentNext').change(function(){
        var agent = $(this).val();
        if(agent){
            $.ajax({
            type:"GET",
            url:"/getsales?agent="+agent,
            dataType: 'JSON',
            success:function(res){
                if(res){
                    $("#salesNext").empty();
                    $("#salesNext").append('<option value="">All</option>');
                    $.each(res,function(sales_id,nama_sales){
                        $("#salesNext").append('<option value="'+sales_id+'">'+nama_sales+'</option>');
                    });
                }else{
                $("#salesNext").empty();
                }
            }
            });
        }else{
            $("#salesNext").empty();
        }
    });

    $("#checkAllProspect").change(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});

    $(document).ready(function() {
        $("#move-prospects").click(function() {
            var selectedProspects = [];
            var agentNext = $('#agentNext').val();
            var salesNext = $('#salesNext').val();

            // Iterate through the checkboxes to find selected prospects
            $(".prospect-checkbox:checked").each(function() {
                selectedProspects.push($(this).val());
            });


            // If no agent next are selected, do nothing
            if (agentNext === '') {
                alert("Please select at least the next agent to move.");
                return;
            }

            // If no sales next are selected, do nothing
            if (salesNext === '') {
                alert("Please select at least the next sales to move.");
                return;
            }

            // If no prospects are selected, do nothing
            if (selectedProspects.length === 0) {
                alert("Please select at least one prospect to move.");
                return;
            }

            manageLoader(true);

            // AJAX request to post selected prospects
            $.ajax({
                url: "{{ route('prospect.move') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    prospects: selectedProspects,
                    agentNext: agentNext,
                    salesNext: salesNext
                },
                success: function(response) {

                    manageLoader(false);

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: `Prospects moved successfully`,
                        confirmButtonText: 'OK'
                    }).then(function() {
                        refreshDatatable();
                    });
                },
                error: function(error) {

                    manageLoader(false);

                    alert("An error occurred while moving prospects.");
                    console.error(error);
                }
            });
        });

        function manageLoader(show) {
            var loaderContainer = $("#loader-container");

            if (show) {
                // Show the loader
                lottie.loadAnimation({
                    container: document.getElementById('loader-container'),
                    path: 'https://lottie.host/41096fbe-efc9-4e23-9d3f-cf91d597f529/dzleVB0xL6.json', // Replace with your animation JSON path
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    responseType: 'text'
                });
                $(".overlay").show();
                loaderContainer.show();
            } else {
                // Hide and destroy the loader
                loaderContainer.empty().hide();
                $(".overlay").hide();
            }
        }
    });

</script>

<script>
    function previewImages() {
        var previewContainer = document.querySelector('.preview-image');
        var files = document.querySelector('#banner').files;

        function readAndPreview(file) {
            var reader = new FileReader();
            reader.addEventListener('load', function() {
                var image = new Image();
                image.src = this.result;
                image.width = 200;
                image.height = 200;
                previewContainer.appendChild(image);
            });
            reader.readAsDataURL(file);
        }

        if (files) {
            [].forEach.call(files, readAndPreview);
        }
    }

    document.querySelector('#banner').addEventListener('change', previewImages);


</script>

@endsection
