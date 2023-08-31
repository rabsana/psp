{{-- extend the view from master blade --}}
@extends(config('rabsana-psp.views.admin.extends'))

{{-- show the page title --}}
@section(config('rabsana-psp.views.admin.title-section'))
    @lang('rabsana-psp::psp.business_editing')
@endsection

{{-- write custom css here --}}
@push(config('rabsana-psp.views.admin.styles-stack'))
@endpush

{{-- show the page content --}}
@section(config('rabsana-psp.views.admin.content-section'))

    <div class='row'>
        <div class='col-lg-12'>
            <div class='card'>
                <div class='card-body'>
                    <div class='row p-2'>
                        <div class='col-md-6'>
                            <h4 class='header-title text-capitalize'>
                                @lang('rabsana-psp::psp.business_editing')
                            </h4>
                        </div>

                        <div class='col-md-6 text-left'>
                            <a href="{{ route('rabsana-psp.admin-api.v1.merchants.index') }}" class='btn btn-primary'>
                                @lang('rabsana-psp::psp.archive')
                            </a>
                        </div>

                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $item)
                                    <li>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('rabsana-psp.admin-api.v1.merchants.update' , $merchant) }}" method='POST'
                          enctype='multipart/form-data' class='needs-validation' novalidate>
                        @method('PATCH')
                        @csrf
                        <div class='row p-2'>

                            {{-- name --}}
                            <div class='col-md-8 my-2'>
                                <div class='form-group mb-2'>
                                    <label class='text-capitalize' for='name'>
                                        @lang('rabsana-psp::psp.business_name')
                                        <span class='text-danger'>*</span>
                                    </label>
                                    <input type='text' class='form-control' id='name' placeholder='name' name='name'
                                           value="{{ old('name' , $merchant->name) }}" required>
                                </div>
                            </div>

                            {{-- is_active --}}
                            <div class='col-md-8 my-2'>
                                <div class='form-group mb-2'>
                                    <label for='is_active'>
                                        @lang('rabsana-psp::psp.business_status')
                                        <span class='text-danger'>*</span>
                                    </label>
                                    <select class='custom-select form-control' id='is_active' name='is_active' required>
                                        <option value='1'
                                                @if(old('is_active' , $merchant->is_active)==1) selected @endif>
                                            @lang(('active'))
                                        </option>
                                        <option value='0'
                                                @if(old('is_active' , $merchant->is_active)==0) selected @endif>
                                            @lang('rabsana-psp::psp.inactive')
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- logo --}}
                            <div class='col-md-8 my-2'>
                                <div class='form-group mb-2'>
                                    <label class='text-capitalize' for='logo'>
                                        @lang('rabsana-psp::psp.business_logo')
                                        <span class='text-danger'>*</span>
                                    </label>
                                    <input type='file' class='form-control' id='logo' placeholder='logo' name='logo'>
                                </div>
                            </div>

                            {{-- currency_id --}}
                            <div class='col-md-8 my-2'>
                                <div class='form-group mb-2'>
                                    <label for='currency_id'>
                                        @lang('rabsana-psp::psp.currency_selection')
                                    </label>
                                    <select class="form-control" id="currency_id" name="currency_ids[]" required
                                            multiple
                                            data-currencyids="{{ json_encode(collect($merchant->currencies)->pluck('id')->toArray()) }}"
                                            data-url="{{ route('rabsana-psp.admin-api.v1.get.currencies') }}">
                                    </select>
                                </div>
                            </div>


                        </div>

                        <div class="col-md-12">
                            <button class='btn btn-primary m-2' type='submit'>
                                @lang(('submit'))
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


{{-- write custom javascript --}}
@push(config('rabsana-psp.views.admin.scripts-stack'))

    <script>
        $(function () {

            $.ajax({
                type: "get",
                url: $("#currency_id").data('url'),
                success: function (response) {
                    var currencyStr = '';
                    var currencyIds = $("#currency_id").data('currencyids');

                    for (var i = 0; i < response.length; i++) {

                        var selected = '';
                        if (currencyIds.includes(response[i].id)) {
                            selected = 'selected';
                        }

                        currencyStr += `
                    <option value="${response[i].id}" ${selected}>
                    ${response[i].name}    
                    </option>
                    `;
                    }

                    $('#currency_id').html(currencyStr);
                }
            });

        });
    </script>

@endpush