{{-- extend the view from master blade --}}
@extends(config('rabsana-psp.views.web.extends'))

{{-- show the page title --}}
@section(config('rabsana-psp.views.web.title-section'))
    Successful
@endsection

{{-- write custom css here --}}
@push(config('rabsana-psp.views.web.styles-stack'))
@endpush

{{-- show the page content --}}
@section(config('rabsana-psp.views.web.content-section'))
    <div class="text-center w-100 m-5">

        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <lottie-player src="https://assets4.lottiefiles.com/private_files/lf30_poez9ped.json" background="transparent"
                       speed="1" style="width: 250px; height: 250px; margin:10px auto;" autoplay></lottie-player>

        <h4>
            <p>
                Successful
            </p>
            <p>
                @lang('rabsana-psp::psp.your_bill_was_successfully_paid')
            </p>
            <a class="btn btn-success btn-block w-25 m-3 mx-auto" id="callbackLink" href="{{ $callbackUrl }}"
               data-callback="{{ $callbackUrl }}">
                @lang('rabsana-psp::psp.return_acceptor_site')<span id="countdown">10</span>
                @lang('rabsana-psp::psp.another_second')
            </a>
        </h4>
    </div>
@endsection


{{-- write custom javascript --}}
@push(config('rabsana-psp.views.web.scripts-stack'))
    <script>
        $(function () {

            setInterval(() => {
                var seconds = Number($('#countdown').html());
                if ((seconds - 1) < 0) {
                    return;
                }
                $("#countdown").html(seconds - 1);
            }, 1000);


            setTimeout(() => {
                window.location.href = $("#callbackLink").data("callback");
            }, 10000);
        })
        //
    </script>
@endpush