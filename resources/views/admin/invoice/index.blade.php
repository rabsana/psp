{{-- extend the view from master blade --}}
@extends(config('rabsana-psp.views.admin.extends'))

{{-- show the page title --}}
@section(config('rabsana-psp.views.admin.title-section'))
    @lang('rabsana-psp::psp.invoice_archived')
@endsection

{{-- write custom css here --}}
@push(config('rabsana-psp.views.admin.styles-stack'))
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>

    <link href="{{ asset('assets') }}/admin/default/js/flatpickr/dist/flatpickr.min.css" rel="stylesheet"
          type="text/css"/>

    <style>
        .popover {
            right: unset !important;
        }

        .flatpickr-current-month .numInputWrapper {
            width: 8ch !important;
        }

        .cur-year {
            margin: 0 20px !important;
        }

        .flatpickr-month {
            height: 35px !important;
        }

        html[mode=dark] .flatpickr-month {
            color: white !important;
        }

        html[mode=dark] body:not(.site-content *),
        html[mode=dark] .table:not(.site-content *) {
            color: #ffffff !important;
        }

        .gal-box {
            height: 200px !important;
        }

        html[mode=light] body,
        html[mode=light] .table {
            color: #000000 !important;
        }

        html[dir=rtl] .flatpickr-time {
            direction: ltr !important;
        }

    </style>
@endpush

