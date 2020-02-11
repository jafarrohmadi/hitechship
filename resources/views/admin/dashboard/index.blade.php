<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" type="text/css" href="{{asset('css/leaflet.css') }}"/>

    <link rel="stylesheet" type="text/css" href="{{asset('css/leaflet.contextmenu.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/leaflet.draw.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/tabs.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/tabstyles.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/skysatu.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/leaflet-measure.css') }}"/>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .input-modal {
            width: 70%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            resize: vertical;
        }

        .input-modal-text {
            width: 100%;
            margin-bottom: 10px;
        }

        input[type=submit] {
            background-color: #2CC185;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }


        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 50px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0, 0, 0); /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
            text-align: center;
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        /* The Close Button */
        .close {
            opacity: 0.6;
            float: right;
            font-size: 14px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body style="height: inherit !important;">
<div id="googleMap"></div>
<div id="googleMapHistory" style="display: none"></div>
<div id="speedCount" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div
                    style="text-align: center;"><h1 class="modal-title">Enter the Number of Points to Be Calculated</h1>
                </div>
            </div>
            <div class="modal-body">
                <form class="addPoints" id="addPoints" method="get" href="">
                    <input type="text" class="speedPolyline input-modal" placeholder="In Nautical Knots"
                           id="speedPolyline">
                    <input type="submit" class="btn btn-default" value="Submit" id="submitPoint">
                </form>
            </div>
        </div>

    </div>
</div>
<div  class="leaflet-top leaflet-right" id="box" style="display: none">
    <div class="leaflet-control-measure leaflet-bar leaflet-control" aria-haspopup="true">
        <div class="leaflet-control-measure-interaction js-interaction">
            <div class="js-measuringprompt"><h3 style="font-size: 11px">Expected Time Remaining</h3>
                <div class="js-results results" id="totalETR">
                </div>
            </div>
        </div>
    </div>
</div>
<div  class="leaflet-top leaflet-right" id="averageSpeedTime" style="display: none">
    <div class="leaflet-control-measure leaflet-bar leaflet-control" aria-haspopup="true">
        <div class="leaflet-control-measure-interaction js-interaction">
            <div class="js-measuringprompt"><h3 style="font-size: 11px" id="titleAverage"></h3>
                <div class="js-results results" id="totalAverage">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="floating-panel">
    <div class="close">
        <i class="fa fa-angle-double-left"></i>
    </div>
    <section>
        <div class="tabs tabs-style-linebox">
            <nav>
                <ul>
                    <li id="tab-track" class="tab-current"><a href="#section-linebox-1"><span>Track</span></a></li>
                    <li id="tab-history"><a href="#section-linebox-2"><span>History</span></a></li>
                </ul>
            </nav>
            <div class="content-wrap">
                <section id="section-linebox-1" class="content-current">
                    <table class="fixed_headers" id="tracking_table">
                        <thead>
                        <tr>
                            <td>
                                <button class="startPoint">Start Point</button>
                                <button class="stopDrawing" >Stop Drawing</button>
                            </td>
{{--                            <td>--}}
{{--                                <button class="resetPoint">Reset Point</button>--}}
{{--                            </td>--}}
                        </tr>

                        <tr>
                            <td><input id="checkAll" type="checkbox" checked="checked"/></td>
                            <td>Name</td>
                            <td>Last Update</td>
                            <td>Speed (knots)</td>
                        </tr>
                        </thead>
                        <tbody id="shipData">
                        </tbody>
                    </table>
                </section>
                <section id="section-linebox-2">
                    <div style="text-align: center;">
                        <input type="text" class="datepicker startDate">&nbsp;
                        <input type="text" class="datepicker endDate">
                        <button id="setDate"><i class="fa fa-search"></i>&nbsp;Search</button>
                        <button id="downloadCSV"><i class="fa fa-download"></i></button>
                    </div>
                    <table class="fixed_headers history" id="history_table">
                        <thead>
                        <tr>
                            <td></td>
                            <td>Name</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody id='historyShipData'>
                        </tbody>
                    </table>
                </section>
            </div><!-- /content -->
        </div><!-- /tabs -->
    </section>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js')}}"></script>
<script src="{{ asset('js/MeasureTool.min.js') }}"></script>
<script src="{{ asset('js/cbpFWTabs.js') }}"></script>
<script src="{{ asset('js/jquery-dateFormat.min.js') }}"></script>

<script src="{{ asset('js/leaflet.js') }}"></script>
<script src="{{ asset('js/leaflet.rotatedMarker.js') }}"></script>
<script src="{{ asset('js/leaflet.contextmenu.js') }}"></script>
<script src="{{ asset('js/leaflet.draw.js') }}"></script>
<script src="{{ asset('js/leaflet-providers.js') }}"></script>
<script src="{{ asset('js/customleaflet.js') }}"></script>


</body>
</html>
