@extends('layouts.master')
@section('title',$user->name)

@section('nav')
<div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $user->name }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('users') }}">{{ __('users.users') }}</a>
                </li>
              <li class="breadcrumb-item">
                  <a href="{{ route('roles') }}">{{ __('users.roles') }}</a>
                </li>
              <li class="breadcrumb-item active">
                    <a href="{{ route('permissions') }}">{{ __('users.permissions') }}</a>
              </li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
@endsection
@section('content')
<div class="card card-primary card-outline">
        <div class="card-header">
            {{ __('users.about') }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                        <div class="card p-0">
                                <div class="card-header">
                                    {{ __('users.profile-picture') }}
                                </div>
                                <div class="card-body text-center">
                                    <div class="cropU">
                                        <div id="placeholder" dataID="{{ $user->id }}">
                                            <img src="{{ $user->avatar }}" class="img-thumbnail rounded-circle" />
                                        </div>
                                        <label class="btn btn-primary mt-2">
                                            {{ __('users.browse') }} <input type="file" class="custom-file-input upload_image" name="upload_image" id="upload_image" accept="image/*" hidden>
                                        </label>

                                    </div>

                                </div>
                            </div>
                </div>
                <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                {{ __('users.details') }}
                            </div>
                            <div class="card-body">
                                    <ul class="list-group">
                                            <li class="list-group-item">{{ $user->name }}</li>
                                            <li class="list-group-item">{{ $user->email }}</li>
                                            <li class="list-group-item">{{ $user->created_at->diffForHumans() }}</li>

                                        </ul>
                            </div>
                        </div>
                </div>
            </div>
        </div>
</div>


<!-- The Modal -->
<div id="uploadimageModal" class="modal" role="dialog">
		<div class="modal-dialog" style="max-width: : 400px !important;">
			<div class="modal-content">
				<div class="modal-header">
					 <h4 class="modal-title">{{ __('users.upload-crope') }}</h4>
        			<button type="button" class="close" data-dismiss="modal">&times;</button>

				</div>
				<div class="modal-body">

						<div class="text-center" style="">
							<div id="image_demo" style="width:320px; margin: 24px auto !important;"></div>
						</div>


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success crop_image" data-dismiss="modal">{{ __('users.upload-crope') }}</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">
                        {{ __('users.close') }}
                    </button>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('css')
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.min.css">
@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.4/croppie.min.js"></script>
<script src="{{ asset('assets/cropConfig.js') }}"></script>
<script>
     $(document).ready(function(){
        //console.log($('#placeholder').attr('dataID'));

            $('#upload_image').on('change', function(){
                canvasArea(this,'#placeholder');

            });

        });
</script>
@endsection
