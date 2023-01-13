<h5>{{ trans('document.data.client_id') }}</h5>@php $_client = $document->rClient->is_headquarter ? $document->rClient : $document->rClient->rHeadquarter; @endphp
@if(!is_null($_client))
<div class="recipient-info">
    <p><strong class="badge badge-primary">{{ $_client->code }}</strong> {{ $_client->full_name }}</p>
    <p>{{ $_client->address }}</p>
    <p>{{ $_client->postal_code }} {{ $_client->city }}, <span class="text-uppercase">{{ $_client->country_id }}</span></p>
</div>
<div class="recipient-contact pb-2">
    <p>JIB: {{ $_client->jib }}</p>
        @if($_client->pib)
    <p>PIB: {{ $_client->pib }}</p>
        @endif
</div>
@endif
