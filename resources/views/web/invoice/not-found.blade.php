{{-- extend the view from master blade --}}
@extends(config('rabsana-psp.views.web.extends'))

{{-- show the page title --}}
@section(config('rabsana-psp.views.web.title-section'))
    Not Found
@endsection

{{-- write custom css here --}}
@push(config('rabsana-psp.views.web.styles-stack'))
@endpush

{{-- show the page content --}}
@section(config('rabsana-psp.views.web.content-section'))
    <div class="text-center w-100 m-5">

        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_4DPcyu.json" background="transparent"
                       speed="1"
                       style="width: 500px; height: 250px; margin:10px auto;" loop autoplay>
        </lottie-player>

        <h4>
            <p>
                Whoops! Page not found
            </p>
            <p>
                @lang('rabsana-psp::psp.page_not_found')
            </p>
        </h4>
    </div>
@endsection


{{-- write custom javascript --}}
@push(config('rabsana-psp.views.web.scripts-stack'))

@endpush