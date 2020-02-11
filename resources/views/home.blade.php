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

</style>
@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12"> 
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{URL::to('/admin/getDashboard')}}"
                                allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection
