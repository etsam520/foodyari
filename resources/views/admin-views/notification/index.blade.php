@extends('layouts.dashboard-main')




@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header ">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h3 class="page-header-title text-capitalize mt-4">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            {{-- <img src="{{asset('/public/assets/admin/img/bell.png')}}" alt="public"> --}}
                            <i class="fa fa-bell"></i>
                        </div>
                        <span>
                            {{__('messages.push')." ".__('messages.notification')}}
                        </span>
                    </h3>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card mb-3">
            <div class="card-body">
                @error('notification_title')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('zone')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('target')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('description')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('image')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('tergatClient')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <form action="{{route('admin.notification.store')}}" method="post" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.title')}}</label>
                                <input id="notification_title" type="text" name="notification_title" class="form-control" placeholder="{{ __('Notification Title') }}" required maxlength="191">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.zone')}}</label>
                                <select id="zone" name="zone" class="form-control" >
                                    <option value="all">{{__('messages.all')}}</option>
                                    @foreach(\App\Models\Zone::orderBy('name')->get() as $z)
                                        <option value="{{$z['id']}}">{{$z['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="target">{{__('messages.send_to')}}</label>

                                <select name="target" class="form-control" id="targetCategory" data-placeholder="{{ __('messages.Ex :') }} contact@company.com" required>
                                    <option value="customer">{{__('messages.customer')}}</option>
                                    <option value="deliveryman">{{__('messages.deliveryman')}}</option>
                                    <option value="restaurant">{{__('messages.restaurant')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" style="display: none"  id="targetClient">
                            <div class="form-group">
                                <label class="input-label" for="tergat">{{__('messages.send_to')}}</label>

                                <select name="tergat" class="form-control" data-placeholder="{{ __('messages.Ex :') }} contact@company.com" required>
                                    <option value="customer">{{__('messages.customer')}}</option>
                                    <option value="deliveryman">{{__('messages.deliveryman')}}</option>
                                    <option value="restaurant">{{__('messages.restaurant')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <center class="mb-3">
                                    <img class="initial-30" id="viewer"
                                        src="{{asset('assets/images/icons/img1.png')}}" alt="image"/>
                                </center>

                                <label>{{__('messages.notification')}} {{__('messages.banner')}}</label><small class="text-danger">* ( {{__('messages.ratio')}} 3:1 )</small>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileEg1" class="form-control"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.description')}}</label>
                                <textarea id="description" name="description" class="form-control h--md-200px" placeholder="{{ __('Write here ') }}" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end mb-0">
                        <button type="button" id="reset_btn" class="btn btn-reset">{{__('messages.reset')}}</button>
                        <button type="submit" id="submit" class="btn btn-primary">{{__('messages.send')}} {{__('messages.notification')}}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Start Table -->
        <div class="card">
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                    <h3 class="card-title">{{__('messages.notification')}} {{__('messages.list')}}
                        <span class="badge bg-soft-primary ml-2">{{$notifications->total()}}</span>
                    </h3>
                    <span class="float-end"><a href="{{route('admin.notification.clear-data')}}" class="btn btn-sm btn-info" >Clear Data</a> </span>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>{{__('sl')}}</th>
                            <th class="w-20p">{{__('messages.title')}}</th>
                            <th>{{__('messages.description')}}</th>
                            <th>{{__('messages.image')}}</th>
                            <th class="w-08p">{{__('messages.zone')}}</th>
                            <th>{{__('messages.tergat')}}</th>
                            <th>{{__('Target Client')}}</th>

                            <th class="text-center w-12p">{{__('messages.action')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($notifications as $notification)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$notification->title}}</td>
                                <td>{{$notification->description}}</td>
                                <td>
                                    @if($notification->file != null)
                                    <img src="{{asset('uploads/notifications/'.$notification->file)}}" alt="image" class="avatar-img rounded">
                                    @endif
                                </td>
                                @php
                                $targetzone = json_decode($notification->targetZone,true);
                                 @endphp
                                <td>
                                    @if($targetzone == "all")
                                        All
                                    @else
                                    @php($zone = \App\Models\Zone::find($targetzone))
                                    {{$zone->name}}
                                    @endif
                                </td>
                                <td>{{$notification->target}}</td>
                                <td>
                                    <?php
                                        $targetClient = json_decode($notification->targetClient,true);
                                    ?>
                                    @if (is_array($targetClient) &&  !in_array("all", $targetClient))
                                    <?php
                                        $targetClient = json_decode($notification->targetClient,true);
                                        if($notification->target == 'customer'){
                                            $client = \App\Models\Customer::whereIn('id', $targetClient)->pluck('phone')->toArray();
                                            foreach($client as $c):
                                                echo $c.',';
                                            endforeach;
                                        }elseif ($notification->target == 'deliveryman') {

                                            $client = App\Models\DeliveryMan::whereIn('id', $targetClient)->pluck('phone')->toArray();

                                            foreach($client as $c):
                                                echo $c.',';
                                            endforeach;
                                        }
                                        elseif ($notification->target == 'restaurant') {
                                            $client = \App\Models\Restaurant::whereIn('id', $targetClient)->pluck('name')->toArray();
                                            foreach($client as $c):
                                                echo $c.',';
                                            endforeach;
                                        }
                                    ?>

                                    @else
                                        All
                                    @endif

                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('admin.notification.delete', $notification->id)}}" class="btn btn-sm btn-danger">{{__('messages.delete')}}</a>
                                    </div>
                                </td>
                            </tr>

                        @endforeach

                    </tbody>
                </table>
                @if(count($notifications) === 0)
                <div class="empty--data">
                    <p class="text-muted text-center">No data found
                    </p>
                </div>
                @endif
                <div class="page-area px-4 pb-3">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            {!! $notifications->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Table -->
    </div>

@endsection

@push('javascript')
<script>
    const targetCategory = document.querySelector('#targetCategory');
    const targetClient = document.querySelector('#targetClient');

    const filterValues = {
        getZone: function () {
            return document.querySelector('#zone').value;
        },
        getCategory: function () {
            return document.querySelector('#targetCategory').value;
        },
    };

    targetCategory.addEventListener('change',getTargetClient);
    document.querySelector('#zone').addEventListener('change',getTargetClient);
    async function getTargetClient(){
        const URIdata = {
            zone: filterValues.getZone(),
            filter: filterValues.getCategory(),
        };

        const queryString = new URLSearchParams(URIdata).toString();
        let url = "{{ route('admin.notification.targetClient') }}?" + queryString;

        try {
            const resp = await fetch(url);
            if (resp.ok) {
                const result = await resp.json();
                console.log(result);
                targetClient.style.display = 'block';
                targetClient.innerHTML = `
                    <div class="form-group">
                        ${filterValues.getCategory() === 'customer' ? '<label class="input-label" for="tergat">Select Customer</label>' : filterValues.getCategory() === 'deliveryman' ? '<label class="input-label" for="tergat">Select Deliveryman</label>' : '<label class="input-label" for="tergat">Select Restaurant</label>'}
                        <select name="tergatClient[]" multiple class="form-control js-select2-custom" data-placeholder="Choose One" required>
                            <option value="all">All</option>
                            ${result.map(option => `<option value="${option.id}">${option.name} ${option?.phone ? "("+option.phone+")" : ''}</option>`).join('')}
                        </select>
                    </div>
                `;
                $('.js-select2-custom').select2();
            } else {
                console.error('Fetch error: ', resp.statusText);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>



    <script>


        $("#customFileEg1").change(function () {
            readURL(this);
        });

        $('#notification').on('submit', function (e) {

            e.preventDefault();
            var formData = new FormData(this);

            Swal.fire({
                title: '{{__('messages.are_you_sure')}}',
                text: '{{__('You want to sent notification?')}}',
                type: 'info',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'primary',
                cancelButtonText: '{{__('messages.no')}}',
                confirmButtonText: '{{__('messages.send')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: '{{route('admin.notification.store')}}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.errors) {
                                for (var i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                toastr.success('{{ __('Notifiction sent successfully!') }}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                setTimeout(function () {
                                    location.href = '{{route('admin.notification.add-new')}}';
                                }, 2000);
                            }
                        }
                    });
                }
            })
        })
    </script>
    <script>
        $('#reset_btn').click(function(){
            $('#notification_title').val(null);
            $('#zone').val('all').trigger('change');
            $('#tergat').val('customer').trigger('change');
            $('#description').val(null);
            $('#viewer').attr('src','{{asset('public/assets/admin/img/900x400/img1.png')}}');
            $('#customFileEg1').val(null);
        })
    </script>
@endpush
