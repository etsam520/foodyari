
@php
 $marquees = App\Models\Marquee::latest()->limit(10)->get();
@endphp
<div class="marquee-container">
    <marquee scrollamount="12" onmouseover="this.stop();" onmouseout="this.start();">
        @foreach ($marquees as $marquee)
            <div class="marquee-item">
                <div class="d-flex">
                    @if(!empty($marquee->link))
                        <div class="text-warning">
                            <a href="{{$marquee->link}}"> {{Str::upper(Str::limit($marquee['title'], 100, '...'))}}</a>
                        </div>
                    @elseif (!empty($marquee->file))
                        <div class="text-warning">
                            <a target="_blank" href="{{asset("marquee/$marquee->file")}}"> {{Str::upper(Str::limit($marquee['title'], 100, '...'))}}</a>
                        </div>                
                    @endif
                </div>
            </div>
        @endforeach
    </marquee> 
</div>

