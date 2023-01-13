<!-- start: editor documents -->
<div class="row">@php $doc_status = get_codebook_opts('document_status')->pluck('name', 'code')->toArray(); @endphp
    <div class="col-12">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($main_tabs as $main_tab_code => $main_tab_name)
            <li class="nav-item">
                <a class="nav-link {{ ($main_tab_code == $query['tab']) ? 'active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['tab' => $main_tab_code], false, ['tab'])) }}" data-loader>{{ $main_tab_name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    @if($query['tab'] == 'documents')
    <div class="col-12">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($statuses as $status_code => $status_name)
            <li class="nav-item">
                <a class="nav-link {{ ($status_code == $query['status']) ? 'active' : '' }}" href="{{ route('dashboard', httpQuery($query, ['status' => $status_code], false, ['tab', 'status'])) }}" data-loader>{{ $status_name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="col-12">
            @if($query['status'] == 'in_process')
            @include('homepage.document._card', ['user_documents' => $user_documents, 'status' => 'in_process', 'type' => 'order'])
            @elseif($query['status'] == 'for_invoicing')
                @include('homepage.document._card', ['user_documents' => $user_documents, 'status' => 'for_invoicing', 'type' => 'order', 'payment' => 'cash_payment'])
                @include('homepage.document._card', ['user_documents' => $user_documents, 'status' => 'for_invoicing', 'type' => 'order', 'payment' => 'other_payment'])
            @endif
    </div>
    @endif
    
    @if($query['tab'] == 'return')
    <div class="col-12">
        @include('homepage.document._card', ['user_documents' => $user_returns, 'status' => 'in_process', 'type' => 'return'])
    </div>
    @endif
    
    @if($query['tab'] == 'clients')
    <div class="col-12">
        @include('homepage.document._client', ['user_clients' => $user_clients, 'status' => 'pending', 'type' => 'client'])
    </div>
    @endif
    </div>
</div>
<!-- end: editor documents -->

@section('script_inline')
    @parent
    @include('homepage.document._script', ['status_actions' => true])
@endsection
