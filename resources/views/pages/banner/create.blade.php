@extends('layouts.simple.master')
@section('title', 'Project List')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/quill.core.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/quill.snow.css') }}">
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
<h3>Banner Create</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Project</li>
<li class="breadcrumb-item">{{ $project_name->nama_project }}</li>
<li class="breadcrumb-item active">Banner Create</li>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <div class="form theme-form">
            
            <form id="form-banner" action="{{ route('project.banner.store', ['id_project' => $id_project]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col mb-3">
                        <label for="title">Title</label>
                        <input class="form-control" id="title" type="text" required="" name="title" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="subtitle">Subtitle</label>
                        <input class="form-control" id="subtitle" type="text" required="" name="subtitle" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    <div class="mb-3">
                        <label>Banner</label>
                        <div class="preview-image"></div>
                        <input class="form-control" type="file" id="banner" name="banner" required>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="validationCustom02">Description</label>
                            <input type="hidden" id="description" name="description">
                            <div id="editor">
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary mt-2" type="submit">Create</button>
                        <a href="{{ route('project.show', $id_project) }}" class="btn btn-secondary mt-2" type="submit">Cancel</a>
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
<script src="{{ asset('assets/js/editor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/editor/quill/quill.config.js') }}"></script>

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

    $(function () {
        $('#form-banner').on('submit', function () {
            let content = quill.root.innerHTML;
            
            $('#description').val(content);
        });
    });
  </script>
@endsection