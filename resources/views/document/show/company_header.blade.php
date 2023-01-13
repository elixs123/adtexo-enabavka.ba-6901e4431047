<!-- start: company details -->
<div class="row">
    <div class="col-sm-6 col-12 text-left">
        <h1>{{ $document_name }}</h1>
    </div>
    <div class="col-sm-6 col-12 text-right">
        @if($show_logo)
        <img src="{{ asset('assets/img/adtexo_logo_20201231.png').assetVersion() }}" alt="Adtexo d.o.o." />
        @else
        <span class="btn" title="{{ trans('skeleton.data.status') }}" data-tooltip style="background-color: {{ $document->rStatus->background_color }};color: {{ $document->rStatus->color }}">{{ $document->rStatus->name }}</span>
        @endif
    </div>
</div>
<!-- end: company details -->
