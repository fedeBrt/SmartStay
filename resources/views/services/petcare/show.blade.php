@extends('admin.layout')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('petcare.index') }}">Pet Care</a></li>
    <li class="breadcrumb-item active" aria-current="page">Show Reservation</li>
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
            <thead id="serviceTableHeader">
            <tr><h2 id="serviceTitle"><i class="fas fa-paw fa-xs" style="padding: 5px;"></i>Pet Care<a
                            href="{{ route('restaurant.index') }}"><i
                                id="addGuest" class="fas fa-user-plus fa-xs"
                                style="padding-left: 70%; color: white; z-index: 1;"></i></a></h2>
            </tr>
            <tr>
                <fieldset>
                    <legend>Show</legend>
                    <strong>Date hour: </strong> {{ $petcare->order_date }}<br>
                    <strong>Guest Name: </strong> {{ $guest->firstname. " ".$guest->lastname }}<br>
                    <strong>Guest Phone: </strong> {{ $guest->telephone }}
                </fieldset>
            </tr>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementsByClassName("itemDropdown")[6].style.color="white";
    </script>
@endsection