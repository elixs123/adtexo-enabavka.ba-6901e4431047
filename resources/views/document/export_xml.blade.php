<podaci>
    <narudzbe>
        @foreach($items as $item)
        <narudzba>
            <id>
                {{ $item->id }}
            </id>

            <broj>
                {{ $item->id }}/{{ $item->date_of_order->format('y') }}
            </broj>

            <vrsta>
                {{ '' }}
            </vrsta>

            @if($item->isCashPayment())
                <kupac_id>
                    FIZ1
                </kupac_id>
            @else
                <kupac_id>
                    {{ is_null($item->rClient->rParent) ? $item->rClient->code : $item->rClient->rParent->code }}
                </kupac_id>
            @endif

            <dostavno_mj_id>
                {{ $item->rClient->location_code }}
            </dostavno_mj_id>

            <datum>
                {{ is_null($item->date_of_order) ? '' : $item->date_of_order->format('Y-m-d') }}
            </datum>

            <datum_isporuke>
                {{ is_null($item->date_of_delivery) ? '' : $item->date_of_delivery->format('Y-m-d') }}
            </datum_isporuke>

            <komercijalista_id>
                {{ is_null($item->rCreatedBy->rPerson) ? '' : $item->rCreatedBy->rPerson->code }}
            </komercijalista_id>

            @if($item->useMpcPrice())
                @if(!is_null($item->rCreatedBy) && $item->rCreatedBy->isSalesAgent())
                    <napomena>
                        {{ trim($item->shipping_data['name']) }}
                    </napomena>
                @else
                    <napomena>
                        {{ trim(is_null($item->note) ? $item->rClient->name : $item->note) }}
                    </napomena>
                @endif
            @else
                <napomena>
                    {{ $item->note }}
                </napomena>
            @endif

            <br_narudzbe_kupca>
                {{ '' }}
            </br_narudzbe_kupca>

            <skladiste_id>
                {{ $item->rStock->code }}
            </skladiste_id>

            <broj_koleta>
                {{ $item->package_number }}
            </broj_koleta>

            <kilaza>
                {{ $item->weight }}
            </kilaza>

            <nacin_placanja>
                {{ $item->rPaymentType->name }}
            </nacin_placanja>

            <stavke>
                @foreach($item->rDocumentProduct as $product)
                    @if($product->qty > 0)
                        <stavka>
                            <artikal_id>
                                {{ $product->code }}
                            </artikal_id>

                            <kolicina>
                                {{ $product->qty }}
                            </kolicina>

                            <jmj_id>
                                {{ $product->rUnit->name }}
                            </jmj_id>

                            <vpc>
                                {{ number_format($product->price, 2, '.', '') }}
                            </vpc>

                            @if(is_null($product->contract_id))
                                <rabat1>
                                    {{ number_format($item->payment_discount, 2, '.', '') }}
                                </rabat1>
                                <rabat2>
                                    {{ number_format($item->discount_value1, 2, '.', '') }}
                                </rabat2>
                                <rabat3>
                                    {{ number_format($item->discount_value2, 2, '.', '') }}
                                </rabat3>
                            @else
                                <rabat1>
                                    {{ number_format($product->contract_discount, 2, '.', '') }}
                                </rabat1>
                                <rabat2>
                                    {{ number_format($item->payment_discount, 2, '.', '') }}
                                </rabat2>
                                <rabat3>
                                    {{ number_format($item->discount_value1, 2, '.', '') }}
                                </rabat3>
                            @endif
                            <prod_cijena>
                                {{ number_format($product->price_discounted, 2, '.', '') }}
                            </prod_cijena>
                        </stavka>
                    @endif
                @endforeach
                @if($item->delivery_cost > 0)
                <stavka>
					@if($item->isCashPayment())
                        <artikal_id>
                            {{ $item->delivery_cost == 3.5 ? 'PRE1' : 'PRE' }}
                        </artikal_id>
					@else
                        <artikal_id>
                            USL
                        </artikal_id>
					@endif
                    <kolicina>1</kolicina>
                    <jmj_id>kom</jmj_id>
                    <vpc>{{ number_format($item->delivery_cost, 2, '.', '') }}</vpc>
                    <rabat1>0.00</rabat1>
                    <rabat2>0.00</rabat2>
                    <rabat3>0.00</rabat3>
                    <prod_cijena>{{ number_format($item->delivery_cost, 2, '.', '') }}</prod_cijena>
                </stavka>
                @endif
            </stavke>
        </narudzba>
        @endforeach
    </narudzbe>
</podaci>
