
<div class="card view_new_option mb-2">
    <div class="card-header">
        <label for=""
            id="new_option_name_{{ $key }}">{{ isset($item['name']) ? $item['name'] : "__('add new variation')" }}</label>
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-lg-3 col-md-6">
                <label for="">{{ __('name') }}</label>
                <input required name="options[{{ $key }}][name]" required class="form-control"
                    type="text" onkeyup="new_option_name(this.value,{{ $key }})"
                    value="{{ $item['name'] }}">
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="form-group">
                    <label class="input-label text-capitalize d-flex alig-items-center"><span
                            class="line--limit-1">{{ __('messages.selcetion_type') }} </span>
                    </label>
                    <div class="resturant-type-group border">
                        <label class="form-check form--check mr-2 mr-md-4">
                            <input class="form-check-input" type="radio" value="multi"
                                name="options[{{ $key }}][type]" id="type{{ $key }}"
                                {{ $item['type'] == 'multi' ? 'checked' : '' }}
                                onchange="show_min_max({{ $key }})">
                            <span class="form-check-label">
                                {{ __('Multiple') }}
                            </span>
                        </label>

                        <label class="form-check form--check mr-2 mr-md-4">
                            <input class="form-check-input" type="radio" value="single"
                                {{ $item['type'] == 'single' ? 'checked' : '' }} name="options[{{ $key }}][type]"
                                id="type{{ $key }}" onchange="hide_min_max({{ $key }})">
                            <span class="form-check-label">
                                {{ __('Single') }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="row g-2">
                    <div class="col-sm-6 col-md-4">
                        <label for="">{{ __('Min') }}</label>
                        <input id="min_max1_{{ $key }}" {{ $item['type'] == 'single' ? 'readonly ' : 'required' }}
                            value="{{ ($item['min'] != 0) ? $item['min']:''  }}" name="options[{{ $key }}][min]"
                            class="form-control" type="number" min="1">
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label for="">{{ __('Max') }}</label>
                        <input id="min_max2_{{ $key }}" {{ $item['type'] == 'single' ? 'readonly ' : 'required' }}
                            value="{{ ($item['max'] != 0) ? $item['max']:''  }}" name="options[{{ $key }}][max]"
                            class="form-control" type="number" min="2">
                    </div>

                    <div class="col-md-4">
                        <label class="d-md-block d-none">&nbsp;</label>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <input name="options[{{ $key }}][required]" type="checkbox"
                                    {{ isset($item['required']) ? ($item['required'] == 'on' ? 'checked	' : '') : '' }}>
                                <label for="options[{{ $key }}][required]"
                                    class="m-0">{{ __('Required') }}</label>
                            </div>
                            <div>
                                <button type="button" class="btn  btn-sm delete_input_button"
                                    onclick="removeOption(this)" title="{{ __('Delete') }}">
                                    <svg class="icon-32" width="25" style="color:red;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.2871 5.24297C20.6761 5.24297 21 5.56596 21 5.97696V6.35696C21 6.75795 20.6761 7.09095 20.2871 7.09095H3.71385C3.32386 7.09095 3 6.75795 3 6.35696V5.97696C3 5.56596 3.32386 5.24297 3.71385 5.24297H6.62957C7.22185 5.24297 7.7373 4.82197 7.87054 4.22798L8.02323 3.54598C8.26054 2.61699 9.0415 2 9.93527 2H14.0647C14.9488 2 15.7385 2.61699 15.967 3.49699L16.1304 4.22698C16.2627 4.82197 16.7781 5.24297 17.3714 5.24297H20.2871ZM18.8058 19.134C19.1102 16.2971 19.6432 9.55712 19.6432 9.48913C19.6626 9.28313 19.5955 9.08813 19.4623 8.93113C19.3193 8.78413 19.1384 8.69713 18.9391 8.69713H5.06852C4.86818 8.69713 4.67756 8.78413 4.54529 8.93113C4.41108 9.08813 4.34494 9.28313 4.35467 9.48913C4.35646 9.50162 4.37558 9.73903 4.40755 10.1359C4.54958 11.8992 4.94517 16.8102 5.20079 19.134C5.38168 20.846 6.50498 21.922 8.13206 21.961C9.38763 21.99 10.6811 22 12.0038 22C13.2496 22 14.5149 21.99 15.8094 21.961C17.4929 21.932 18.6152 20.875 18.8058 19.134Z" fill="currentColor"></path></svg>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="option_price_{{ $key }}">
            <div class="border rounded p-3 pb-0 mt-3" >
                    <div id="option_price_view_{{ $key }}">


                @if (isset($item['values']))
                    @foreach ($item['values'] as $key_value => $value)
                        <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-md-0">
                            <div class="col-md-4 col-sm-6">
                                <label for="">{{ __('Option_name') }}</label>
                                <input class="form-control" required type="text"
                                    name="options[{{ $key }}][values][{{ $key_value }}][label]"
                                    value="{{ $value['label'] }}">
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label for="">{{ __('Additional_price') }}</label>
                                <input class="form-control" required type="number" min="0" step="0.01"
                                    name="options[{{ $key }}][values][{{ $key_value }}][optionPrice]"
                                    value="{{ $value['optionPrice'] }}">
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label for="">{{ __('Margin') }}</label>
                                <input class="form-control" required type="number" min="0" step="0.01"
                                    name="options[{{ $key }}][values][{{ $key_value }}][optionMargin]"
                                    value="{{ $value['optionMargin']??0 }}">
                            </div>
                            <div class="col-sm-2 max-sm-absolute">
                                <label class="d-none d-md-block">&nbsp;</label>
                                <div class="mt-1">
                                    <button type="button" class="btn  btn-sm delete_input_button" onclick="deleteRow(this)"
                                        title="{{__('Delete')}}">
                                        <svg class="icon-32" width="25" style="color:red;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.2871 5.24297C20.6761 5.24297 21 5.56596 21 5.97696V6.35696C21 6.75795 20.6761 7.09095 20.2871 7.09095H3.71385C3.32386 7.09095 3 6.75795 3 6.35696V5.97696C3 5.56596 3.32386 5.24297 3.71385 5.24297H6.62957C7.22185 5.24297 7.7373 4.82197 7.87054 4.22798L8.02323 3.54598C8.26054 2.61699 9.0415 2 9.93527 2H14.0647C14.9488 2 15.7385 2.61699 15.967 3.49699L16.1304 4.22698C16.2627 4.82197 16.7781 5.24297 17.3714 5.24297H20.2871ZM18.8058 19.134C19.1102 16.2971 19.6432 9.55712 19.6432 9.48913C19.6626 9.28313 19.5955 9.08813 19.4623 8.93113C19.3193 8.78413 19.1384 8.69713 18.9391 8.69713H5.06852C4.86818 8.69713 4.67756 8.78413 4.54529 8.93113C4.41108 9.08813 4.34494 9.28313 4.35467 9.48913C4.35646 9.50162 4.37558 9.73903 4.40755 10.1359C4.54958 11.8992 4.94517 16.8102 5.20079 19.134C5.38168 20.846 6.50498 21.922 8.13206 21.961C9.38763 21.99 10.6811 22 12.0038 22C13.2496 22 14.5149 21.99 15.8094 21.961C17.4929 21.932 18.6152 20.875 18.8058 19.134Z" fill="currentColor"></path></svg>

                                    </button>

                                </div>
                            </div>
                        </div>

                    @endforeach
                @endif
            </div>
                <div class="row mt-3 p-3 mr-1 d-flex" id="add_new_button_{{ $key }}">
                    <button type="button"
                        class="btn btn-outline-primary"onclick="add_new_row_button({{ $key }})">{{ __('messages.add new option') }}</button>
                </div>

            </div>




        </div>
    </div>
</div>
