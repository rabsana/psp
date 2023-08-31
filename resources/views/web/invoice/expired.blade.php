{{-- extend the view from master blade --}}
@extends(config('rabsana-psp.views.web.extends'))

{{-- show the page title --}}
@section(config('rabsana-psp.views.web.title-section'))
    Expired
@endsection

{{-- write custom css here --}}
@push(config('rabsana-psp.views.web.styles-stack'))
@endpush

{{-- show the page content --}}
@section(config('rabsana-psp.views.web.content-section'))
    <div class="text-center w-100 m-5">

        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <lottie-player src="https://assets8.lottiefiles.com/packages/lf20_ibwcdbyk.json" background="transparent"
                       speed="0.8" style="width: 500px; height: 250px; margin:10px auto;" autoplay></lottie-player>

        <h4>
            <p>
                Expired
            </p>
            <p>
                @lang('rabsana-psp::psp.your_bill_expired_please_try_again')
            </p>
            <a class="btn btn-primary btn-block w-25 m-3 mx-auto" href="{{ $callbackUrl }}">
                @lang('rabsana-psp::psp.return_acceptor_site')
            </a>
        </h4>
    </div>
@endsection


{{-- write custom javascript --}}
@push(config('rabsana-psp.views.web.scripts-stack'))

@endpush