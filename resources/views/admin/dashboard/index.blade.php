<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="{{asset('js/MeasureTool.min.js') }}"></script>
    <script src="{{ asset('js/cbpFWTabs.js') }}"></script>
    <script src="{{asset('js/jquery-dateFormat.min.js') }}"></script>
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
        <script src="{{asset('js/leaflet.js')}}"></script>
            <script src="{{asset('js/customleaflet.js')}}"></script>
<!--       <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzPsrAS2Wg_xEdvr1uVc0fIhy0U99yOkU&callback=initMap"
  type="text/javascript"></script> -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/leaflet.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/tabs.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/tabstyles.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/skysatu.css') }}"/>

    <script>
        var locations = {
            "01035506SKYB6F7": {
                id: 5420605146,
                name: "GLORIA SENTOSA",
                eventTime: 1575985126000,
                heading: 3236,
                speed: 0,
                latitude: -7.17835,
                longitude: 112.72058333333334
            },
        };
    </script>
</head>
<body>
<div id="googleMap"></div>
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
                            <td><input id="checkAll" type="checkbox" checked="checked"/></td>
                            <td>Name</td>
                            <td>Last</td>
                            <td>Speed (knots)</td>
                        </tr>
                        </thead>
                        <tbody>

                        <tr class="header">
                            <td><input type="checkbox" name="3" checked="checked"/></td>
                            <td colspan="3">Sentek Marine & Trading Pte Ltd</td>
                        </tr>


                        <tr class="row">
                            <td><input type="checkbox" name="3" value="01162082SKY1F27" checked="checked"/></td>
                            <td>STK PRIMA 6</td>
                            <td id="01162082SKY1F27-last">-</td>
                            <td id="01162082SKY1F27-speed">0</td>
                        </tr>


                        <tr class="row">
                            <td><input type="checkbox" name="3" value="01229667SKYCB2C" checked="checked"/></td>
                            <td>STK MERLION 111</td>
                            <td id="01229667SKYCB2C-last">37m26s</td>
                            <td id="01229667SKYCB2C-speed">0</td>
                        </tr>
                        </tbody>
                    </table>
                </section>
                <section id="section-linebox-2">
                    <div style="text-align: right;">
                        <input type="text" class="datepicker startDate">&nbsp;<input type="text"
                                                                                     class="datepicker endDate">
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
                        <tbody>

                        <tr class="header">
                            <td></td>
                            <td>Sentek Marine & Trading Pte Ltd</td>
                        </tr>

                        <tr class="row">
                            <td><input type="checkbox" name="3" value="01162082SKY1F27"/></td>
                            <td>STK PRIMA 6</td>
                        </tr>

                        <tr class="row">
                            <td><input type="checkbox" name="3" value="01229667SKYCB2C"/></td>
                            <td>STK MERLION 111</td>
                        </tr>

                        </tbody>
                    </table>
                </section>
            </div><!-- /content -->
        </div><!-- /tabs -->
    </section>
</div>
    <script>


</script>

</body>
</html>
