@extends('layouts.simple.master')
@section('title', 'Tasks')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
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
                              <button class="badge-light-primary btn-block btn-mail w-100" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="me-2" data-feather="check-circle"></i>Add Setting</button>
                              <div class="modal fade modal-bookmark" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                 <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
                                          <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                       </div>
                                       <div class="modal-body">
                                          <form class="form-bookmark needs-validation" id="bookmark-form" novalidate="">
                                             <div class="row">
                                                <div class="mb-3 mt-0 col-md-12">
                                                   <label for="task-title">Task Title</label>
                                                   <input class="form-control" id="task-title" type="text" required="" autocomplete="off">
                                                </div>
                                                <div class="mb-3 mt-0 col-md-12">
                                                   <label for="sub-task">Sub task</label>
                                                   <input class="form-control" id="sub-task" type="text" required="" autocomplete="off">
                                                </div>
                                                <div class="mb-3 mt-0 col-md-12">
                                                   <div class="d-flex date-details">
                                                      <div class="d-inline-block">
                                                         <label class="d-block mb-0" for="chk-ani">
                                                         <input class="checkbox_animated" id="chk-ani" type="checkbox">Remind on
                                                         </label>
                                                      </div>
                                                      <div class="d-inline-block">
                                                         <input class="datepicker-here form-control" type="text" data-language="en" placeholder="Date">
                                                      </div>
                                                      <div class="d-inline-block">
                                                         <select class="form-control">
                                                            <option>7:00 am</option>
                                                            <option>7:30 am</option>
                                                            <option>8:00 am</option>
                                                            <option>8:30 am</option>
                                                            <option>9:00 am</option>
                                                            <option>9:30 am</option>
                                                            <option>10:00 am</option>
                                                            <option>10:30 am</option>
                                                            <option>11:00 am</option>
                                                            <option>11:30 am</option>
                                                            <option>12:00 pm</option>
                                                            <option>12:30 pm</option>
                                                            <option>1:00 pm</option>
                                                            <option>2:00 pm</option>
                                                            <option>3:00 pm</option>
                                                            <option>4:00 pm</option>
                                                            <option>5:00 pm</option>
                                                            <option>6:00 pm</option>
                                                         </select>
                                                      </div>
                                                      <div class="d-inline-block">
                                                         <label class="d-block mb-0" for="chk-ani1">
                                                         <input class="checkbox_animated" id="chk-ani1" type="checkbox">notification
                                                         </label>
                                                      </div>
                                                      <div class="d-inline-block">
                                                         <label class="d-block mb-0" for="chk-ani2">
                                                         <input class="checkbox_animated" id="chk-ani2" type="checkbox">Mail
                                                         </label>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="mb-3 mt-0 col-md-6">
                                                   <select class="js-example-basic-single">
                                                      <option value="task">My Task</option>
                                                   </select>
                                                </div>
                                                <div class="mb-3 mt-0 col-md-6">
                                                   <select class="js-example-disabled-results" id="bm-collection">
                                                      <option value="general">General</option>
                                                   </select>
                                                </div>
                                                <div class="mb-3 col-md-12 my-0">
                                                   <textarea class="form-control" required="" autocomplete="off">  </textarea>
                                                </div>
                                             </div>
                                             <input id="index_var" type="hidden" value="6">
                                             <button class="btn btn-secondary" id="Bookmark" onclick="submitBookMark()" type="submit">Save</button>
                                             <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </div>
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
                                 <a href="#"><i class="me-2 mb-1" data-feather="plus-square"></i>Add</a>
                                 <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
                                    <div class="modal-dialog modal-lg" role="document">
                                       <div class="modal-content"  style="border-radius: 20px;">
                                          <div class="modal-header" style="background-color: #6F9CD3; border-top-left-radius: 20px;border-top-right-radius: 20px;">
                                            <h2 class="modal-title text-white" style="font-family: Montserrat ,
                                            sans-serif Medium 500; font-size: 25px;"><strong>MAKUTA</strong> Pro</h2>
                                             <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <form action="{{route('unit-type.store')}}" method="POST" enctype="multipart/form-data">
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
                              <div class="card-body">
                                 <div class="card-block row">
                                    <div class="col-sm-12 col-lg-12 col-xl-12">
                                       <div class="table-responsive">
                                          <table class="table">
                                             <thead class="thead-dark">
                                                <tr>
                                                   <th scope="col">No</th>
                                                   <th scope="col">Unit Name</th>
                                                   <th scope="col">Project</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                @forelse ($units as $unit)
                                                   <tr>
                                                      <th scope="row">{{ $loop->iteration }}</th>
                                                      <td>{{ $unit->unit_name }}</td>
                                                      <td>{{ $unit->nama_project }}</td>
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
                                 <a href="#"><i class="me-2 mb-1" data-feather="plus-square"></i>Add</a>
                              </div>
                              <div class="card-body">
                                 <div class="card-block row">
                                    <div class="col-sm-12 col-lg-12 col-xl-12">
                                       <div class="table-responsive">
                                          <table class="table">
                                             <thead class="thead-dark">
                                                <tr>
                                                   <th scope="col">No.</th>
                                                   <th scope="col">Budget</th>
                                                   <th scope="col">Bulan/Tahun</th>
                                                   <th scope="col">Project</th>
                                                   <th scope="col" class="text-center">CPL <br> <small>(Cost Per Leads)</small></th>
                                                   <th scope="col" class="text-center">CPA <br> <small>(Cost Per Acquisition)</small></th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                @php
                                                   $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                                @endphp
                                                @forelse ($roas as $item)
                                                   <tr>
                                                      <th scope="row">{{ $loop->iteration }}</th>
                                                      <td>Rp. {{ number_format($item->budget == null ? $item->google + $item->sosmed + $item->detik : $item->budget, 0,',','.') }}</td>
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

