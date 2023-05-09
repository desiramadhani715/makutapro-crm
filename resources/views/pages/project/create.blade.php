@extends('layouts.simple.master')
@section('title', 'Project List')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
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
<h3>Project Create</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Project</li>
<li class="breadcrumb-item active">Project Create</li>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <div class="form theme-form">
            <form action="{{route('project.store')}}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label>Project Title</label>
                    <input class="form-control" type="text" placeholder="Project name *" name="nama_project" required>
                  </div>
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
                  <div class="mb-3">
                    <label>Description</label>
                    <textarea class="form-control" rows="3" name="description"></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label>Send By</label>
                    <select class="form-select" name="send_by">
                      <option value="sales">Sales</option>
                      <option value="agent">Agent</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div>
                    <button class="btn btn-success sweet-8" type="submit">Save</button>
                    <a class="btn btn-danger" href="{{route('project.index')}}">Cancel</a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
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