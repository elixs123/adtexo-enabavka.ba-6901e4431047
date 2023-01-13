<!-- start: client documents -->
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="javascript:">Dokumenti</a>
            </li>
            @if(!is_null($client))
            <li class="nav-item">
                <a class="nav-link" href="{{ $client->public_url }}" target="_blank">Brza pošta</a>
            </li>
            @endif
        </ul>
        <ul class="nav nav-tabs" role="tablist">
            @foreach(getDocumentStatusSorted() as $status_code => $status)
            <li class="nav-item">
                <a class="nav-link {{ ($status_code == 'draft') ? 'active' : '' }}" data-toggle="tab" href="#client-documents-{{ $status_code }}" role="tab" aria-selected="{{ ($status_code == 'draft') ? 'true' : 'false' }}">{{ $status->name }}</a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach(getDocumentStatusSorted() as $status_code => $status)
            <div class="tab-pane {{ ($status_code == 'draft') ? 'active' : '' }}" id="client-documents-{{ $status_code }}">
                @include('homepage.document._card', ['user_documents' => $user_documents, 'status' => $status_code, 'type' => 'order'])
            </div>
            @endforeach
        </div>
    </div>
    @if(userIsSalesAgent())
    <div class="col-12 col-md-6 offset-md-3">
        {!! Form::open(['url' => route('dashboard'), 'method' => 'GET', 'files' => false, 'autocomplete' => 'false', 'class' => 'form-dates-range']) !!}
            {!! VuexyAdmin::dateRange('start', 'end', $dates_data['start_date'], $dates_data['end_date'], []) !!}
        {!! Form::close() !!}
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge badge-primary">{{ trans('skeleton.stats') }}</span></h4>
            </div>
            <div class="card-content">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-md-flex justify-content-between align-items-center">
                        <span>{{ trans('skeleton.total.documents') }}</span>
                        <strong>{{ $sales_documents['documents'] }}</strong>
                    </li>
                    <li class="list-group-item d-md-flex justify-content-between align-items-center">
                        <span>{{ trans('skeleton.total.vpc') }}</span>
                        <strong>{{ format_price($sales_documents['vpc']) }} {{ ScopedStock::currency() }}</strong>
                    </li>
                    <li class="list-group-item d-md-flex justify-content-between align-items-center">
                        <span>{{ trans('skeleton.total.vpc_discounted') }}</span>
                        <strong>{{ format_price($sales_documents['vpc_discounted']) }} {{ ScopedStock::currency() }}</strong>
                    </li>
                    <li class="list-group-item d-md-flex justify-content-between align-items-center">
                        <span>{{ trans('skeleton.total.vpc_difference') }}</span>
                        <strong>{{ format_price($sales_documents['vpc_difference']) }} {{ ScopedStock::currency() }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endif
    
    
    
    
    
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge badge-primary">Loyalty</span> <span class="badge badge-dark">{{ now()->startOfYear()->format('d.m.Y') }}</span> - <span class="badge badge-dark">{{ now()->endOfYear()->format('d.m.Y') }}</span></h4>
            </div>
            <div class="card-content">
                <div class="table-responsive-lg">
                    <table class="table mb-0">
                        <tr class="text-center text-white">
                            <td class="w-25 bg-success" title="{{ get_codebook_opts('document_type')->where('code', 'order')->first()->name }}" data-toggle="tooltip">{{ $loyalty['orders'] }}</td>
                            <td class="w-25 bg-danger" title="{{ get_codebook_opts('document_type')->where('code', 'return')->first()->name }}" data-toggle="tooltip">{{ $loyalty['returns'] }}</td>
                            <td class="w-25 bg-primary" title="{{ trans('skeleton.difference') }}" data-toggle="tooltip">{{ $loyalty['orders'] - $loyalty['returns'] }}</td>
                            @if(userIsSalesAgent())
                            <td class="w-25 bg-success" title="{{ trans('skeleton.value') }}" data-toggle="tooltip">{{ format_price(getLoyaltyValue($loyalty['orders'] - $loyalty['returns'], is_null(auth()->user()->client) ? 'bih' : auth()->user()->client->country_id)) }} {{ ScopedStock::currency() }}</td>
                            @endif
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    @if(isset($contract))
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge badge-dark">Ugovorena prodaja</span></h4>
            </div>
            <div class="card-content">
                <div class="table-responsive-lg">
                    <table class="table mb-0">
                        <tr class="text-center text-white">
                            <td class="w-25 bg-primary" title="{{ trans('contract.data.total_qty') }}" data-toggle="tooltip">{{ $contract['total_qty'] }}</td>
                            <td class="w-25 bg-danger" title="{{ trans('contract.data.total_bought') }}" data-toggle="tooltip">{{ $contract['total_bought'] }}</td>
                            <td class="w-25 bg-success" title="{{ trans('contract.data.in_stock') }}" data-toggle="tooltip">{{ $contract['in_stock'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- end: client documents -->

@section('script_inline')
    @parent
    @if(userIsSalesAgent())
    <script>
        $(document).ready(function () {
            $('input[name="start"], input[name="end"]').change(function () {
                loader_on();
                $('form.form-dates-range').submit();
            });
        });
    </script>
    @endif
@endsection
