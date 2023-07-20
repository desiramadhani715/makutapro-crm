@extends('layouts.simple.master')
@section('title', 'Tasks')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('style')
    <style>
        .pricing-plan {
            border: 1px solid #ecf3fa;
            border-radius: 5px;
            padding: 15px;
            position: relative;
            overflow: hidden;
        }
        .pricing-plan p {
            margin-bottom: 5px;
            color: #898989;
        }
        .pricing-plan .bg-img {
            position: absolute;
            top: 40px;
            opacity: 0.1;
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
            right: -30px;
        }
        .btn-action:hover {
            cursor: pointer;
        }
    </style>
@endsection

@section('breadcrumb-title')
<h3>Setting</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item active">Setting</li>
@endsection

@section('content')
<div class="container-fluid">
   <div class="email-wrap bookmark-wrap">
      <div class="row">
         <div class="col-xl-3 box-col-6">
            <div class="email-left-aside">
               <div class="card">
                  <div class="card-body">
                     <div class="email-app-sidebar left-bookmark task-sidebar">
                        <div class="media">
                           <div class="media-size-email"><img class="me-3 rounded-circle" src="{{ asset('assets/images/user/user.png')}}" alt=""></div>
                           <div class="media-body">
                              <h6 class="f-w-600">{{ Auth::user()->name }}</h6>
                              {{-- <p>{{ Auth::user()->email }}</p> --}}
                           </div>
                        </div>
                        <hr class="mb-0">
                        <ul class="nav main-menu" role="tablist">
                           <li class="nav-item">
                              <button class="badge-light-primary btn-block btn-mail w-100" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="me-2" data-feather="check-circle"></i>All Setting</button>
                           </li>
                           {{-- <li class="nav-item"><span class="main-title"> Views</span></li> --}}
                           {{-- <li><a id="general-tab" data-bs-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true"><span class="title"> General</span></a></li> --}}
                           <li><a id="unit-type-tab" data-bs-toggle="pill" href="#unit-type" role="tab" aria-controls="unit-type" aria-selected="true"><span class="title"> Unit Type</span></a></li>
                           <li><a class="show" id="roas-tab" data-bs-toggle="pill" href="#roas" role="tab" aria-controls="roas" aria-selected="false"><span class="title"> ROAS</span></a></li>
                           <li><a class="show" id="campaign-tab" data-bs-toggle="pill" href="#campaign" role="tab" aria-controls="campaign" aria-selected="false"><span class="title">Campaign</span></a></li>
                           <li><a class="show" id="app-logs-tab" data-bs-toggle="pill" href="#app-logs" role="tab" aria-controls="app-logs" aria-selected="false"><span class="title">App Logs</span></a></li>
                           <li>
                              <hr>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xl-9 col-md-12 box-col-12">
            <div class="email-right-aside bookmark-tabcontent">
               <div class="card email-body radius-left">
                  <div class="ps-0">
                     <div class="tab-content">
                        {{-- <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-tab">
                           <div class="card mb-0">
                              <div class="card-header d-flex">
                                 <h5 class="mb-0">General Settings</h5>
                                 <a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div>
                              <div class="card-body">
                                <div class="card-body p-0">
                                    <div class="row list-persons">
                                        <div class="col-xl-3 xl-50 col-md-3">
                                            <div class="nav nav-pills" id="v-pills-tab1" role="tablist" aria-orientation="vertical">
                                                <div class="col-sm-5 col-xl-5 col-lg-5 bg-light b-r-8">
                                                    <div class="media py-2">
                                                        <i class="fa fa-cube f-36 txt-google-plus ms-2 mt-1"></i>
                                                        <div class="media-body ms-3">
                                                        <h6 class="mb-0">Projects</h6>
                                                        <p>2</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5 col-xl-5 col-lg-5 ms-2 bg-light b-r-8">
                                                    <div class="media py-2">
                                                        <i class="fa fa-child f-36 txt-warning ms-2 mt-1"></i>
                                                        <div class="media-body ms-3">
                                                        <h6 class="mb-0">Agent</h6>
                                                        <p>10</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5 col-xl-5 col-lg-5 bg-light b-r-8 mt-3">
                                                    <div class="media py-2">
                                                        <i class="fa fa-users f-36 txt-success ms-2 mt-1"></i>
                                                        <div class="media-body ms-3">
                                                        <h6 class="mb-0">Sales</h6>
                                                        <p>18</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5 col-xl-5 col-lg-5 ms-2 bg-light b-r-8 mt-3">
                                                    <div class="media py-2">
                                                        <i class="fa fa-database f-36 txt-secondary ms-2 mt-1"></i>
                                                        <div class="media-body ms-3">
                                                        <h6 class="mb-0">Leads</h6>
                                                        <p>186</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-9 xl-50 col-md-9 p-0">
                                            <div class="pricing-plan">
                                                <p>Your Product Plan Package</p>
                                                <h5>12 MONTHS</h5>
                                                <p> Sign Up on : 20 Juli 2020</p>
                                                <p> Expire on  : 20 Juli 2021</p>
                                                <img class="bg-img" src="{{asset('assets/images/dashboard/folder1.png')}}" alt="">
                                              </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-xl-8">
                                            <div class="media mb-3">
                                                <div>
                                                    <label class="m-r-10 f-18 mb-0">Whatsapp Notification</label>
                                                    <p class="mt-0 f-12">Allow sales to get Leads Notification by WA</p>
                                                </div>
                                                <div class="media-body text-end">
                                                    <label class="switch">
                                                    <input type="checkbox" checked=""><span class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="media mb-3">
                                                <div>
                                                    <label class="m-r-10 f-18 mb-0">Email Notification</label>
                                                    <p class="mt-0 f-12">Allow sales to get Leads Notification by Email</p>
                                                </div>
                                                <div class="media-body text-end">
                                                    <label class="switch">
                                                    <input type="checkbox"><span class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="media mb-2">
                                                <div>
                                                    <label class="m-r-10 f-18 mb-0">Auto-send Account by WA</label>
                                                    <p class="mt-0 f-12">Allow sales to get Account Notification (Username and Password) CRM after created by WA.</p>
                                                </div>
                                                <div class="media-body text-end">
                                                    <label class="switch">
                                                    <input type="checkbox" ><span class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="media mb-2">
                                                <div>
                                                    <label class="m-r-10 f-18 mb-0">Auto-send Account by Email</label>
                                                    <p class="mt-0 f-12">Allow sales to get Account Notification (Username and Password) CRM after created by Email.</p>
                                                </div>
                                                <div class="media-body text-end">
                                                    <label class="switch">
                                                    <input type="checkbox" checked=""><span class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="media mb-2">
                                                <div>
                                                    <label class="m-r-10 f-18 mb-0">WA Apointment Confirmation</label>
                                                    <p class="mt-0 f-12">Settings for Automatic WhatsApp Messages to Your Clients Before Upcoming Schedule</p>
                                                </div>
                                                <div class="media-body text-end">
                                                    <label class="switch">
                                                    <input type="checkbox" ><span class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                           </div>
                        </div> --}}
                        <div class="fade tab-pane fade active show" id="unit-type" role="tabpanel" aria-labelledby="unit-type-tab">
                           <div class="card mb-0">
                              <div class="card-header d-flex">
                                 <h5 class="mb-0">Unit Type</h5>
                                 <a href="" type="button" data-bs-toggle="modal" data-bs-target="#unitTypeModalAdd"><i class="me-2 mb-1" data-feather="plus-square" onclick="createUnitType()"></i>Add</a>
                                 <div class="modal fade modal-bookmark" id="unitTypeModalAdd" tabindex="-1" role="dialog" aria-labelledby="unitTypeModalAddLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h5 class="modal-title" id="exampleModalLabel">Add Unit Type</h5>
                                             <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                             <form class="form-bookmark needs-validation" method="POST" id="unitTypeForm">
                                                @csrf
                                                <div class="row">
                                                   <div class="mb-3 mt-0 col-md-12">
                                                      <label for="task-title">Project</label>
                                                      <select name="project_id" id="project_id" class="form-control" required>
                                                        <option value="">Choose Project</option>
                                                        @foreach ($projects as $item)
                                                            <option value="{{$item->id}}">{{$item->nama_project}}</option>
                                                        @endforeach
                                                      </select>
                                                   </div>
                                                   <div class="mb-3 mt-0 col-md-12">
                                                      <label for="sub-task">Unit Name</label>
                                                      <input class="form-control" id="unit_name" type="text" required="" name="unit_name">
                                                   </div>
                                                </div>
                                                <button class="btn btn-secondary" id="Bookmark" type="submit" >Save</button>
                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                                             </form>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="card-body">
                                 <div class="card-block row">
                                    <div class="col-sm-12 col-lg-12 col-xl-12">
                                       <div class="table-responsive">
                                          <table class="table" id="unitTypeTable">
                                             <thead class="thead-dark">
                                                <tr>
                                                   <th scope="col">No</th>
                                                   <th scope="col">Unit Name</th>
                                                   <th scope="col">Project</th>
                                                   <th scope="col"></th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                @forelse ($units as $unit)
                                                   <tr>
                                                      <th scope="row">{{ $loop->iteration }}</th>
                                                      <td>{{ $unit->unit_name }}</td>
                                                      <td>{{ $unit->nama_project }}</td>
                                                      <td>
                                                        <a><i class="me-2 mb-1 btn-action" data-feather="edit-2" onclick="editUnitType({{ $unit->id }})" style="width: 16px; height: 16px;"></i></a>
                                                        <a><i class="me-2 mb-1 btn-action" data-feather="x" onclick="deleteUnitType({{ $unit->id }})" onsubmit="return confirm('Apakah anda yakin ?')" style="width: 16px; height: 16px;"></i></a>
                                                      </td>
                                                   </tr>
                                                @empty
                                                   <tr>
                                                      <th></th>
                                                      <td class="text-center">Unit Type Not Available.</td>
                                                      <td></td>
                                                   </tr>
                                                @endforelse
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="fade tab-pane" id="roas" role="tabpanel" aria-labelledby="roas-tab">
                           <div class="card mb-0">
                              <div class="card-header d-flex">
                                 <h5 class="mb-0">Return on Ad Spend</h5>
                                 <a href="" type="button" data-bs-toggle="modal" data-bs-target="#roasModal"><i class="me-2 mb-1" data-feather="plus-square" onclick="createRoas()"></i>Add</a>
                                 <div class="modal fade modal-bookmark" id="roasModal" tabindex="-1" role="dialog" aria-labelledby="roasModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <h5 class="modal-title" id="exampleModalLabel">Add ROAS</h5>
                                             <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                             <form class="form-bookmark needs-validation" method="POST" id="roasForm">
                                                @csrf
                                                <div class="row">
                                                   <div class="mb-3 mt-0 col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <select name="project_id" id="project_id" class="form-control" required>
                                                                  <option value="">Choose Project</option>
                                                                  @foreach ($projects as $item)
                                                                      <option value="{{$item->id}}">{{$item->nama_project}}</option>
                                                                  @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input class="datepicker-here form-control" name="month" type="text" data-language="en" placeholder="Month" data-min-view="months" data-view="months" data-date-format="MM yyyy">
                                                            </div>
                                                        </div>
                                                   </div>
                                                   <div class="mb-3 mt-0 col-md-12">
                                                      <label for="sub-task">Google Ads Spend</label>
                                                      <input class="form-control rupiah-input" id="google" type="text" required="" name="google">
                                                   </div>
                                                   <div class="mb-3 mt-0 col-md-12">
                                                      <label for="sub-task">Sosial Media Spend</label>
                                                      <input class="form-control rupiah-input" id="sosmed" type="text" required="" name="sosmed">
                                                   </div>
                                                   <div class="mb-3 mt-0 col-md-12">
                                                      <label for="sub-task">Detik Ads Spend</label>
                                                      <input class="form-control rupiah-input" id="detik" type="text" required="" name="detik">
                                                   </div>
                                                   <div class="mb-3 mt-0 col-md-12">
                                                      <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="sub-task">Received Budget</label>
                                                            <input class="form-control rupiah-input" id="received_budget" type="text" required="" name="received_budget">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="sub-task">Received Date</label>
                                                            <input class="datepicker-here form-control" name="received_date" type="text" data-language="en">
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-secondary" id="Bookmark" type="submit" >Save</button>
                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                                             </form>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="card-body">
                                 <div class="card-block row">
                                    <div class="col-sm-12 col-lg-12 col-xl-12">
                                       <div class="table-responsive">
                                          <table class="table" id="roasTable">
                                             <thead class="thead-dark">
                                                <tr>
                                                   <th scope="col">No.</th>
                                                   <th scope="col">Budget</th>
                                                   <th scope="col">Bulan/Tahun</th>
                                                   <th scope="col">Project</th>
                                                   <th scope="col" class="text-center">CPL <br> <small>(Cost Per Leads)</small></th>
                                                   <th scope="col" class="text-center">CPA <br> <small>(Cost Per Acquisition)</small></th>
                                                   <th scope="col" ></th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                @php
                                                   $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                                @endphp
                                                @forelse ($roas as $item)
                                                   <tr>
                                                      <th scope="row">{{ $loop->iteration }}</th>
                                                      <td>Rp. {{ number_format(($item->google + $item->sosmed + $item->detik) , 0,',','.') }}</td>
                                                      @php
                                                         if ($item->bulan != "10" and $item->bulan != "11" and $item->bulan != "12")
                                                               $idxbulan = str_replace('0','',$item->bulan);
                                                         else
                                                               $idxBulan = $item->bulan;
                                                      @endphp
                                                      <td>{{ $bulan[$item->bulan - 1] , $item->tahun }}</td>
                                                      <td>{{ $item->nama_project }}</td>
                                                      <td class="text-center">Rp. {{number_format($item->cpl,0, ',' , '.')}}</td>
                                                      <td class="text-center">Rp. {{number_format($item->cpa,0, ',' , '.')}}</td>
                                                      <td>
                                                        <a><i class="me-2 mb-1 btn-action" data-feather="edit-2" onclick="editRoas({{ $item->id }})" style="width: 16px; height: 16px;"></i></a>
                                                        <a><i class="me-2 mb-1 btn-action" data-feather="x" onclick="deleteRoas({{ $item->id }})" onsubmit="return confirm('Apakah anda yakin ?')" style="width: 16px; height: 16px;"></i></a>
                                                      </td>
                                                   </tr>
                                                @empty
                                                   <tr>
                                                      <th></th>
                                                      <td></td>
                                                      <td></td>
                                                      <td class="text-center">Roas Data Not Available.</td>
                                                      <td></td>
                                                      <td></td>
                                                   </tr>
                                                @endforelse
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="fade tab-pane" id="campaign" role="tabpanel" aria-labelledby="campaign-tab">
                           <div class="card mb-0">
                              <div class="card-header d-flex">
                                 <h5 class="mb-0">Campaign Management</h5>
                                 <a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div>
                              <div class="card-body">
                                 <div class="details-bookmark text-center"><span>No tasks found.</span></div>
                              </div>
                           </div>
                        </div>
                        <div class="fade tab-pane" id="app-logs" role="tabpanel" aria-labelledby="app-logs-tab">
                           <div class="card mb-0">
                              <div class="card-header d-flex">
                                 <h5 class="mb-0">App Logs</h5>
                                 <a href="#"><i class="me-2" data-feather="printer"></i>Print</a>
                              </div>
                              <div class="card-body">
                                 <div class="details-bookmark text-center"><span>Coming Soon.</span></div>
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
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
<script src="{{asset('assets/js/task/custom.js')}}"></script>

{{-- file manager --}}
<script src="{{asset('assets/js/icons/feather-icon/feather-icon-clipart.js')}}"></script>
<script src="{{asset('assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/typeahead-custom.js')}}"></script>

{{-- unit type script--}}
<script>
    var editMode = false;
    var unitId = null;
    // JavaScript function to submit the form and reload the table using AJAX
    function saveUnitType() {
        event.preventDefault();
        var form = $('#unitTypeForm');
        var url = '/unit-type';
        var formData = form.serialize();

        var method = editMode ? 'PUT' : 'POST';
        if (method == 'PUT') {
            url = '/unit-type/'+unitId;
        }

        $.ajax({
            type: method,
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                // On successful save, close the modal and reload the table
                $('#unitTypeModalAdd').modal('hide');
                editMode = false;
                unitId = null;
                reloadTable();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

    function resetForm() {
        // Reset the form and remove validation classes
        var form = $('#unitTypeForm');
        form[0].reset();

        // Remove Bootstrap's "was-validated" class
        form.removeClass('was-validated');

        // Remove the validation classes from each form element
        form.find('.form-control').removeClass('is-valid is-invalid');
    }

    // JavaScript function to reload the table using AJAX
    function reloadTable() {
        var table = $('#unitTypeTable');
        var url = '{{ route("unit.index") }}';

        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function (response) {
                // Check if the response has valid data
                if (response && response.data && Array.isArray(response.data)) {
                    var data = response.data;

                    // Clear the existing table rows before adding the new ones
                    table.find('tbody').empty();

                    // Loop through the data and add rows to the table
                    $.each(data, function (index, item) {
                        var newRow = $('<tr>').append(
                            $('<td>').text(index + 1),
                            $('<td>').text(item.unit_name),
                            $('<td>').text(item.nama_project),
                            $('<td>').append(
                                $('<a>').append(
                                    $('<i>').addClass('me-2 mb-1').attr({
                                        'data-feather': 'edit-2',
                                        'onclick': 'editUnitType(' + item.id + ')',
                                        'style': 'width: 16px; height: 16px;'
                                    })
                                ),
                                $('<a>').append(
                                    $('<i>').addClass('me-2 mb-1').attr({
                                        'data-feather': 'x',
                                        'onclick': 'deleteUnitType(' + item.id + ')',
                                        'style': 'width: 16px; height: 16px;'
                                    })
                                )
                            )
                        );
                        table.find('tbody').append(newRow);
                    });

                    // Reinitialize Feather icons after updating the table
                    feather.replace();

                    resetForm();
                } else {
                    console.log('Invalid or missing data in the AJAX response.');
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

    // Submit the form when it's submitted
    $('#unitTypeForm').on('submit', saveUnitType);

    function createUnitType() {
        resetForm();
        editMode = false; // Set the flag to indicate create mode
    }

    function editUnitType(id) {
        editMode = true;
        unitId = id;

        // You may also want to fetch the existing data for the unit type and populate the form fields
        $.get('/unit-type/' + id, function (data) {
            $('#project_id').val(data.data.project_id);
            $('#unit_name').val(data.data.unit_name);
        });

        // Show the modal for editing
        $('#unitTypeModalAdd').modal('show');
    }

    function deleteUnitType(id) {
        // Show a confirmation dialog to the user
        var confirmDelete = confirm('Are you sure you want to delete this unit type?');

        // If the user confirms the delete action, proceed with the AJAX request
        if (confirmDelete) {
            var url = '/unit-type/' + id;

            $.ajax({
                type: 'DELETE',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}" // Include the CSRF token in the request data
                },
                dataType: 'json',
                success: function (data) {
                    // On successful delete, reload the table
                    reloadTable();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    }

</script>

{{-- roas script--}}
<script>

    var rupiahInputs = document.querySelectorAll(".rupiah-input");

    // Attach event listener to each input element
    rupiahInputs.forEach(function(input) {
        input.addEventListener("keyup", function(e) {
            // Call the reusable formatRupiah function with this input element and prefix "Rp. "
            input.value = formatRupiah(this.value, "Rp. ");
        });
    });

    /* Reusable function formatRupiah */
    function formatRupiah(angka, prefix) {
        // The same code as in your original function remains unchanged
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? prefix + rupiah : "";
    }

    // Submit the form when it's submitted
    $('#roasForm').on('submit', saveRoas);

    var editMode = false;
    var roasId = null;
    // JavaScript function to submit the form and reload the table using AJAX
    function saveRoas() {
        event.preventDefault();
        var form = $('#roasForm');
        var url = '/roas';
        var formData = form.serialize();

        var method = editMode ? 'PUT' : 'POST';
        if (method == 'PUT') {
            url = '/roas/'+roasId;
        }

        $.ajax({
            type: method,
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                // On successful save, close the modal and reload the table
                $('#roasModal').modal('hide');
                editMode = false;
                roasId = null;
                reloadTable();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

    function resetForm() {
        // Reset the form and remove validation classes
        var form = $('#roasForm');
        form[0].reset();

        // Remove Bootstrap's "was-validated" class
        form.removeClass('was-validated');

        // Remove the validation classes from each form element
        form.find('.form-control').removeClass('is-valid is-invalid');
    }

    // JavaScript function to reload the table using AJAX
    function reloadTable() {
        var table = $('#roasTable');
        var url = '{{ route("roas.index") }}';

        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function (response) {
                // Check if the response has valid data
                if (response && response.data && Array.isArray(response.data)) {
                    var data = response.data;

                    // Clear the existing table rows before adding the new ones
                    table.find('tbody').empty();

                    // Loop through the data and add rows to the table
                    $.each(data, function (index, item) {
                        var newRow = $('<tr>').append(
                            $('<td>').text(index + 1),
                            $('<td>').text((item.google + item.sosmed + item.detik)),
                            $('<td>').text(item.bulan + item.tahun
                                ),
                            $('<td>').text(item.nama_project),
                            $('<td>').text(item.cpl),
                            $('<td>').text(item.cpa),
                            $('<td>').append(
                                $('<a>').append(
                                    $('<i>').addClass('me-2 mb-1').attr({
                                        'data-feather': 'edit-2',
                                        'onclick': 'editUnitType(' + item.id + ')',
                                        'style': 'width: 16px; height: 16px;'
                                    })
                                ),
                                $('<a>').append(
                                    $('<i>').addClass('me-2 mb-1').attr({
                                        'data-feather': 'x',
                                        'onclick': 'deleteUnitType(' + item.id + ')',
                                        'style': 'width: 16px; height: 16px;'
                                    })
                                )
                            )
                        );
                        table.find('tbody').append(newRow);
                    });

                    // Reinitialize Feather icons after updating the table
                    feather.replace();

                    resetForm();
                } else {
                    console.log('Invalid or missing data in the AJAX response.');
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }

    function createRoas() {
        resetForm();
        editMode = false; // Set the flag to indicate create mode
    }

    function editRoas(id) {
        editMode = true;
        roasId = id;

        // You may also want to fetch the existing data for the unit type and populate the form fields
        $.get('/roas/' + id, function (data) {
            $('#project_id').val(data.data.project_id);
            $('#google').val(data.data.google);
            $('#sosmed').val(data.data.sosmed);
            $('#detik').val(data.data.detik);
        });

        // Show the modal for editing
        $('#roasModal').modal('show');
    }

    function deleteUnitType(id) {
        // Show a confirmation dialog to the user
        var confirmDelete = confirm('Are you sure you want to delete this unit type?');

        // If the user confirms the delete action, proceed with the AJAX request
        if (confirmDelete) {
            var url = '/unit-type/' + id;

            $.ajax({
                type: 'DELETE',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}" // Include the CSRF token in the request data
                },
                dataType: 'json',
                success: function (data) {
                    // On successful delete, reload the table
                    reloadTable();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    }

</script>


@endsection
