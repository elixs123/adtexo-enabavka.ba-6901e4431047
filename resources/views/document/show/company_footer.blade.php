<!-- start: document footer -->
<div class="document-footer pt-3">
    <div class="logo">
        <img src="{{ asset('assets/img/adtexo_logo_20201231.png').assetVersion() }}" alt="enabavka.ba" />
    </div>
    <div class="client-info">
        @if($document->rStock->country_id == 'bih')
        <p><strong class="black">ADTEXO d.o.o.</strong></p>
        <p><strong>ID</strong>: 4202476730003</p>
        <p><strong>PDV</strong>: 202476730003</p>
        @elseif($document->rStock->country_id == 'srb')
        <p><strong class="black">ADTEXO d.o.o. Priboj</strong></p>
        <p><strong>PIB</strong>: 112090920</p>
        <p><strong>MIB</strong>: 21605425</p>
        @endif
    </div>
    <div class="client-info">
        @if($document->rStock->country_id == 'bih')
        <p><strong>Adresa</strong>: Marka Marulića 2, 71000 Sarajevo</p>
        <p><strong>Br. računa</strong>: 1941410040000160</p>
        <p><strong>Tel.</strong>: +387 33 821 881</p>
        @elseif($document->rStock->country_id == 'srb')
        <p><strong>Adresa</strong>: Save Kovačevića 73, Priboj 31330</p>
        <p><strong>Br. računa</strong>: 160-6000000765042-40</p>
        <p><strong>Tel.</strong>: +381 11 422 9101</p>
        @endif
    </div>
</div>
<!-- end: document footer -->
