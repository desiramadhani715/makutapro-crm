@extends('SM.layouts.simple.master')
@section('title', 'Project List')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Project Create</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Prospect</li>
<li class="breadcrumb-item active">Prospect Detail</li>
@endsection

@section('content')
<div class="container-fluid">
<form action="{{route('sm.prospect.update',$data->id)}}" method="POST" role="form">
    @method('PUT')
    @csrf
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
            <h5>Prospect Source</h5>
            <span>Source of this Prospect</span>
        </div>
        <div class="card-body">
          <div class="form theme-form">
            <div class="row">
              <div class="col-sm-4">
                <div class="mb-3">
                  <label>Project</label>
                  <input class="form-control" type="text" value="{{$data->nama_project}}" disabled>
                  <input type="hidden" name="project_id" value="{{$data->project_id}}">
                </div>
              </div>
              <div class="col-sm-4">
                <div class="mb-3">
                  <label>Agent</label>
                  <input class="form-control" type="text" value="{{$data->nama_agent}}" disabled>
                  <input type="hidden" name="agent_id" value="{{$data->agent_id}}">
                </div>
              </div>
              <div class="col-sm-4">
                <div class="mb-3">
                  <label>Sales</label>
                  <input class="form-control" type="text" value="{{$data->nama_sales}}" disabled>
                  <input type="hidden" name="sales_id" value="{{$data->sales_id}}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <div class="mb-3">
                  <label>Platform</label>
                  <select class="form-select" name="sumber_platform_id">
                    <option value="{{$data->sumber_platform_id}}">{{$data->nama_platform ? $data->nama_platform : "Choose Platform"}}</option>
                    @foreach ($data->platform as $item)
                    <option value="{{$item->id}}">{{$item->nama_platform}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="mb-3">
                  <label>Source</label>
                  <select class="form-select" name="sumber_data_id">
                    <option value="{{$data->sumber_data_id}}">{{$data->nama_sumber ? $data->nama_sumber : "Choose Source"}}</option>
                    @foreach ($data->source as $item)
                    <option value="{{$item->id}}">{{$item->nama_sumber}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="mb-3">
                  <label>Full path Referal</label>
                  <input class="form-control" type="text" value="{{$data->full_path_ref}}" name="full_path_ref">
                </div>
              </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="mb-3">
                      <label>Utm Source</label>
                      <input class="form-control" type="text" value="{{$data->utm_source}}" name="utm_source">
                    </div>
                  </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                      <label>Utm Medium</label>
                      <input class="form-control" type="text" value="{{$data->utm_medium}}" name="utm_medium">
                    </div>
                  </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                      <label>Utm Campaign</label>
                      <input class="form-control" type="text" value="{{$data->utm_campaign}}" name="utm_campaign">
                    </div>
                  </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Personal Data</h5>
                <span>For change data of prospect</span>
            </div>
            <div class="card-body">
                <div class="form theme-form">
                  <div class="row">
                      <div class="col-sm-4">
                      <div class="mb-3">
                          <label>Nama</label>
                          <input class="form-control" type="text" value="{{$data->nama_prospect}}" name="nama_prospect">
                      </div>
                      </div>
                      <div class="col-sm-4">
                      <div class="mb-3 m-form__group">
                          <label>Phone Number</label>
                          <div class="input-group mb-3">
                              <div class="input-group-prepend col-5 py-2">
                                  <select class="js-example-disabled-results form-control" name="kode_negara">
                                      <option value="{{$data->kode_negara}}">{{$data->kode_negara}} - {{$data->country}}</option>
                                      @foreach ($data->countries as $item)
                                      <option value="{{$item->calling_code}}">{{$item->calling_code}} - {{$item->country}}</option>
                                      @endforeach
                                  </select>
                              </div>
                              <input class="col-7 form-control" type="number" value="{{$data->hp}}" name="hp">
                          </div>
                      </div>
                      </div>
                      <div class="col-sm-4">
                      <div class="mb-3">
                          <label>Email</label>
                          <input class="form-control" type="email" placeholder="Email" value="{{$data->email}}" name="email">
                      </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-4">
                      <div class="mb-3">
                          <label>Gender</label>
                          <select class="form-select" name="gender_id">
                              <option value="{{$data->gender_id}}">{{$data->jenis_kelamin ? $data->jenis_kelamin : "Choose Gender"}}</option>
                              @foreach ($data->gender as $item)
                              <option value="{{$item->id}}">{{$item->jenis_kelamin}}</option>
                              @endforeach
                          </select>
                      </div>
                      </div>
                      <div class="col-sm-4">
                          <div class="mb-3">
                              <label>Usia</label>
                              <select class="form-select" name="usia_id">
                                <option value="{{$data->usia_id}}">{{$data->range_usia ? $data->range_usia." Tahun" : "Choose Age"}}</option>
                                @foreach ($data->usia as $item)
                                <option value="{{$item->id}}">{{$item->range_usia}} Tahun</option>
                                @endforeach
                              </select>
                          </div>
                      </div>
                      <div class="col-sm-4">
                      <div class="mb-3">
                          <label>Pekerjaan</label>
                          <select class="form-select" name="pekerjaan_id">
                            <option value="{{$data->pekerjaan_id}}">{{$data->tipe_pekerjaan ? $data->tipe_pekerjaan : "Choose Job"}}</option>
                            @foreach ($data->pekerjaan as $item)
                            <option value="{{$item->id}}">{{$item->tipe_pekerjaan}} Tahun</option>
                            @endforeach
                          </select>
                      </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-4">
                      <div class="mb-3">
                          <label>Penghasilan</label>
                          <select class="form-select" name="penghasilan_id">
                            <option value="{{$data->penghasilan_id}}">{{$data->range_penghasilan ? $data->range_penghasilan : "Choose Range"}}</option>
                            @foreach ($data->penghasilan as $item)
                            <option value="{{$item->id}}">{{$item->range_penghasilan}} Tahun</option>
                            @endforeach
                          </select>
                      </div>
                      </div>
                      <div class="col-sm-4 mb-3">
                          <label>Domisili Tinggal</label>
                          <div class="d-flex justify-content-between">
                            <select class="js-example-disabled-results form-control" id="domisili_prov">
                                <option>{{$data->domisili_prov ? $data->domisili_prov : "Choose Province"}}</option>
                                @foreach ($data->provinces as $item)
                                <option value="{{$item->id}}">{{$item->province}}</option>
                                @endforeach
                            </select>
                            <select class="js-example-disabled-results form-control" name="domisili_id" id="domisili_id">
                                <option value="{{$data->domisili_id}}">{{$data->domisili_city ? $data->domisili_city : "Choose City"}}</option>
                            </select>
                          </div>
                      </div>
                      <div class="col-sm-4 mb-3">
                        <label>Domisili Kerja</label>
                        <div class="d-flex justify-content-between">
                          <select class="js-example-disabled-results form-control" id="work_prov">
                              <option>{{$data->work_prov ? $data->work_prov : "Choose Province"}}</option>
                              @foreach ($data->provinces as $item)
                              <option value="{{$item->id}}">{{$item->province}}</option>
                              @endforeach
                          </select>
                          <select class="js-example-disabled-results form-control" name="tempat_kerja_id" id="work_id">
                              <option value="{{$data->tempat_kerja_id}}">{{$data->work_city ? $data->work_city : "Choose City"}}</option>
                          </select>
                        </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col">
                          <div class="mb-3">
                            <label>Pesan</label>
                            <textarea class="form-control" rows="3" name="message">{{$data->message}}</textarea>
                          </div>
                        </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                <h5>Status</h5>
                <span>{{$data->last_updated_status ? "Last Updated by ".$data->last_updated_status : "Change Status of prospect"}}</span>
            </div>
            <div class="card-body pt-4">
                <div class="row">
                  <div class="col">
                    <div class="mb-3">
                        <label>Status</label>
                        <select class="form-select" name="status_id" id="status_id">
                          <option value="{{$data->status_id}}">{{$data->status ? $data->status : "Choose Status"}}</option>
                          @foreach ($data->statuslist as $item)
                          <option value="{{$item->id}}">{{$item->status}}</option>
                          @endforeach
                        </select>
                    </div>
                  </div>
                </div>
                <div id="reason" style="display: {{$data->status_id !=5 && $data->status_id != 1 && $data->status_id != 7 ? "block" : "none"}}">
                  <div class="row">
                    <div class="col">
                      <div class="mb-3">
                          <label>Reason</label>
                          <select class="form-select" name="standard_id" id="standard_id">
                            <option value="{{$data->standard_id}}">{{$data->reason ? $data->reason : "Choose Reason"}}</option>
                            @foreach ($data->reasons as $item)
                                <option value="{{$item->id}}">{{$item->alasan}}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="mb-3">
                          <label>Note</label>
                          <textarea class="form-control" rows="5" name="note_standard">{{$data->note_standard ? $data->note_standard : ""}}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="closing" style="display: {{$data->status_id ==5 ? "block" : "none"}}">
                  <div class="row">
                    <div class="col">
                      <div class="mb-3">
                          <label>Unit Type</label>
                          <select class="form-select" name="unit_id">
                            <option value="{{$data->unit_id}}">{{$data->unit_name ? $data->unit_name : "Choose Type"}}</option>
                            @foreach ($data->units as $item)
                            <option value="{{$item->id}}">{{$item->unit_name}}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="mb-3">
                          <label>Keterangan Unit</label>
                          <input type="text" class="form-control" name="ket_unit" value="{{$data->ket_unit}}">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="mb-3">
                          <label>Harga Jual</label>
                          <input type="text" class="form-control" name="closing_amount" id="rupiah" value="Rp. {{number_format($data->closing_amount,0, ',' , '.')}}">
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header pb-2">
                <h5>Note</h5>
                <span>Note From Admin</span>
            </div>
            <div class="card-body pt-4">
                <textarea class="form-control" rows="5" name="catatan_admin">{{$data->catatan_admin}}</textarea>
            </div>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
          <div class="card-body">
            <div class="row py-0 my-0">
              <div class="col-sm-12 text-end">
                <button class="btn btn-success" type="submit">Save Changes</button>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</form>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/js/dropzone/dropzone-script.js')}}"></script>
<script src="{{asset('assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/typeahead-custom.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
@endsection
