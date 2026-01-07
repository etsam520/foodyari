@extends('vendor-views.layouts.dashboard-main')
@push('css')
    <style>
        .step-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .step-container div {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: #f8f9fa;
            margin: 0 1rem;
            border-radius: 0.5rem;
        }

        .instructions {
            margin-bottom: 2rem;
        }

        .instructions h4 {
            margin-bottom: 1rem;
        }

        .download-buttons a {
            margin-right: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h5 class="page-header-title">{{ __('Bulk Import') }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="step-container">
                                <div>STEP 1<br>Download Excel File</div>
                                <div>STEP 2<br>Match Spreadsheet Data</div>
                                <div>STEP 3<br>Validate Data</div>
                            </div>
                            <div class="instructions">
                                <h4>Instructions</h4>
                                <ol>
                                    <li>Download the format file and fill it with proper data.</li>
                                    <li>You can download the example file to understand how the data must be filled.</li>
                                    <li>Once you have downloaded and filled the format file, upload it in the form below and
                                        submit.</li>
                                    <li>After uploading foods you need to edit them and set image and variations.</li>
                                    <li>You can get category id from their list, please input the right ids.</li>
                                    <li>Don't forget to fill all the fields.</li>
                                    <li>For veg food enter 1 and for non-veg enter 0 on veg field.</li>
                                </ol>
                            </div>
                            <div class="download-buttons mb-3">
                                <a href="{{route('vendor.food.food-export')}}" class="btn btn-secondary">Template with
                                    Existing Data</a>
                                <a href="{{route('vendor.food.food-sample-download')}}" class="btn btn-secondary">Template
                                    without Data</a>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{route('vendor.food.bulk-import')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="file" class="form-label">Choose File</label>
                                    <input type="file" name="file" value="{{old('file')}}" class="form-control" required>
                                </div>
                                <hr style="border: 1px solid #cecbcb;">
                                <div class="text-end">
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