<script>
    document.getElementById("unitTypeForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission

    // Get the input values
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;

    // Create a new table row
    var row = document.createElement("tr");

    // Create the row cells
    var idCell = document.createElement("td");
    var nameCell = document.createElement("td");
    var emailCell = document.createElement("td");
    var actionsCell = document.createElement("td");

    // Set the cell values
    idCell.textContent = generateUniqueId(); // Replace this with the actual ID
    nameCell.textContent = name;
    emailCell.textContent = email;
    actionsCell.innerHTML = '<button onclick="editRow(this)">Edit</button> <button onclick="deleteRow(this)">Delete</button>';

    // Append cells to the row
    row.appendChild(idCell);
    row.appendChild(nameCell);
    row.appendChild(emailCell);
    row.appendChild(actionsCell);

    // Append the row to the table
    document.getElementById("myTable").getElementsByTagName('tbody')[0].appendChild(row);

    // Clear the form inputs
    document.getElementById("name").value = '';
    document.getElementById("email").value = '';

    // Store the data in the database
    storeData(name, email);
});

function editRow(button) {
    var row = button.parentNode.parentNode;

    // Get the cell values
    var id = row.cells[0].textContent;
    var name = row.cells[1].textContent;
    var email = row.cells[2].textContent;

    // Populate the form with the selected row's data
    document.getElementById("name").value = name;
    document.getElementById("email").value = email;

    // Remove the row from the table
    row.parentNode.removeChild(row);
}

    function deleteRow(button) {
        var row = button.parentNode.parentNode;

        // Remove the row from the table
        row.parentNode.removeChild(row);
    }

    function generateUniqueId() {
        // Generate a unique ID here (e.g., using a UUID library)
        // Replace this with your actual implementation or use a backend-generated ID
        return Math.random().toString(36).substr(2, 9);
    }

    function storeData(name, email) {
        var data = {
            name: name,
            email: email
        };

        fetch('/store-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            console.log(result);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endsection
