@extends('SM.layouts.simple.master')
@section('title', 'Project List')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Project Create</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Prospect</li>
<li class="breadcrumb-item active">Prospect Create</li>
@endsection

@section('content')
<div class="container-fluid">
  <form action="{{route('sm.prospect.store')}}" method="POST" role="form">
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
                    <label>Sales <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                    <select name="sales_id" id="sales" class="form-control" required>
                      <option value="">Choose Sales</option>
                      @foreach ($data->sales as $sales)
                      <option value="{{ $sales->id }}">{{ $sales->nama_sales }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                      <label>Full path Referal <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                      <input class="form-control" type="text"  name="full_path_ref" value="{{old('full_path_ref')}}" placeholder="Referal Link (optional)">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                      <label>Campaign <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                      <select class="form-select" name="campaign_id" id="campaign">
                        <option value="">Choose Campaign</option>
                      </select>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>Platform <sup>*</sup></label>
                    <select class="form-select" name="sumber_platform_id" required>
                      <option value="">Choose Platform</option>
                      @foreach ($data->platform as $item)
                          <option value="{{$item->id}}" {{old('sumber_platform_id') == $item->id ? 'selected' : ''}}>{{$item->nama_platform}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>Source <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                    <select class="form-select" name="sumber_data_id">
                      <option value="">Choose Source</option>
                      @foreach ($data->source as $item)
                          <option value="{{$item->id}}" {{old('sumber_data_id') == $item->id ? 'selected' : ''}}>{{$item->nama_sumber}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="mb-3">
                    <label>Source Note <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                    <input class="form-control" type="text"  name="note_sumber_data" value="{{old('note_sumber_data')}}" placeholder="Source Note (optional)">
                  </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-sm-4">
                      <div class="mb-3">
                        <label>Utm Source <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                        <input class="form-control" type="text" name="utm_source" value="{{old('utm_source')}}" placeholder="Utm Source (optional)">
                      </div>
                    </div>
                  <div class="col-sm-4">
                      <div class="mb-3">
                        <label>Utm Medium <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                        <input class="form-control" type="text" name="utm_medium" value="{{old('utm_medium')}}" placeholder="Utm Medium (optional)">
                      </div>
                    </div>
                  <div class="col-sm-4">
                      <div class="mb-3">
                        <label>Utm Campaign <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                        <input class="form-control" type="text" name="utm_campaign" value="{{old('utm_campaign')}}" placeholder="Utm Campaign (optional)">
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
                            <label>Nama <sup>*</sup></label>
                            <input class="form-control" type="text" name="nama_prospect" required value="{{old('nama_prospect')}}" placeholder="Nama">
                        </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="mb-3 m-form__group">
                              <label>Phone Number <sup>*</sup></label>
                              <div class="input-group mb-3">
                                  <div class="input-group-prepend col-5">
                                      <select class="js-example-disabled-results form-control" name="kode_negara" required>
                                        <option value="+62">+62 - Indonesia</option>
                                          @foreach ($data->countries as $item)
                                          <option value="{{$item->calling_code}}" {{old('kode_negara') == $item->calling_code ? 'selected' : ''}}>{{$item->calling_code}} - {{$item->country}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <input class="col-7 form-control" type="number"  name="hp" id="hp" placeholder="0812xxxxx" required oninput="cekHp()" value="{{old('hp')}}">
                                  <div class="pesan"></div>
                                  @if(session('alert_hp'))
                                  <p class="text-danger"> Nomor hp <b>{{old('hp')}}</b> sudah terdaftar ..! </p>
                                  @endif
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-4">
                        <div class="mb-3">
                            <label>Email <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                            <input class="form-control" type="email" placeholder="Email" name="email" value="{{old('email')}}">
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                              <label>Pesan <sup style="color: rgba(43, 43, 43, 0.7);font-size: 9px;">* <i>optional</i></sup></label>
                              <textarea class="form-control" rows="3" name="message"  value="{{old('message')}}">{{old('message')}}</textarea>
                            </div>
                          </div>
                    </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col mb-4 d-flex justify-content-end">
        <div><button class="btn btn-success me-3" type="submit" id="add">Add</button><a class="btn btn-danger" href="{{route('sm.prospect.index')}}">Cancel</a></div>
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
<script src="{{asset('assets/js/form-validation-custom.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
@endsection
