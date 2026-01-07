@extends('mess-views.layouts.dashboard-main')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div>
       <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between pb-4">
                    <div class="header-title">
                        <h4 class="card-title text-uppercase">
                            <svg viewBox="0 0 24 24" id="Layer_1" data-name="Layer 1" width="30" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><defs><style>.cls-1{fill:none;stroke:#020202;stroke-miterlimit:10;stroke-width:1.91px;}</style></defs><polyline class="cls-1" points="11.04 19.61 1.5 19.61 1.5 3.39 20.59 3.39 20.59 9.11"></polyline><line class="cls-1" x1="11.05" y1="0.52" x2="11.05" y2="5.3"></line><line class="cls-1" x1="6.27" y1="0.52" x2="6.27" y2="5.3"></line><line class="cls-1" x1="15.82" y1="0.52" x2="15.82" y2="5.3"></line><line class="cls-1" x1="8.18" y1="8.16" x2="10.09" y2="8.16"></line><line class="cls-1" x1="12" y1="8.16" x2="13.91" y2="8.16"></line><line class="cls-1" x1="15.82" y1="8.16" x2="17.73" y2="8.16"></line><line class="cls-1" x1="4.36" y1="11.98" x2="6.27" y2="11.98"></line><line class="cls-1" x1="8.18" y1="11.98" x2="10.09" y2="11.98"></line><line class="cls-1" x1="4.36" y1="15.8" x2="6.27" y2="15.8"></line><line class="cls-1" x1="8.18" y1="15.8" x2="10.09" y2="15.8"></line><line class="cls-1" x1="12" y1="11.98" x2="13.91" y2="11.98"></line><ellipse class="cls-1" cx="17.73" cy="18.66" rx="4.77" ry="3.82"></ellipse><path class="cls-1" d="M20.11,11h1.43a0,0,0,0,1,0,0v1.43a2.39,2.39,0,0,1-2.39,2.39H17.73a0,0,0,0,1,0,0V13.41A2.39,2.39,0,0,1,20.11,11Z"></path><path class="cls-1" d="M14.86,11h0a2.86,2.86,0,0,1,2.87,2.87v.95"></path></g></svg>
                            Diet Calender
                        </h4>
                    </div>
                </div>
                <nav>
                    <div class="nav nav-tabs " id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-week-I-tab" data-bs-toggle="tab" data-bs-target="#nav-week-I" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Week : 1st</button>
                        <button class="nav-link" id="nav-week-II-tab" data-bs-toggle="tab" data-bs-target="#nav-week-II" type="button" role="tab" aria-controls="nav-week-II" aria-selected="false">Week : 2nd</button>
                        <button class="nav-link" id="nav-week-III-tab" data-bs-toggle="tab" data-bs-target="#nav-week-III" type="button" role="tab" aria-controls="nav-week-III" aria-selected="false">Week : 3rd</button>
                        <button class="nav-link" id="nav-week-IV-tab" data-bs-toggle="tab" data-bs-target="#nav-week-IV" type="button" role="tab" aria-controls="nav-week-IV" aria-selected="false">Week : 4th</button>
                        <button class="nav-link" id="nav-week-V-tab" data-bs-toggle="tab" data-bs-target="#nav-week-V" type="button" role="tab" aria-controls="nav-week-V" aria-selected="false">Week : 5th</button>
                    </div>
                </nav>
            </div>
        </div>

    
            
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-week-I" role="tabpanel" aria-labelledby="nav-week-I-tab"> 
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h5 class="card-title">First Week</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="firstWeek" data-weeknumber="1">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Break Fast</th>
                                                <th>Lunch</th>
                                                <th>Dinner</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>     
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-week-II" role="tabpanel" aria-labelledby="nav-week-II-tab">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h5 class="card-title">Second Week</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table" id="secondWeek" data-weeknumber="2">
                                    <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>Break Fast</th>
                                            <th>Lunch</th>
                                            <th>Dinner</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                </div>     
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-week-III" role="tabpanel" aria-labelledby="nav-week-III-tab">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h5 class="card-title">Third Week</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table" id="thirdweek" data-weeknumber="3">
                                    <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>Break Fast</th>
                                            <th>Lunch</th>
                                            <th>Dinner</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                </div>     
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-week-IV" role="tabpanel" aria-labelledby="nav-week-IV-tab">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h5 class="card-title">Fourth Week</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="fourth" data-weeknumber="4">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Break Fast</th>
                                                <th>Lunch</th>
                                                <th>Dinner</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>     
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-week-V" role="tabpanel" aria-labelledby="nav-week-V-tab">
                    <div class="col-xl-6 col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h5 class="card-title">Fourth Week</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="fifth" data-weeknumber="5">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Break Fast</th>
                                                <th>Lunch</th>
                                                <th>Dinner</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>     
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </div>
 </div>
@endsection

@push('javascript')

<script>
    const WEEKLY_CALENDAR = {
        getElementsURL: "{{ route('mess.diet-calander.get-calender-elements') }}",
        updateElemURL : "{{route('mess.diet-calander.update-calender-elements')}}",
        getElementsByWeekNo: async function (weekNo) {
            try {
                if (!weekNo) {
                    throw new Error('Empty Week No');
                }
                const resp = await fetch(`${this.getElementsURL}?week_no=${weekNo}`);
                const result = await resp.json();
                // console.log(result);
                if (result.error) {
                    throw new Error(result.error || 'Something went wrong');
                } else {
                    return result;
                }
            } catch (error) {
                toastr.error(error.message);
            }
        },
        showTable:async  function (table) {
            try {
                // const table = document.querySelector(selector);
                const weekNo = table.dataset.weeknumber;
                const result = await  this.getElementsByWeekNo(weekNo);
                if (result) {
                    let dataToAppend = result.map(item => {
                        return `<tr>
                            <th>${item.day.charAt(0).toUpperCase() + item.day.slice(1)}</th>
                            <td>
                                <div class="form-group d-flex justify-content-around">
                                    <span class="form-check">
                                        <label>N</label>
                                        <input type="checkbox" data-elem-id="${item.id}" data-elem-type="B" data-elem-speciality="N" ${item.breakfast === "normal" ? 'checked' : ''} data-check="${item.breakfast === "normal" ? 1 : 0}" class="form-check-input">
                                    </span>
                                    <span class="form-check">
                                        <label>S</label>
                                        <input type="checkbox" data-elem-id="${item.id}" data-elem-type="B" data-elem-speciality="S" ${item.breakfast === "special" ? 'checked' : ''} data-check="${item.breakfast === "special" ? 1:0}" class="form-check-input">
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="form-group d-flex justify-content-around">
                                    <span class="form-check">
                                        <label>N</label>
                                        <input type="checkbox" data-elem-id="${item.id}" data-elem-type="L" data-elem-speciality="N" ${item.lunch === "normal" ? 'checked' : ''} data-check="${item.lunch === "normal" ? 1:0}" class="form-check-input">
                                    </span>
                                    <span class="form-check">
                                        <label>S</label>
                                        <input type="checkbox" data-elem-id="${item.id}" data-elem-type="L" data-elem-speciality="S" ${item.lunch === "special" ? 'checked' : ''} data-check="${item.lunch === "special" ? 1:0}" class="form-check-input">
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="form-group d-flex justify-content-around">
                                    <span class="form-check">
                                        <label>N</label>
                                        <input type="checkbox" data-elem-id="${item.id}" data-elem-type="D" data-elem-speciality="N" ${item.dinner === "normal" ? 'checked' : ''} data-check="${item.dinner === "normal" ? 1:0}" class="form-check-input">
                                    </span>
                                    <span class="form-check">
                                        <label>S</label>
                                        <input type="checkbox" data-elem-id="${item.id}" data-elem-type="D" data-elem-speciality="S" ${item.dinner === "special" ? 'checked' : ''} data-check="${item.dinner === "special" ? 1:0}" class="form-check-input">
                                    </span>
                                </div>
                            </td>
                        </tr>`;
                    }).join('\n');
                    table.querySelector('tbody').innerHTML = dataToAppend;

                    const elements = table.querySelectorAll('[data-elem-type]');
                    elements.forEach(item => {
                        // console.log(item)
                        item.addEventListener('click', function() {
                            WEEKLY_CALENDAR.updateElement(item);
                        });
                    });

                } else {
                    toastr.error('Data Not Available');
                }
            } catch (error) {
                toastr.error(error.message);
            }
        },

        updateElement : async function (item){
            let checked = null
            if(item.dataset.check==1){
                checked =  0;
            }
            if(item.dataset.check==0){checked
                checked = 1;
            }
            try {
                let elemobj = {
                    id : item.dataset.elemId,
                    type : item.dataset.elemType,
                    speciality :item.dataset.elemSpeciality,
                    checked : checked,
                }

                const res = await fetch(this.updateElemURL, {
                    method: 'POST',
                    body: JSON.stringify({element :elemobj}),
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });
                const result = await res.json();
                if (result.error) {
                    throw new Error(result.errosr);
                }
                if (result.success) {
                    // toastr.success(result.success);
                    this.showTable(item.closest('table'));
                }
            } catch (error) {
                toastr.error(error.message);
                console.error(error);
            }
           
        }
    };


    document.querySelectorAll('[data-weeknumber]').forEach(element => {
        WEEKLY_CALENDAR.showTable(element)
    });
</script>


@endpush
