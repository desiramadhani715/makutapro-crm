@extends('layouts.simple.master')
@section('title', 'Validation Forms')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
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
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Project Detail</h5>
					<span>For change name of project</span>
				</div>
				<div class="card-body">
					<input type="hidden" value="{{$project->id}}" id="project_id" name="project">
					<form method="POST" action="{{route('project.update',$project->id)}}" role="form">
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
                        <div class="row">
                            <div class="col">
                              <div class="mb-3">
                                <label>Project Banner <sup> *Can Choose more than one</sup></label>
                                <input class="form-control" type="file" id="banner" name="banner[]" multiple="multiple" required>
                              </div>
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
		</div>
        {{-- <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
					<h5>Prospect Project</h5>
					<span>For move between Project. Filter first, then select leads and project which you want to move.</span>
                </div>
                <div class="card-body">
					<div class="row mb-4">
						<div class="col-12 col-lg-3 table-filters ">
							<div class="filter-container">
								<select id="agent" class="js-example-disabled-results" name="agent"  onchange="refreshDatatable()">
									<option value="">Select Agent</option>
									@foreach ($agent as $item)
										<option value="{{$item->id}}">{{$item->nama_agent}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-12 col-lg-3 table-filters ">
							<div class="filter-container">
								<select id="sales" class="js-example-disabled-results" name="sales"  onchange="refreshDatatable()">
									<option value="">Select Sales</option>
								</select>
							</div>
						</div>
						<div class="col-12 col-lg-3 table-filters ">
							<div class="filter-container">
								<select id="status" class="js-example-disabled-results" name="status"  onchange="refreshDatatable()">
									<option value="">Select Status</option>
									@foreach ($status as $item)
									<option value="{{$item->id}}">{{$item->status}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table class="display datatables" id="prospect-project-datatable">
							<thead>
								<tr>
									<th>ID</th>
									<th>Nama & No Hp</th>
									<th>Source</th>
									<th>Plarform</th>
									<th>Campaign</th>
									<th>Project</th>
									<th>Agent & Sales</th>
									<th>Status</th>
									<th>Input Date</th>
									<th>Process Date</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>
										ID
									</th>
									<th>Nama & No Hp</th>
									<th>Source</th>
									<th>Plarform</th>
									<th>Campaign</th>
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
        </div> --}}
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script><script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>

<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script>
	function refreshDatatable(){
		$('#prospect-project-datatable').DataTable({
			"serverSide": true,
			"destroy": true,
			"order" : [[0, 'desc']],
			"ajax": {
				"url": "prospect",
				"data": {
					"project": $("#project").val(),
					"agent": $("#agent").val(),
					"sales": $("#sales").val(),
					"status": $("#status").val(),
				}
			},
			"columns": [
				// {
				//     mRender: function(data, type, row) {
				//         if (row.status == '2') {
				//             return `
				//             <span class='bg-red' style='border-left: red solid 2px;'>
				//                 ${row.status}
				//             </span>`;
				//         } else {
				//             return `<span class='bg-blue'> ${row.status}</span>`;
				//         }

				//     }
				// },
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
				{ data: 'nama_campaign' },
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
						${row.status}<br><small class="card-subtitle" style="font-size: 11px;">${row.alasan != null ? row.alasan : ''}</small>
						`
					}
				},
				{ data: 'created_at' },
				// {
				//     mRender: function(data, type, row) {
				//         return moment(row.created_at).format("d-m-y")
				//     }
				// },
				{ data: 'accept_at' },
				// {
				// 	mRender: function(data,type,row){
				// 		return `
				// 			<td class="text-center">
				// 				<div class="d-flex justify-content-start">
				// 					<a href=""><button class="btn btn-pill btn-outline-primary" style="border-radius:5px"><i class="fa fa-eye" style="color:#7366ff"></i></button></a>
				// 					<form action="" method="post" onsubmit="return confirm('Apakah anda yakin ?')">
				// 						@method('delete')
				// 						@csrf
				// 						<button class="btn btn-rounded mx-2" style="background-color: #8A0512; color :#fff;" type="submit"><i class="fa fa-trash"></i></button>
				// 					</form>
				// 					<a href=""><button class="btn btn-outline-primary history" style="background-color: #fb8c2e;color:#fff;" data-modal="" ><i class="fa fa-history"></i></button></a>
				// 				</div>
				// 			</td>
				// 		`
				// 	}
				// }
			],
			"deferRender": true,
		});
	}

	// refreshDatatable();

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
