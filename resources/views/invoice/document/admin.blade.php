<meta name="csrf-token" content="{{ csrf_token() }}"/>
<div class="row">
    <div class="col-12 col-lg-6">
        <ul class="nav nav-tabs" role="tablist">
            @foreach(get_codebook_opts('countries') as $country)
                <li class="nav-item">
                    <a class="nav-link {{ ($country->code == $query['country']) ? 'active' : '' }}"
                       href="{{ route('invoicing', httpQuery($query, ['country' => $country->code])) }}"
                       data-loader>{{ $country->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="col-12 col-lg-6">
        {!! Form::open(['url' => route('invoicing'), 'method' => 'GET', 'files' => false, 'autocomplete' => 'false', 'class' => 'form-dates-range']) !!}
        {!! VuexyAdmin::dateRange('start', 'end', $dates_data['start_date'], $dates_data['end_date'], []) !!}
        @foreach($query as $key => $value)
            @if(!in_array($key, ['start', 'end']))
                {!! Form::hidden($key, $value) !!}
            @endif
        @endforeach
        {!! Form::close() !!}
    </div>
</div>

<!-- start: documents -->
<div id="documents" class="row">
    <div class="col-12">
        <ul class="nav nav-tabs" role="tablist">
            @foreach(getDocumentStatusSorted() as $status_code => $status)
                @if($status_code == 'for_invoicing' 
                    || $status_code == 'invoiced' 
                    || $status_code == 'returned'
                    || $status_code == 'reversed')
                    <li class="nav-item">
                        <a class="nav-link {{ ($status_code == $query['status']) ? 'active' : '' }}"
                           href="{{ route('invoicing', httpQuery($query, ['status' => $status_code])) }}#documents"
                           data-loader>{{ $status->name }}</a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
    @php $doc_status = get_codebook_opts('document_status')->where('code', $query['status'])->first(); $doc_type = get_codebook_opts('document_type')->where('code', $query['type_id'])->first(); @endphp
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-1 border-bottom">
                <h4 class="card-title"><span class="badge"
                                             style="background-color: {{ $doc_type->background_color }};color: {{ $doc_type->color }};">{{ $doc_type->name }}</span>
                    <span class="badge"
                          style="background-color: {{ $doc_status->background_color }};color: {{ $doc_status->color }};">{{ $doc_status->name }}</span>
                    <span class="badge badge-dark">{{ $user_documents->count() }}</span></h4>
            </div>
            <div class="card-content">
                @if(!empty($user_documents))
                    <div class="table-responsive-lg">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>{{ trans('document.data.date_of_order') }}</th>
                                <th>{{ trans('document.data.client_id') }}</th>
                                <th>{{ trans('document.data.printing') }}</th>
                                @if($query['type_id'] != 'return')
                                    <th>{{ trans('document.data.payment_type') }}</th>
                                @endif
                                <th style="width: 250px;">{{ trans('document.data.note') }}</th>
                                <th class="text-right">{{ trans('document.data.subtotal') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user_documents as $document)
                                <tr>
                                    <td>{{ $document->id }}</td>
                                    <td>{{ $document->date_of_order->format('d.m.Y.') }}</td>
                                    <td>
                                        @if(is_null($document->client_id))
                                            <span>-</span>
                                        @else
                                            <a href="{{ route('document.show', ['id' => $document->id]) }}"
                                               title="{{ trans('document.actions.show') }}"
                                               data-tooltip><strong>{{ $document->rClient->full_name }}</strong></a>
                                            @if(!is_null($document->rClient->rSalesmanPerson))
                                                <br><small>({{ $document->rClient->rSalesmanPerson->name }})</small>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if($document->status == 'returned')
                                                        <button class="btn btn-primary rounded"
                                                                type="button"
                                                                onclick="printCancellationReceipt({{ $document->id }})">
                                                            Storniraj
                                                        </button>
                                        @else
                                            <div class="btn-group">
                                                @php
                                                    $document->receipt_items = $document->rDocumentProduct()->with(['rDocument', 'rUnit'])->get();
                                                @endphp
                                                
                                                <button class="btn btn-primary dropdown-item rounded-left"
                                                        type="button"
                                                        onclick="printAll({{ $document->id  }}, {{ $document->fiscal_receipt_no }})">{{ trans('document.actions.print_all') }}
                                                </button>

                                                <button type="button"
                                                        class="btn btn-primary dropdown-toggle dropdown-toggle-split rounded-right"
                                                        data-toggle="dropdown"
                                                        aria-haspopup="true"
                                                        aria-expanded="false">
                                                    <span class="sr-only">Izaberi</span>
                                                </button>

                                                <div class="dropdown-menu">
                                                    <button class="dropdown-item"
                                                            type="button"
                                                            onclick="printInvoice({{ $document->id }})">{{ trans('document.actions.invoice') }}
                                                    </button>
                                                    
                                                    @if($document->status == 'for_invoicing')
                                                        <button class="dropdown-item"
                                                                type="button"
                                                                onclick="printReceipt({{ $document->id }})">{{ trans('document.actions.receipt') }}
                                                        </button>
                                                    

                                                    @elseif($document->status == 'invoiced')
                                                        <button class="dropdown-item"
                                                                type="button"
                                                                onclick="printDuplicateReceipt({{ $document->id }})">
                                                            Duplikat fiskalnog
                                                        </button>
                                                        @if(is_null($document->fiscal_receipt_void_no))
                                                            <button class="dropdown-item"
                                                                    type="button"
                                                                    onclick="printCancellationReceipt({{ $document->id }})">
                                                                Storniraj fiskalni
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </td>


                                    @if($query['type_id'] != 'return')
                                        <td>
                                            <small class="badge"
                                                   style="background-color: {{ $document->rPaymentType->background_color }};color: {{ $document->rPaymentType->color }};">{{ $document->rPaymentType->name }}</small>
                                        </td>
                                    @endif
                                    <td>
                                        @if(is_null($document->note))
                                            <small>-</small>
                                        @else
                                            <small>{{ str_limit($document->note, 50) }}</small>@if(strlen($document->note) > 50)
                                                <span class="fa fa-plus-square text-primary cursor-pointer"
                                                      data-toggle="tooltip" data-title="{!! nl2br($document->note) !!}"
                                                      data-html="true"></span>@endif
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ format_price($document->total_discounted_value) }}</strong> {{ $document->currency }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="no-results show" data-no-results>
                        <h5>{{ trans('skeleton.no_results') }}</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- end: documents -->
