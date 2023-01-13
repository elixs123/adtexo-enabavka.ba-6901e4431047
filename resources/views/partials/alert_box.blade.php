
@if(Session::has('success_msg') || isset($success_msg))
<div class="alert @if(isset($class)) {{ $class }} @endif alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i class="fa-fw fa fa-check"></i>
    <strong>Uspjeh!</strong> {{{ Session::get('success_msg') }}}
</div>
@endif

@if(Session::has('error_msg') || isset($error_msg))
<div class="alert @if(isset($class)) {{ $class }} @endif alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i class="fa-fw fa fa-times"></i>
    <strong>Greška!</strong> {{{ Session::get('error_msg') }}}
</div>
@endif

<div style="display: {{ count($errors->all()) ? 'block' : 'none' }}" class="alert alert-danger">
    <i class="fa-fw fa fa-times"></i>
    Dogodile su se <strong>greške</strong>:<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
