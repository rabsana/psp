{{-- extend the view from master blade --}}
@extends(config('rabsana-psp.views.web.extends'))

{{-- show the page title --}}
@section(config('rabsana-psp.views.web.title-section'))
@endsection

{{-- write custom css here --}}
@push(config('rabsana-psp.views.web.styles-stack'))
@endpush

{{-- show the page content --}}
@section(config('rabsana-psp.views.web.content-section'))
@endsection


{{-- write custom javascript --}}
@push(config('rabsana-psp.views.web.scripts-stack'))

    <div class="row" dir="rtl">
        <div class="col-md-8 m-auto p-3 pt-5">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('rabsana-psp.web.invoices.pay', ['token' => $invoice->token]) }}" method="POST">
                @csrf
                <p>
                    @lang('rabsana-psp::psp.currency'): {{ $invoice->base }}
                </p>
                <p>
                    @lang('rabsana-psp::psp.amount_payable'):
                    {{ $invoice->amount_prettified }} @lang('rabsana-psp::psp.toman')
                </p>
                <p>
                    @lang('rabsana-psp::psp.number'):
                    {{ $invoice->qty_prettified }} {{ $invoice->base }}
                </p>

                {{-- username --}}
                <div class='col-md-12'>
                    <div class='form-group mb-2'>
                        <label class='text-capitalize p-1' for='username'>
                            @lang('rabsana-psp::psp.username')
                            <span class='text-danger'>*</span>
                        </label>
                        <input type='text' class='form-control' id='username'
                               placeholder='@lang('rabsana-psp::psp.username_email_mobile_number')'
                               name='username' value="{{ old('username') }}" title="@lang('rabsana-psp::psp.enter_required_value')"
                               required>
                    </div>
                </div>

                {{-- password --}}
                <div class='col-md-12'>
                    <div class='form-group mb-2'>
                        <label class='text-capitalize p-1' for='password'>
                            @lang('rabsana-psp::psp.password')
                            <span class='text-danger'>*</span>
                        </label>
                        <input type='password' class='form-control' id='password' placeholder='@lang('rabsana-psp::psp.password')'
                               name='password'
                               title="@lang('rabsana-psp::psp.enter_the_required_value')" required>
                    </div>
                </div>

                <div class="col-md-12 text-center">
                    <button class="btn btn-primary" type="submit">
                        @lang('rabsana-psp::psp.submit')
                    </button>
                </div>

            </form>
        </div>
    </div>

@endpush