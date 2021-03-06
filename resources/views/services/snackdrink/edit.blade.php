@extends('admin.layout')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('snackdrink.index') }}">Snacks and Drinks</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Order</li>
@endsection
@section('css')
    <style>
        fieldset {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        legend {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
        }
    </style>
@endsection

@section('content')

    <div class="card"
         style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); padding: 10px;">
        <table class="table table-sm table-hover text-center" id="serviceTable">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <thead id="serviceTableHeader">
            <tr><h2 id="serviceTitle"><i class="fas fa-utensils fa-xs" style="padding: 5px;"></i>Snacks and Drinks<a
                            href="{{ route('snackdrink.index') }}"><i
                                id="addGuest" class="fas fa-user-plus fa-xs"
                                style="padding-left: 70%; color: white; z-index: 1;"></i></a></h2>
            </tr>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::model($snackdrink, ['route' => ['snackdrink.update', $snackdrink->id], 'method'=>'PATCH', null]) !!}
            @include('services.snackdrink.partial.form')
            {!! Form::close() !!}


        </table>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#change').change(function () {
                var $this = this;
                if ($($this).is(':checked')) {
                    this.checked = (confirm("Are you sure?")) ? $('#selectGuest').prop('disabled', false) : false;
                }else{$('#selectGuest').prop('disabled', true)}
            });
        });
    </script>

    <script>
        document.getElementsByClassName("itemDropdown")[4].style.color="white";
    </script>
@endsection