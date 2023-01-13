<div class="card">@php $doc_type = get_codebook_opts('document_type')->where('code', $type)->first(); @endphp
    <div class="card-header pb-1 border-bottom">@php $doc_status = get_codebook_opts('document_status')->where('code', $status)->first(); @endphp
        <h4 class="card-title"><span class="badge" style="background-color: {{ $doc_type->background_color }};color: {{ $doc_type->color }};">{{ $doc_type->name }}</span> <span class="badge" style="background-color: {{ $doc_status->background_color }};color: {{ $doc_status->color }};">{{ $doc_status->name }}</span>@if(($status == 'for_invoicing') && !userIsClient() && isset($payment)) <span class="badge badge-black">{{ ($payment == 'cash_payment') ? 'Gotovina' : 'Ostalo' }}</span>@endif</h4>
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
                                <input id="form-control-documents-{{ $type }}-{{ $status }}" class="custom-control-input" type="checkbox" data-select-all data-status="{{ $type.'-'.$status }}">
                                <label for="form-control-documents-{{ $type }}-{{ $status }}" class="custom-control-label">&nbsp;</label>
                            </div>
                        </th>
                        @endif
                        <th>#</th>
                        <th>{{ trans('document.data.date_of_order') }}</th>
                        <th>{{ trans('document.data.client_id') }}</th>
                        @if(!userIsWarehouse() && ($type != 'return'))
                        <th>{{ trans('document.data.payment_type') }}</th>
                        @endif
                        @if(userIsClient())
                        <th>Loyalty</th>
                        @else
                        <th style="width: {{ userIsWarehouse() ? 200 : 250 }}px;">{{ trans('document.data.note') }}</th>
                        @endif
                        @if(userIsWarehouse())
                        <th>{{ trans('document.data.delivery') }}</th>
                            @if(in_array($status, ['in_warehouse', 'warehouse_preparing', 'invoiced', 'express_post']))
                        <th style="width: 150px;">{{ trans('document.data.package_number_short') }}</th>
                            @endif
                            @if(in_array($status, ['shipped', 'express_post_in_process']))
                        <th style="width: 150px;">PDF</th>
                            @endif
                            @if(in_array($status, ['shipped', 'express_post_in_process']))
                        <th style="width: 150px;">Status poš.</th>
                            @endif
                            @if(in_array($status, ['retrieved']))
                        <th style="width: 180px;">{{ trans('document.data.picked_at') }}</th>
                            @endif
                        @else
                        <th class="text-right">{{ trans('document.data.subtotal') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($_documents as $document)
                    <tr id="document{{ $document['uid'] }}" data-tr-status="{{ $type.'-'.$status }}" @if($type != 'return') data-tr-payment_type="{{ $document->payment_type }}" @endif class="{{ userIsClient() ? (($document->created_by == auth()->id()) ? 'created-by-me' : 'created-by-salesman') : '' }}">
                        @if(!userIsClient())
                        <td>
                            <div class="custom-control custom-checkbox checkbox-default">
                                <input id="form-control-documents-{{ $document->id }}" class="custom-control-input" name="d[]" type="checkbox" value="{{ $document->id }}" data-select-{{ $type.'-'.$status }} @if(($status == 'warehouse_preparing') && (is_null($document->package_number))){{ 'disabled' }}@endif>
                                <label for="form-control-documents-{{ $document->id }}" class="custom-control-label">&nbsp;</label>
                            </div>
                        </td>
                        @endif
                        <td>{{ $document->id }}</td>
                        <td>{{ $document->date_of_order->format('d.m.Y.') }}</td>
                        <td>
                            @if(is_null($document->client_id))
                            <span>-</span>
                                @else
                            <a href="{{ route('document.show', ['id' => $document->id]) }}" title="{{ trans('document.actions.show') }}" data-tooltip><strong>{{ $document->rClient->full_name }}</strong></a>
                                    @if(!is_null($document->rClient->rSalesmanPerson))
                            <br><small>({{ $document->rClient->rSalesmanPerson->name }})</small>
                                    @endif
                                    @if($document->isCashPayment())
                            <br><strong>{{ array_get($document->shipping_data, 'name', '-') }}</strong>
                                    @endif
                            @endif
                        </td>
                        @if(!userIsWarehouse() && ($type != 'return'))
                        <td>
                            <small class="badge" style="background-color: {{ $document->rPaymentType->background_color }};color: {{ $document->rPaymentType->color }};">{{ $document->rPaymentType->name }}</small>
                        </td>
                        @endif
                        @if(userIsClient())
                        <td>{{ $document->rDocumentProduct->sum('total_loyalty_points') }}</td>
                        @else
                        <td>
                            @if(is_null($document->note))
                            <small>-</small>
                                @else
                            <small>{{ str_limit($document->note, 50) }}</small>@if(strlen($document->note) > 50) <span class="fa fa-plus-square text-primary cursor-pointer" data-toggle="tooltip" data-title="{!! nl2br($document->note) !!}" data-html="true"></span>@endif
                            @endif
                        </td>
                        @endif
                        @if(userIsWarehouse())
                        <td>
                            @if($document->delivery_type == 'paid_delivery')
                            <strong>{{ is_null($document->rExpressPost) ? '-' : $document->rExpressPost->express_post_name }}</strong><br>
                            <small data-shipping-name="{{ $document->id }}">{{ array_get($document->shipping_data, 'name', '-') }}</small><br>
                            <small data-shipping-address="{{ $document->id }}">{{ array_get($document->shipping_data, 'address', '-') }}</small><br>
                            <small data-shipping-city="{{ $document->id }}">{{ array_get($document->shipping_data, 'postal_code', '-') }}, {{ array_get($document->shipping_data, 'city', '-') }}</small>
                            @if($document->status == 'invoiced')
                            <small> - <a href="{{ route('document.shipping.edit', ['id' => $document->id])}}" title="Izmjeni podatke isporuke" data-tooltip data-toggle="modal" data-target="#form-modal1">Izmjeni</a></small>
                            @endif
                            @else
                            <span>{{ $document->rDeliveryType->name }}</span>
                            @endif
                        </td>
                            @if(in_array($status, ['in_warehouse', 'warehouse_preparing', 'invoiced', 'express_post']))
                        <td class="text-center">
                                @if(in_array($status, ['in_warehouse', 'warehouse_preparing', 'invoiced']))
                            <input class="form-control text-center" name="package_number" type="text" value="{{ $document->package_number }}" aria-label="" maxlength="100" data-form-url="{{ route('document.changes.store', ['id' => $document->id]) }}" data-form-warehouse-package onkeydown="return (event.keyCode!=13);">
                            <input type="hidden" name="weight" value="{{ $document->weight }}">
                                @else
                            <span>{{ $document->package_number }}</span>
                                @endif
                        </td>
                            @endif
                            @if(in_array($status, ['shipped', 'express_post_in_process']))
                        <td>
                            @if(!is_null($document->rExpressPost->pdf_label_path))
                            <a href="{{ asset($document->rExpressPost->pdf_label_path) }}" title="Preuzmi Label PDF" data-toggle="tooltip" target="_blank">Label PDF</a><br>
                            @elseif(!is_null($document->rExpressPost->pdf_label))
                            <a href="{{ route('document.express-post.pdf', ['id' => $document->id, 'type' => 'label']) }}" title="Preuzmi Label PDF" data-toggle="tooltip" target="_blank">Label PDF</a><br>
                            @endif
                            
                            @if(!is_null($document->rExpressPost->pdf_pickup_path))
                            <a href="{{ asset($document->rExpressPost->pdf_pickup_path) }}" title="Preuzmi Pickup PDF" data-toggle="tooltip" target="_blank">Pickup PDF</a>
                            @elseif(!is_null($document->rExpressPost->pdf_pickup))
                            <a href="{{ route('document.express-post.pdf', ['id' => $document->id, 'type' => 'pickup']) }}" title="Preuzmi Pickup PDF" data-toggle="tooltip" target="_blank">Pickup PDF</a>
                            @endif
                        </td>
                            @endif
                            @if(in_array($status, ['shipped', 'express_post_in_process']))
                        <td>
                            <small><strong data-express-post-status="{{ $document->id }}">{{ $document->rStatus->name }}</strong></small> <a href="{{ route('expresspost.status', ['id' => $document->id]) }}" title="Provjeri status pošiljke" data-toggle="tooltip" data-express-post-check><i class="fa fa-refresh"></i></a>
                            @if(!is_null($document->rExpressPost) && isset($document->rExpressPost->traces['status_code']) && isset($document->rExpressPost->traces['status_label']))
                            <br><small style="opacity: .5">- <strong>{{ $document->rExpressPost->traces['status_label'] }}</strong> ({{ $document->rExpressPost->traces['status_code'] }})</small>
                            @endif
                        </td>
                            @endif
                            @if(in_array($status, ['retrieved']))
                        <td>{{ $document->rTakeover->picked_at->format('d.m.Y \u H:i') }}<br>{{ $document->rTakeover->name }}</td>
                            @endif
                        @else
                        <td class="text-right"><strong>{{ format_price($document->total_discounted_value) }}</strong> {{ $document->currency }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(can('edit-document') && !userIsClient())
            <div class="border-top p-1">
                @if($status == 'in_process')
                <button type="button" class="btn btn-success" data-document-status data-status="in_warehouse" data-type="document">{{ trans('document.actions.status_to.in_warehouse') }}</button>
                @elseif($status == 'in_warehouse')
                <button type="button" class="btn btn-success" data-document-status data-status="warehouse_preparing" data-type="document">{{ trans('document.actions.status_to.warehouse_preparing') }}</button>
                <button type="button" class="btn btn-danger" data-document-status data-status="canceled" data-type="document">{{ trans('document.actions.status_to.canceled') }}</button>
                @elseif($status == 'warehouse_preparing')
                <button type="button" class="btn btn-success" data-document-status data-status="for_invoicing" data-type="document">{{ trans('document.actions.status_to.for_invoicing') }}</button>
                @elseif($status == 'for_invoicing')
                <button type="button" class="btn btn-success" data-document-status data-status="invoiced" data-type="document">{{ trans('document.actions.status_to.invoiced') }}</button>
                    @if(($document_type == 'order') && !userIsWarehouse())
                <button type="button" class="btn btn-danger" data-document-status data-status="canceled" data-type="document">{{ trans('document.actions.status_to.canceled') }}</button>
                    @endif
                @elseif($status == 'invoiced')
                    @if($warehouse_tab == 'express_post')
                <button type="button" class="btn btn-success" data-href="{{ route('home.document.express-post') }}" data-url="{{ route('home.document.express-post') }}" data-toggle="modal" data-target="#form-modal1" data-status="{{ $type.'-'.$status }}" data-document-express-post>{{ trans('document.actions.status_to.express_post') }}</button>
                    @else
                <button type="button" class="btn btn-success" data-href="{{ route('home.document.takeover') }}" data-url="{{ route('home.document.takeover') }}" data-toggle="modal" data-target="#form-modal1" data-status="{{ $type.'-'.$status }}" data-document-express-post>{{ trans('document.actions.status_to.retrieved') }}</button>
                    @endif
                @elseif($status == 'express_post')
                <button type="button" class="btn btn-success" data-document-status data-status="shipped" data-type="document">{{ trans('document.actions.status_to.shipped') }}</button>
                <button type="button" class="btn btn-danger" data-document-status data-status="express_post_canceled" data-type="document">{{ trans('document.actions.status_to.express_post_canceled') }}</button>
                <button type="button" class="btn btn-success hidden" data-href="{{ route('home.document.pdf') }}" data-url="{{ route('home.document.pdf') }}" data-toggle="modal" data-target="#form-modal1" data-document-pdf>PDF</button>
                @elseif($status == 'shipped')
                <button type="button" class="btn btn-success" data-document-status data-status="delivered" data-type="document">{{ trans('document.actions.status_to.delivered') }}</button>
                <button type="button" class="btn btn-danger" data-document-status data-status="returned" data-type="document">{{ trans('document.actions.status_to.returned') }}</button>
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
        <a href="{{ route('document.index', ['type_id' => $type, 'status' => $status]) }}" class="btn btn-info">{{ trans('skeleton.view_all') }}</a>
    </div>
</div>

@section('script_inline')
    @parent
    <script>
        $(document).ready(function () {
            $('input[data-form-warehouse-package]').change(function(e) {
                e.preventDefault();
                
                var $el = $(this);
    
                HttpRequest.post($(this).data('form-url'), {
                    package_number: $(this).val(),
                    weight: $(this).next('input').val(),
                }, function() {
                    $el.parents('tr').find('input[type="checkbox"]:eq(0)').prop('disabled', false);
                });
            });
            
            $('a[data-express-post-check]').click(function (e) {
                e.preventDefault();
                
                $(this).tooltip('hide');
                
                loader_on();
    
                HttpRequest.get($(this).attr('href'), {
                    v: "{{ time() }}"
                }, function(response) {
                    loader_off();
                    $('[data-express-post-status="' + response.document.id + '"]').text(response.document.r_status.name);
                });
            });
        });
        
        function documentShippingDataChanged(response) {
            if (response.shipping) {
                $('small[data-shipping-name="' + response.shipping.id + '"]').text(response.shipping.name);
                $('small[data-shipping-address="' + response.shipping.id + '"]').text(response.shipping.address);
                $('small[data-shipping-city="' + response.shipping.id + '"]').text(response.shipping.city);
            }
        }
    </script>
@endsection
