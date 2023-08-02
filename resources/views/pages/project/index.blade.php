@extends('layouts.simple.master')
@section('title', 'Project List')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css')}}">
@endsection

@section('style')
<style>

   #project:hover{
      transform: scale(1.1)
   }
</style>
@endsection

@section('breadcrumb-title')
<h3>Project List</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Project</li>
<li class="breadcrumb-item active">Project List</li>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row project-cards">
      <div class="col-12 d-flex flex-row-reverse mb-3">
          <a class="btn btn-primary d-flex justify-content-center" href="{{ route('project.create') }}">Create New Project</a>
      </div>

      <div class="col-sm-12">
         <div class="card">
            <div class="card-header">
                <h5>Project</h5>
                <span>All projects include <code>active</code> and <code>nonactive</code> project</span>
            </div>
             <div class="card-body">
               <div class="tab-content" id="top-tabContent">
                  <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                     <div class="row">
                        @forelse ($data as $item)
                        <div class="col-xxl-4 col-lg-6">
                           <a href="{{route('project.show',$item->id)}}" style="color:black">
                              <div class="project-box shadow shadow-showcase" id="project">
                                 @if ($item->active == 1)
                                 <span class="badge badge-primary">Doing</span>
                                 @else
                                 <span class="badge badge-success">Done</span>
                                 @endif
                                 <h6 class="mt-2">{{$item->nama_project}}</h6>
                                 <div class="media">
                                    <img class="img-20 me-1 rounded-circle" src="{{asset('assets/images/user/3.jpg')}}" alt="" data-original-title="" title="">
                                    <div class="media-body">
                                       <p>Pic : {{$item->pic == null ? '-' : $item->pic}}</p>
                                    </div>
                                 </div>
                                 <p>{{$item->description}}</p>
                                 <div class="row details">
                                    <div class="col-6"><span>New </span></div>
                                    <div class="col-6 {{$item->active == 1 ? 'text-primary' : 'text-success'}}">{{$item->new}} </div>
                                    <div class="col-6"> <span>Process</span></div>
                                    <div class="col-6 {{$item->active == 1 ? 'text-primary' : 'text-success'}}">{{$item->process}}</div>
                                    <div class="col-6"> <span>Closing</span></div>
                                    <div class="col-6 {{$item->active == 1 ? 'text-primary' : 'text-success'}}">{{$item->closing}}</div>
                                    <div class="col-6"> <span>Not Interested</span></div>
                                    <div class="col-6 {{$item->active == 1 ? 'text-primary' : 'text-success'}}">{{$item->notinterested}}</div>
                                 </div>
                                 <div class="project-status mt-4">
                                    <div class="media mb-0">
                                       <div class="media-body text-end"><span>Done</span></div>
                                    </div>
                                    @if ($item->active == 1)
                                    <div class="progress" style="height: 5px">
                                       <div class="progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: 70%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    @else
                                    <div class="progress" style="height: 5px">
                                       <div class="progress-bar-animated bg-success" role="progressbar" style="width: 100%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                     </div>
                                    @endif
                                 </div>
                              </div>
                           </a>
                        </div>
                        @empty
                            <p class="text-center">Project Not Available, Create one!</p>
                        @endforelse
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
<script src="{{asset('assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/typeahead-custom.js')}}"></script>
<script src="{{asset('assets/js/modal-animated.js')}}"></script>

<script>

</script>
@endsection
