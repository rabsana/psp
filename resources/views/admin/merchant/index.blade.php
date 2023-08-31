{{-- extend the view from master blade --}}
@extends(config('rabsana-psp.views.admin.extends'))

{{-- show the page title --}}
@section(config('rabsana-psp.views.admin.title-section'))
    @lang('rabsana-psp::psp.business_archive')
@endsection

{{-- write custom css here --}}
@push(config('rabsana-psp.views.admin.styles-stack'))
    <style>
        .gg-sync {
            box-sizing: border-box;
            position: relative;
            display: block;
            transform: scale(var(--ggs, 1));
            border-radius: 40px;
            border: 2px solid;
            margin: 1px;
            border-left-color: transparent;
            border-right-color: transparent;
            width: 18px;
            height: 18px
        }

        .gg-sync::after,
        .gg-sync::before {
            content: "";
            display: block;
            box-sizing: border-box;
            position: absolute;
            width: 0;
            height: 0;
            border-top: 4px solid transparent;
            border-bottom: 4px solid transparent;
            transform: rotate(-45deg)
        }

        .gg-sync::before {
            border-left: 6px solid;
            bottom: -1px;
            right: -3px
        }

        .gg-sync::after {
            border-right: 6px solid;
            top: -1px;
            left: -3px
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
                        <a href='{{ route("rabsana-psp.admin-api.v1.merchants.create") }}' class='btn btn-primary'>
                            @lang('rabsana-psp::psp.add_business')
                        </a>
                        <a href='{{ route("rabsana-psp.admin-api.v1.merchants.document") }}' class='btn btn-primary'
                           target="_blank">
                            @lang('rabsana-psp::psp.business_management_documentation')
                        </a>
                    </div>

                </div>

                <form class='mb-2' method='get'>
                    <div class='row'>

                        {{-- name --}}
                        <div class='col-md-4 mt-1'>
                            <label>@lang('rabsana-psp::psp.name')</label>
                            <input placeholder="@lang('rabsana-psp::psp.name')" class='form-control' type='search' name='name'
                                   value='{{ Request::query("name") }}'/>
                        </div>

                        {{-- token --}}
                        <div class='col-md-4 mt-1'>
                            <label>@lang('rabsana-psp::psp.token')</label>
                            <input placeholder="@lang('rabsana-psp::psp.token')" class='form-control' type='search' name='token'
                                   value='{{ Request::query("token") }}'/>
                        </div>

                        {{-- is_active --}}
                        <div class='col-md-4 mt-1'>
                            <label>@lang('rabsana-psp::psp.status')</label>
                            <select class='custom-select form-control' id='is_active' name='is_active'>
                                <option value='' selected>
                                    @lang('rabsana-psp::psp.all_situations')
                                </option>
                                <option value='1' @if(Request::query('is_active')==1) selected @endif>
                                    @lang('rabsana-psp::psp.active')
                                </option>
                                <option value='0' @if(Request::query('is_active')==0 &&
                                is_numeric(Request::query('is_active'))) selected @endif>
                                    @lang('rabsana-psp::psp.inactive')
                                </option>
                            </select>
                        </div>

                        <div class='col-md-12 mt-1'>
                            <button class='btn btn-primary' type='submit'> @lang('rabsana-psp::psp.search')</button>
                        </div>
                    </div>
                </form>

                <div class='table-responsive'>
                    <table class='table mb-0 table-responsive-xs table-striped'>
                        <thead>
                        <tr>
                            <th> #</th>
                            <th>@lang('rabsana-psp::psp.name')</th>
                            <th>@lang('rabsana-psp::psp.user')</th>
                            <th>@lang('rabsana-psp::psp.token')</th>
                            <th>@lang('rabsana-psp::psp.status')</th>
                            <th>@lang('rabsana-psp::psp.token_reconstruction')</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($merchants as $item)
                            <tr>
                                <th>
                                    {{ $loop->iteration }}
                                </th>
                                <td>
                                    <img width="64px" src="{{ $item->logo_path }}" class="rounded">

                                    {{ $item->name }}
                                </td>
                                <td>
                                    <a class="text-info"
                                       href="{{url('/'.setting("admin_url").'/user/'.$item->user->id)}}"
                                       target="_blank">{{ $item->user->name }}</a>
                                </td>
                                <td>
                                    {{ $item->token }}
                                </td>
                                <td>
                                    {{ $item->is_active_prettified }}
                                </td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary btn-sm"
                                            data-toggle="modal" data-target="#refreshToken{{$item->id}}">
                                        <i class="gg-sync"></i>
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="refreshToken{{$item->id}}" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLabel">@lang('rabsana-psp::psp.token_reconstruction')</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div style="white-space: initial" class="alert alert-danger">
                                                        @lang('rabsana-psp::psp.If_the_token_restored')
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">@lang('rabsana-psp::psp.cansel')
                                                    </button>
                                                    <a href="{{route('rabsana-psp.admin-api.v1.refresh.token', $item->id)}}"
                                                       class="btn btn-success">@lang('rabsana-psp::psp.rebuilding')</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class='btn-group'>
                                        {{--edit--}}
                                        <a href='{{ route("rabsana-psp.admin-api.v1.merchants.edit" , $item) }}'
                                           class='btn btn-sm btn-success' data-toggle='tooltip' title="@lang('rabsana-psp::psp.edit')">
                                            <span class='fas fa-edit'></span>
                                        </a>

                                        {{--destroy--}}
                                        <form action='{{ route("rabsana-psp.admin-api.v1.merchants.destroy" , $item) }}'
                                              method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class='btn btn-sm btn-danger' data-toggle='tooltip'
                                                    title="@lang('rabsana-psp::psp.delete')">
                                                <span class='fas fa-times'></span>
                                            </button>
                                        </form>
                                    </div>
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
                    {{ $merchants->onEachSide(7)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


{{-- write custom javascript --}}
@push(config('rabsana-psp.views.admin.scripts-stack'))

@endpush