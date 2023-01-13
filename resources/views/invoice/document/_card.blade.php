<meta name="csrf-token" content="{{ csrf_token() }}"/>
<div class="card">@php $doc_type = get_codebook_opts('document_type')->where('code', $type)->first(); @endphp
    <div class="card-header pb-1 border-bottom">@php $doc_status = get_codebook_opts('document_status')->where('code', $status)->first(); @endphp
        <h4 class="card-title"><span class="badge"
                                     style="background-color: {{ $doc_type->background_color }};color: {{ $doc_type->color }};">{{ $doc_type->name }}</span>
            <span class="badge"
                  style="background-color: {{ $doc_status->background_color }};color: {{ $doc_status->color }};">{{ $doc_status->name }}</span>@if(($status == 'for_invoicing') && !userIsClient() && isset($payment))
                <span class="badge badge-black">{{ ($payment == 'cash_payment') ? 'Gotovina' : 'Ostalo' }}</span>@endif
        </h4>
    </div>@php $document_type = $type; $type = ($status == 'for_invoicing') ? (isset($payment) ? $payment : $type) : $type; @endphp
    <div class="card-content">
        @php
            if (($status == 'for_invoicing') && isset($user_documents[$status]) && isset($payment)) :
            if ($payment == 'cash_payment') :
            $_documents = $user_documents[$status]->filter(function($document) {return $document->payment_type == 'cash_payment';});
            else :
            $_documents = $user_documents[$status]->reject(function($document) {return $document->payment_type == 'cash_payment';});
            endif;
            else :
            $_documents = isset($user_documents[$status]) ? $user_documents[$status] : collect([]);
            endif;
        @endphp
        @if($no_results = $_documents->count())
            {!! Form::open(['url' => route('document.status.change'), 'method' => 'post', 'files' => false, 'autocomplete' => 'false', 'class' => ($form_class = 'ajax-form-document-'.$type.'-'.$status).' table-responsive-lg', 'data-status' => $type.'-'.$status]) !!}
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                <tr>
                    @if(!userIsClient())
                        <th style="width: 40px;">
                            <div class="custom-control custom-checkbox checkbox-default">
                                <input id="form-control-documents-{{ $type }}-{{ $status }}"
                                       class="custom-control-input" type="checkbox" data-select-all
                                       data-status="{{ $type.'-'.$status }}">
                                <label for="form-control-documents-{{ $type }}-{{ $status }}"
                                       class="custom-control-label">&nbsp;</label>
                            </div>
                        </th>
                    @endif
                    <th>#</th>
                    <th>{{ trans('document.data.date_of_order') }}</th>
                    <th>{{ trans('document.data.client_id') }}</th>
                    <th>{{ trans('document.data.printing') }}</th>
                    @if(!userIsWarehouse() && ($type != 'return'))
                        <th>{{ trans('document.data.payment_type') }}</th>
                    @endif
                    @if(userIsClient())
                        <th>Loyalty</th>
                    @else
                        <th style="width: {{ userIsWarehouse() ? 200 : 250 }}px;">{{ trans('document.data.note') }}</th>
                    @endif
                    @if(userIsWarehouse())
                        <th style="width: 150px;">{{ trans('document.data.package_number_short') }}</th>
                    @else
                        <th class="text-right">{{ trans('document.data.subtotal') }}</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($_documents as $document)
                    <tr id="document{{ $document['uid'] }}" data-tr-status="{{ $type.'-'.$status }}"
                        @if($type !='return' ) data-tr-payment_type="{{ $document->payment_type }}"
                        @endif class="{{ userIsClient() ? (($document->created_by == auth()->id()) ? 'created-by-me' : 'created-by-salesman') : '' }}">
                        @if(!userIsClient())
                            <td>
                                <div class="custom-control custom-checkbox checkbox-default">
                                    <input id="form-control-documents-{{ $document->id }}" class="custom-control-input"
                                           name="d[]" type="checkbox" value="{{ $document->id }}"
                                           data-select-{{ $type.'-'.$status }} @if(($status=='in_warehouse' ) && (is_null($document->package_number))){{ 'disabled' }}@endif>
                                    <label for="form-control-documents-{{ $document->id }}"
                                           class="custom-control-label">&nbsp;</label>
                                </div>
                            </td>
                        @endif
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

                                        @if(is_null($document->fiscal_receipt_no))
                                            <button class="dropdown-item"
                                                    type="button"
                                                    onclick="printReceipt({{ $document->id }})">{{ trans('document.actions.receipt') }}
                                            </button>
                                        @endif

                                        @if(!is_null($document->fiscal_receipt_no))
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

                        @if(!userIsWarehouse() && ($type != 'return'))
                            <td>
                                <small class="badge"
                                       style="background-color: {{ $document->rPaymentType->background_color }};color: {{ $document->rPaymentType->color }};">{{ $document->rPaymentType->name }}</small>
                            </td>
                        @endif
                        @if(userIsClient())
                            <td>{{ $document->rDocumentProduct->sum('total_loyalty_points') }}</td>
                        @else
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
                        @endif
                        @if(userIsWarehouse())
                            <td>
                                <input class="form-control text-center" name="package_number" type="text"
                                       value="{{ $document->package_number }}" aria-label="" maxlength="100"
                                       data-form-url="{{ route('document.changes.store', ['id' => $document->id]) }}"
                                       data-form-warehouse-package onkeydown="return (event.keyCode!=13);">
                                <input type="hidden" name="weight" value="{{ $document->weight }}">
                            </td>
                        @else
                            <td class="text-right">
                                <strong>{{ format_price($document->total_discounted_value) }}</strong> {{ $document->currency }}
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if(can('edit-document') && !userIsClient())
                <div class="border-top p-1">
                    @if($status == 'in_process')
                        <button type="button" class="btn btn-success" data-document-status data-status="completed"
                                data-type="document">{{ trans('document.actions.status_to.completed') }}</button>
                        <button type="button" class="btn btn-danger" data-document-status data-status="canceled"
                                data-type="document">{{ trans('document.actions.status_to.canceled') }}</button>
                    @elseif($status == 'in_warehouse')
                        <button type="button" class="btn btn-success" data-document-status data-status="for_invoicing"
                                data-type="document">{{ trans('document.actions.status_to.for_invoicing') }}</button>
                    @elseif($status == 'for_invoicing')
                        <button type="button" class="btn btn-success" data-document-status data-status="invoiced"
                                data-type="document">{{ trans('document.actions.status_to.invoiced') }}</button>
                        @if($document_type == 'order')
                            <button type="button" class="btn btn-danger" data-document-status data-status="canceled"
                                    data-type="document">{{ trans('document.actions.status_to.canceled') }}</button>
                        @endif
                    @endif
                </div>
                <input type="hidden" name="s" value="" required>
                <input type="hidden" name="t" value="{{ $document_type }}" required>
            @endif
            {!! Form::close() !!}
        @endif
        <div class="no-results @if(!$no_results){{ 'show' }}@endif" data-no-results="{{ $type.'-'.$status }}">
            <h5>{{ trans('skeleton.no_results') }}</h5>
        </div>
    </div>
    <div class="card-footer text-right">
        <a href="{{ route('document.index', ['type_id' => $type, 'status' => $status]) }}"
           class="btn btn-info">{{ trans('skeleton.view_all') }}</a>
    </div>
</div>
