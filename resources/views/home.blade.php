<style>

    .main .container-fluid {
        padding: 0px !important;
    }

    .row {
        margin-right: 0 !important;
        margin-left: 0 !important;
    }

    .col-lg-12 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

     body {
            background: #a1cded !important;
        }

</style>
@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="row">
                        <iframe class="embed-responsive-item" src="{{URL::to('/admin/getDashboard')}}"
                                style="position:absolute;top:19px;left:0;width:100%;height:96.7%; border: 0" allowfullscreen="allowfullscreen" frameborder="0"></iframe>
        </div>
    </div>
@endsection