{{-- show the page content --}}
@section(config('rabsana-psp.views.admin.content-section'))
    <div class='row'>
        <div class='col-lg-12'>
            <div class='card-box'>
                <div class='row'>

                    <div class='col-md-6'>
                        <h4 class='header-title mb-2'>
                            @lang('rabsana-psp::psp.archive')
                        </h4>
                    </div>
                    <div class='col-md-6 text-left'>
                    </div>
                </div>

                <form class='mb-2' method='get'>
                    <div class='row'>

                        {{-- merchant --}}
                        <div class='col-md-3 mt-1'>
                            <label>@lang('rabsana-psp::psp.business')</label>
                            <input placeholder="@lang('rabsana-psp::psp.business_name')" class='form-control' type='search'
                                   name='merchant'
                                   value='{{ Request::query("merchant") }}'/>
                        </div>

                        {{-- user --}}
                        <div class='col-md-3 mt-1'>
                            <label>@lang('rabsana-psp::psp.payer')</label>
                            <input placeholder="@lang('rabsana-psp::psp.name_of_the_payer')" class='form-control' type='search'
                                   name='user'
                                   value='{{ Request::query("user") }}'/>
                        </div>

                        {{-- currency --}}
                        <div class='col-md-3 mt-1'>
                            <label>@lang('rabsana-psp::psp.currency')</label>
                            <input placeholder="@lang('rabsana-psp::psp.name_currency_symbol')" class='form-control' type='search'
                                   name='currency'
                                   value='{{ Request::query("currency") }}'/>
                        </div>

                        {{-- status --}}
                        <div class='col-md-3 mt-1'>
                            <label>@lang('rabsana-psp::psp.status')</label>
                            <select name="status" id="" title="@lang('rabsana-psp::psp.billing_status')" class="form-control">
                                <option value="">@lang('rabsana-psp::psp.select_invoice_status')</option>
                                @foreach ($statuses as $status)
                                    <option value="{{$status['name']}}"
                                            @if (Request::query("status") && Request::query("status")== $status['name'])
                                            selected
                                            @endif>{{$status['name_translated']}}</option>
                                @endforeach
                            </select>

                        </div>

                        {{-- token --}}
                        <div class='col-md-4 mt-1'>
                            <label>@lang('rabsana-psp::psp.token')</label>
                            <input placeholder="@lang('rabsana-psp::psp.token')" class='form-control' type='search' name='token'
                                   value='{{ Request::query("token") }}'/>
                        </div>

                        {{-- status --}}
                        <div class='col-md-4 mt-1'>
                            <label>@lang('rabsana-psp::psp.up_date')</label>
                            <input placeholder="@lang('rabsana-psp::psp.up_date')" class='form-control datePicker' type='search'
                                   name='from_date'
                                   value='{{ Request::query("from_date") }}'/>
                        </div>

                        {{-- date --}}
                        <div class='col-md-4 mt-1'>
                            <label>@lang('rabsana-psp::psp.up_date')</label>
                            <input placeholder="@lang('rabsana-psp::psp.date')" class='form-control datePicker' type='search'
                                   name='to_date'
                                   value='{{ Request::query("to_date") }}'/>
                        </div>

                        <div class='col-md-12 mt-1'>
                            <button class='btn btn-primary' type='submit'> @lang('rabsana-psp::psp.search')</button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    @foreach ($report as $currency)

                        <div class="col-sm-3 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <img src="{{$currency['icon']}}" alt="{{$currency['name']}}" class="py-1">
                                    <h5 class="card-title py-1">{{$currency['name']}}</h5>
                                    <hr>
                                    <h6 class="card-subtitle mb-2 text-muted">@lang('rabsana-psp::psp.total_orders_of_this_currency')</h6>
                                    <p class="card-text pb-1">
                                        {{ $currency['sum'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>

                <div class='table-responsive'>
                    <table class='table mb-0 table-responsive-xs table-striped'>
                        <thead>
                        <tr>
                            <th> #</th>
                            <th>@lang('rabsana-psp::psp.business')</th>
                            <th>@lang('rabsana-psp::psp.payer')</th>
                            <th>@lang('rabsana-psp::psp.currency')</th>
                            <th>@lang('rabsana-psp::psp.amount')</th>
                            <th>@lang('rabsana-psp::psp.token')</th>
                            <th>@lang('rabsana-psp::psp.status')</th>
                            <th>@lang('rabsana-psp::psp.date')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($invoices as $item)
                            <tr>
                                <th>
                                    {{ $loop->iteration }}
                                </th>
                                <td>
                                    {{ $item->merchant->name }}
                                </td>
                                <td>
                                    <a class="text-info"
                                       href="{{url('/'.setting("admin_url").'/user/'.(optional($item->user)->id ?? 0))}}"
                                       target="_blank">{{ optional($item->user)->name ?? '' }}</a>
                                </td>
                                <td>
                                    {{ $item->qty_prettified }} {{ $item->base }}
                                </td>
                                <td>
                                    {{ $item->amount_prettified }}
                                    {{-- <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                                        Tooltip on top
                                      </button> --}}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger my-popover" data-placement="top"
                                            data-toggle="popover" title="@lang('rabsana-psp::psp.token')" style="position: relative;"
                                            data-content="{{ $item->token }}">@lang('rabsana-psp::psp.see')
                                    </button>
                                </td>
                                <td>
                                    {{ $item->status_info['name_translated'] }}
                                </td>
                                <td>
                                    {{ $item->jcreated_at }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class='alert-danger text-center noResult' colspan="20">
                                    @lang('rabsana-psp::psp.there_are_no_results')
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $invoices->onEachSide(7)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


{{-- write custom javascript --}}
@push(config('rabsana-psp.views.admin.scripts-stack'))


    <script src="{{ asset('assets') }}/admin/default/js/flatpickr/dist/flatpickr.min.js"></script>
    <script src="{{ asset('assets') }}/admin/default/js/flatpickr/jdate.min.js"></script>
    <script src="{{ asset('assets') }}/admin/default/js/flatpickr/dist/l10n/fa.js"></script>


    <script>

        locale = 'en';
        if ($('html').attr('lang') == 'fa') {
            window.Date = window.JDate;
            locale = 'fa';
        }
        $(".dateAndTimePicker").flatpickr({
            "locale": locale,
            enableTime: true,
            dateFormat: "Y/m/d H:i:S",
            time_24hr: true,
            disableMobile: "true"
        });
        $(".datePicker").flatpickr({
            "locale": locale,
            enableTime: false,
            dateFormat: "Y-m-d",
            time_24hr: false,
            disableMobile: "true"
        });

        $(function () {
            $('[data-toggle="popover"]').popover()
        })
        $(document).on('focus', ':not(.popover)', function () {
            $('.popover').popover('hide');
        })


    </script>
@endpush