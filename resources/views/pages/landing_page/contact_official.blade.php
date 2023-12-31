@extends('layouts.main')

@section('page-style')
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/datemultiselect/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-script')
    <script src="{{env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
    <script src="{{ env('STORAGE_URL_VIEW') }}{{('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script>
        $('.selectpicker').selectpicker();
    </script>
    <script src="https://cdn.tiny.cloud/1/oowsi03408mi3se06e6g73ocmflkdn4blz5jffod9wz1lc1t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
      });
      
      function readURL(input, level) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          var fileimport = $('#' + input.id).val();
          var allowedExtensions = /(\.png|\.jpg|\.jpeg)$/i; // Mengizinkan PNG, JPG, dan JPEG
          if (!allowedExtensions.exec(fileimport)) {
            alert('Gambar harus bertipe gambar');
            $('#' + input.id).val('');
            return false;
          }
          reader.onload = function(e) {
            $('#blah_' + level).attr('src', e.target.result).width(200);
            // .height();
          };
          reader.readAsDataURL(input.files[0]);
        }
      }

      function imgError(data) {
        console.log('error_img');
        data.setAttribute('src', '{{ asset("images/logo.svg") }}');
      }

      const main = {
        working_add: function(){
            let id = this.random_number();
            let html = `
            <div class="row row_${id}">
                <div class="col-md-10">
                    <input type="text" class="form-control" name="working_hour[]" placeholder="Working Hour" required value="">
                </div>
                <div class="col-md-2">
                    <a class="btn btn-danger" data-id="${id}" onclick="main.working_delete(this)"><li class="fa fa-trash"></li></a>
                </div>
                <div class="col-md-12"><br></div>
            </div>
            `
            $('.pluss').append(html);
        },
        working_delete: function(that){
            let id = $(that).data('id');
            $(`.row_${id}`).remove();
        },
        random_number: function(){
            var min = 100;
            var max = 999;         
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }
      }

    </script>
@endsection

@section('content')
    
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Landing Page</span>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <strong>Official Partner</strong>
            </li>
        </ul>
    </div><br>

    @include('layouts.notifications')

    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <span class="caption-subject font-blue bold uppercase">Official Partner</span>
            </div>
        </div>

        <div class="portlet-body m-form__group row">
            <form class="form-horizontal" role="form" action="{{ route('landing_page.contact_official.update') }}"  method="post" enctype="multipart/form-data" id="myForm">
                <div class="col-md-12">
                    <div class="form-body">

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">WhatsApp<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="WhatsApp" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="hidden" class="form-control" name="id_whatsapp" placeholder="WhatsApp" required value="{{ $detail['contact_official'][0]['id'] ?? '' }}">
                                        <input type="text" class="form-control" name="whatsapp" placeholder="WhatsApp" required value="{{ json_decode($detail['contact_official'][0]['official_value']) ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">Working Hour<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="Working Hour" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        
                                        <input type="hidden" class="form-control" name="id_working_hour" placeholder="Working Hour" required value="{{ json_decode($detail['contact_official'][1]['id']) ?? '' }}">

                                        @foreach(json_decode($detail['contact_official'][1]['official_value']) as $key)
                                            <div class="row row_{{ $loop->index }}">
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="working_hour[]" placeholder="Working Hour" required value="{{ $key ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <a class="btn btn-danger" data-id="{{ $loop->index }}" onclick="main.working_delete(this)"><li class="fa fa-trash"></li></a>
                                                </div>
                                                <div class="col-md-12"><br></div>
                                            </div>
                                        @endforeach
                                        <div class="pluss"></div>
                                        <a class="btn btn-primary" onclick="main.working_add()"><li class="fa fa-plus"></li></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($detail['contact_sosial_media'] as $key)
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label class="control-label">{{ $key['type'] }}<span class="required" aria-required="true">*</span>
                                        <i class="fa fa-question-circle tooltips" data-original-title="{{ $key['type'] }}" data-container="body"></i>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="col-md-10">
                                        <input type="hidden" name="detail_id[]" value="{{ $key['id'] ?? '' }}">
                                        <input type="text" name="link[]" placeholder="Link" class="form-control" required value="{{ $key['link'] ?? '' }}">
                                        <Br>
                                        <input type="text" class="form-control" name="username[]" placeholder="Username" required value="{{ $key['username'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                    <div class="form-actions">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <hr style="width:95%; margin-left:auto; margin-right:auto; margin-bottom:20px">
                            </div>
                            <div class="col-md-offset-5 col-md-2 text-center">
                                <button type="submit" class="btn yellow btn-block"><i class="fa fa-check"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
