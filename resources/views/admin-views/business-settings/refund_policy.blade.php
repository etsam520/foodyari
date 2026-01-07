@extends('layouts.dashboard-main')
@push('css')
<link rel="stylesheet" href="{{asset('assets/vendor/summernote/summernote-bs5.css')}}" />
@endpush
@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header ">
                    <div class="header-title d-flex justify-content-between">
                        <h4 class="card-title">{{__('messages.refund_policy')}}</h4>
                        <div class="d-flex flex-wrap justify-content-end">
                            <div class="form-check form-switch form-check-inline">
                                <label class="form-check-label pl-2" for="data_status"> {{ __('messages.Status') }}</label>
                                <input class="form-check-input form-check-inline" type="checkbox" id="data_status" {{$data?($data['status']==1?'checked':''):''}} />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0">
                    <form action="{{route('admin.business-settings.refund-policy')}}" method="post" id="tnc-form">
                        @csrf
                        <div class="form-group">
                            <textarea class="summernote form-control" id="summernote" name="refund_policy">{!! $data['data']??null!!}</textarea>
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

    $(document).ready(function () {
            $('body').on('change','#data_status', function(){
            // var id = $(this).attr('data-id');
            if(this.checked){
            var status = 1;
            }else{
            var status = 0;
            }
        url= '{{ url('admin/business-settings/pages/refund-policy') }}/'+status;
        $.ajax({
            url: url,
            method: 'get',
            success: function(result) {
                toastr.success('{{ __('messages.status updated!') }}', {
                CloseButton: true,
                ProgressBar: true
                });
            }
        });

        });
    });
</script>
@endpush

