@extends('layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/summernote/summernote-bs5.css')}}" />
@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{__('messages.about_us')}}</h4>
                    </div>
                </div>
                <div class="card-body px-0">
                    <form action="{{route('admin.business-settings.about-us')}}" method="post" id="tnc-form">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" id="summernote"  name="about_us">{!! $data['value']??null !!}</textarea>
                        </div>
                        <div class="btn-container mx-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{__('messages.submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('javascript')

<script src="{{asset('assets/vendor/summernote/summernote-bs5.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#summernote').summernote();
    });
</script>
@endpush



