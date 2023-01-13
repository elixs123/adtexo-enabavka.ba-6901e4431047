<!-- start: invoicing documents -->

<div class="col-12">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#invoicing">Za fakturisanje</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#invoiced">Fakturisano</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#express_post">Brza pošta</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#delivered">Dostavljeno</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#retrieved">Preuzeto</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#returned">Vraćeno</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reversed">Stornirano</a></li>
    </ul>
</div>

<div class="tab-content">
    <div id="invoicing" class="tab-pane fade in active show row">
        <div class="col-12">
            @include('invoice.document._card', ['user_documents' => $user_documents, 'status' => 'for_invoicing', 'type' => 'order'])
        </div>
    </div>

    <div id="invoiced" class="tab-pane fade row">
        <div class="col-12">
            @include('invoice.document._card', ['user_documents' => $user_documents, 'status' => 'invoiced', 'type' => 'order'])
        </div>
    </div>
    
    <div id="express_post" class="tab-pane fade row">
        <div class="col-12">
            @include('invoice.document._card', ['user_documents' => $user_documents, 'status' => 'express_post', 'type' => 'order'])
        </div>
    </div>
    
    <div id="delivered" class="tab-pane fade row">
        <div class="col-12">
            @include('invoice.document._card', ['user_documents' => $user_documents, 'status' => 'delivered', 'type' => 'order'])
        </div>
    </div>
    
    <div id="retrieved" class="tab-pane fade row">
        <div class="col-12">
            @include('invoice.document._card', ['user_documents' => $user_documents, 'status' => 'retrieved', 'type' => 'order'])
        </div>
    </div>

    <div id="returned" class="tab-pane fade row">
        <div class="col-12">
            @include('invoice.document._card', ['user_documents' => $user_documents, 'status' => 'returned', 'type' => 'order'])
        </div>
    </div>

    <div id="reversed" class="tab-pane fade row">
        <div class="col-12">
            @include('invoice.document._card', ['user_documents' => $user_documents, 'status' => 'reversed', 'type' => 'order'])
        </div>
    </div>
</div>

<!-- end: invoicing documents -->

@section('script_inline')
@parent
@include('homepage.document._script', ['status_actions' => true])
@endsection
