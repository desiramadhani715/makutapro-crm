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
<h3>Banner Edit</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Project</li>
<li class="breadcrumb-item">{{ $project_name->nama_project }}</li>
<li class="breadcrumb-item active">Banner Edit</li>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">
          <div class="form theme-form">

            <form id="form-banner" action="{{ route('project.banner.update', ['id_project' => $id_project, 'id_banner' => $banner->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col mb-3">
                        <label for="title">Title</label>
                        <input class="form-control" id="title" type="text" required="" name="title" value="{{ @old('title') ? @old('title') : $banner->title }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="subtitle">Subtitle</label>
                        <input class="form-control" id="subtitle" type="text" name="subtitle" value="{{ @old('subtitle') ? @old('subtitle') : $banner->subtitle }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    <div class="mb-3">
                        <label>Banner</label>
                        <div class="preview-image">
                            <img src="{{ asset('storage/banner/'.$banner->banner) }}" alt="">
                        </div>
                        <input class="form-control" type="file" id="banner" name="banner">
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="validationCustom02">Description</label>
                            <input type="hidden" id="description" name="description">
                            <div id="editor">
                                {!! $banner->description !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <button class="btn btn-success mt-2" type="submit">Save</button>
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

        previewContainer.innerHTML = '';

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
