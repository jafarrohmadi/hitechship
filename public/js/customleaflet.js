$(document).ready(function () {
    let locations = {};
    let LeafIcon = L.Icon.extend({
        options: {
            //iconSize: [6, 10],
            shadowSize: [10, 12],
            shadowAnchor: [4, 62],
            iconAnchor: [10, 10],//changed marker icon position
            popupAnchor: [0, -16]//changed popup position
        }
    });

    let map, mapHistory;
    let markers = new L.FeatureGroup();
    let savePolyline = new L.FeatureGroup();
    let markersHistory = new L.FeatureGroup();
    let filterMarkers = [];
    let average_speed = [];
    let drawPolylineStart = 0;
    let drawLatLngInitial;
    let drawSpeedValue;
    let drawLatLng = [];
    let drawCountDistanceStartEndPoint = 0;
    let lastDrawPoint;

//Set Awal
    (function () {
        [].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
            new CBPFWTabs(el);
        });
    })();

    $("#tab-track").click(function () {
        $("#googleMap").show();
        $("#googleMapHistory").hide();
        $("#averageSpeedTime").hide();
    });

    $("#tab-history").click(function () {
        $("#googleMapHistory").show();
        $("#googleMap").hide();
        $("#box").hide();
        getDataMapHistory();
    });

    let minDatePicker = new Date();
    minDatePicker.setFullYear(minDatePicker.getFullYear() - 1);

    $(".datepicker").datepicker({
        dateFormat: 'dd.mm.yy',
        autoSize: true,
        firstDay: 1,
        constrainInput: true,
        maxDate: '0',
        minDate: minDatePicker
    });

    let endDate = new Date();
    endDate.setHours(0, 0, 0, 0);
    let startDate = endDate;

    $(".datepicker.startDate").datepicker("setDate", startDate);
    $(".datepicker.endDate").datepicker("setDate", endDate);

    $("#setDate").click(function () {
        endDate = $(".datepicker.endDate").datepicker("getDate");
        startDate = $(".datepicker.startDate").datepicker("getDate");
        $("div.inner-table").closest("tr").remove();
        for (let terminalId in locations) {
            let message = locations[terminalId];
            if (message.path) {
                message.path.remove();
                $.each(message.historiesMarkers, function (i, marker) {
                    markersHistory.removeLayer(marker);
                });
                delete message.historiesMarkers;
                delete message.path;
            }
        }
    });

    function getTimeDifference(fromDate) {
        if (!fromDate) {
            return "-";
        }

        let toDate = new Date().getTime();

        let seconds = Math.round((toDate - fromDate) / 1000);
        let minutes = 0, hours = 0, days = 0, weeks = 0, months = 0, years = 0;

        let result = seconds + "s";

        if (seconds >= 60) {
            minutes = Math.floor(seconds / 60);
            seconds = seconds % 60;
            result = minutes + "m" + seconds + "s";
        }

        if (minutes >= 60) {
            hours = Math.floor(minutes / 60);
            minutes = minutes % 60;
            result = hours + "h" + minutes + "m";
        }

        if (hours >= 24) {
            days = Math.floor(hours / 24);
            hours = hours % 24;
            result = days + "d" + hours + "h";
        }

        if (days >= 7 && days < 30) {
            weeks = Math.floor(days / 7);
            days = days % 7;
            result = weeks + "w" + days + "d";
        } else if (days >= 30 && days < 365) {
            months = Math.floor(days / 30);
            weeks = Math.floor(days % 30 / 7);
            result = months + "m" + weeks + "w";
        } else if (days >= 365) {
            years = Math.floor(days / 365);
            months = Math.floor(days % 365 / 30);
            result = years + "y" + months + "m";
        }

        return result;
    }

    $('#floating-panel div.close').on('click', function () {
        let $this = $("#floating-panel");
        if ($this.hasClass('open')) {
            $this.animate({
                left: 0
            }).removeClass('open');
            $('#floating-panel div.close i').removeClass("fa-angle-double-right");
            $('#floating-panel div.close i').addClass("fa-angle-double-left");
        } else {
            $this.animate({
                left: '-285px'
            }).addClass('open');
            $('#floating-panel div.close i').removeClass("fa-angle-double-left");
            $('#floating-panel div.close i').addClass("fa-angle-double-right");
        }
    });

    function getDataMap() {
        map = L.map('googleMap', {
            zoomControl: false,
            center: [0, 118.8230631], zoom: 5
        });

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        map.on('click', function (e) {
            if (whileDrawPolyline == 0) {
                var popLocation = e.latlng;
                $('#speedCount').modal('toggle');
                if (closeModal == 0) {
                    $("#floating-panel .close").trigger('click');
                    closeModal = 1
                }
                drawPolylineStart = 1;
                startPoint(popLocation);
            }
        });
    }

    function centerLeafletMapOnMarker(lat, lng) {
        map.flyTo(new L.LatLng(lat, lng), 5);
    }

    function getDataMapHistory() {
        mapHistory = L.map('googleMapHistory', {
            zoomControl: false,
            center: [0, 118.8230631], zoom: 5
        });
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapHistory);

        L.control.zoom({
            position: 'bottomright'
        }).addTo(mapHistory);
    }

    function centerLeafletMapHistoriesOnMarker(lat, lng) {
        mapHistory.flyTo(new L.LatLng(lat, lng), 9);
    }

    // function centerLeafletMapHistoriesOnMarker(lat, lng) {
    //     mapHistory.flyTo(new L.LatLng(lat, lng), 13);
    // }

//TRACK
    function getDataShip() {
        $.ajax({
            type: 'get',
            url: "/admin/getDataShip",
            success: function (data) {
                let getDataShip = '';
                let getDataHistoryShip = '';
                let damask, jsonParse, lastSeeShip, damaskus = '';
                let timeShip, speed, latitude, longitude, key, heading = '';
                let king = 0;
                let topKing = 0;

                for (let i in data) {
                    damask = i;
                    if(i !== '') {
                        getDataShip = getDataShip + '<tr class="header2" style="background-color: #023342; color:#fff;"><td colspan="4"><input type="checkbox" id="top' + topKing + '" name="top' + topKing + '" checked="checked"/>&nbsp;' + damask + '</td> </tr>';

                        getDataHistoryShip = getDataHistoryShip + '<tr class="header2" style="background-color: #023342; color:#fff;"><td colspan="2" style ="height:19px; padding-left:5px; font-weight: 600;">' + damask + '</td></tr>';
                    }
                    for (const j in data[i]) {
                        if (j === "") {
                            damaskus = 'Unassigned terminals';
                        } else {
                            damaskus = j;
                        }
                        if(i !== '') {
                            getDataShip = getDataShip + '<tr class="header"><td colspan="4" style="padding-left: 7px"><input type="checkbox" id="top' + topKing + '" name="' + king + '" checked="checked"/>&nbsp;' + damaskus + '</td> </tr>';
                            getDataHistoryShip = getDataHistoryShip + '<tr class="header"><td colspan="2" style="padding-left: 10px" height:18px; font-weight: 600;>' + damaskus + '</td></tr>';
                        }else {
                            getDataShip = getDataShip + '<tr class="header"><td colspan="4" style="padding-left: 7px"><input type="checkbox" id="top' + topKing + '" name="' + king + '" checked="checked"/>&nbsp;' + damaskus + '</td> </tr>';
                            getDataHistoryShip = getDataHistoryShip + '<tr class="header" style="height:20px;"><td colspan="2" style="padding-left: 10px; font-weight: 600;">' + damaskus + '</td>/tr>';
                        }
                        for (const k in data[i][j]) {
                            if (data[i][j][k]['ship_history_ships_latest']) {
                                timeShip = Date.parse(data[i][j][k]['ship_history_ships_latest']['message_utc']) + 7 * 60 * 60 * 1000;
                                lastSeeShip = getTimeDifference(timeShip);

                                jsonParse = JSON.parse(data[i][j][k]['ship_history_ships_latest']['payload']);
                                for (const l in jsonParse['Fields']) {
                                    if (jsonParse['Fields'][l]['Name'].toLowerCase() === 'speed') {
                                        speed = (jsonParse['Fields'][l]['Value'] * 1).toFixed(1);
                                    }

                                    if (jsonParse['Fields'][l]['Name'].toLowerCase() === 'latitude') {
                                        latitude = (jsonParse['Fields'][l]['Value'] * 1).toFixed(4);
                                    }

                                    if (jsonParse['Fields'][l]['Name'].toLowerCase() === 'longitude') {
                                        longitude = (jsonParse['Fields'][l]['Value'] * 1).toFixed(4);
                                    }

                                    if (jsonParse['Fields'][l]['Name'].toLowerCase() === 'heading') {
                                        heading = (jsonParse['Fields'][l]['Value'] * 1).toFixed(1);
                                    }
                                }
                                key = data[i][j][k]['ship_ids'];
                                locations[key] = {};
                                locations[key]['id'] = data[i][j][k]['id'];
                                locations[key]['name'] = data[i][j][k]['name'];
                                locations[key]['eventTime'] = timeShip;
                                locations[key]['heading'] = heading ? heading : 0;
                                locations[key]['speed'] = speed ? speed : 0;
                                locations[key]['latitude'] = latitude;
                                locations[key]['longitude'] = longitude;
                            } else {
                                lastSeeShip = '-';
                                speed = 0;
                            }

                            speed = speed === undefined ? 0 : speed;
                            let checkbox = lastSeeShip == '-' ? '' : '<input type="checkbox" id="top' + topKing + '" name="' + king + '" value="' + data[i][j][k]['ship_ids'] + '" checked="checked"/>';
                            if (data[i][j][k]['name'] != null) {
                                if(i !== '') {
                                    getDataShip = getDataShip + '<tr class="row">' +
                                        '<td><span style="padding-left: 12px">' + checkbox + '</span></td>' +
                                        '<td><span style="padding-left: 0px">' + data[i][j][k]['name'] + ' </span></td>' +
                                        '<td style="padding-right: 0px" id="' + data[i][j][k]['ship_ids'] + '-last">' + lastSeeShip + '</td>' +
                                        '<td id="' + data[i][j][k]['ship_ids'] + '-speed">' + speed + '</td></tr>';

                                    getDataHistoryShip = getDataHistoryShip +
                                        '<tr class="row">' +
                                        '<td style="padding-left: 6px"><input type="checkbox" name="' + i + '" value="' + data[i][j][k]['ship_ids'] + '"/></td>' +
                                        '<td style="padding-left: 0px; font-weight: 600;">' + data[i][j][k]['name'] + '</td>' +
                                        '</tr>';
                                }else {
                                    getDataShip = getDataShip + '<tr class="row">' +
                                        '<td><span style="padding-left: 10px">' + checkbox + '</span></td>' +
                                        '<td><span style="padding-left: 0px">' + data[i][j][k]['name'] + ' </span></td>' +
                                        '<td style="padding-right: 10px" id="' + data[i][j][k]['ship_ids'] + '-last">' + lastSeeShip + '</td>' +
                                        '<td id="' + data[i][j][k]['ship_ids'] + '-speed">' + speed + '</td></tr>';

                                    getDataHistoryShip = getDataHistoryShip +
                                        '<tr class="row">' +
                                        '<td style="padding-left: 6px"><input type="checkbox" name="' + i + '" value="' + data[i][j][k]['ship_ids'] + '"/></td>' +
                                        '<td style="padding-left: 0px; font-weight: 600;">' + data[i][j][k]['name'] + '</td>' +
                                        '</tr>';
                                }
                            }
                        }
                        king++;
                    }
                    topKing++;
                }

                $('#shipData').html(getDataShip);
                $('#historyShipData').html(getDataHistoryShip);
                getDataMap();
                getMarker();
            },
        });
    }

    function getMarker() {
        map.addLayer(markers);
        for (let terminalId in locations) {
            let message = locations[terminalId];
            if (message.latitude != undefined && message.longitude != undefined) {
                let greenIcon = new LeafIcon({iconUrl: getIcon(message)});
                let rotation = message.speed > 0.49 ? Math.round(message.heading * 1) : 0;
                let popup = showInfoPopUp(message);
                let marker = L.marker([message.latitude, message.longitude],
                    {rotationAngle: rotation, icon: greenIcon});
                marker.bindPopup(popup, {"closeOnClick": null});
                marker.on('click', function (e) {
                    this.openPopup();
                });

                filterMarkers[terminalId] = marker;
                markers.addLayer(marker);
            }
        }
    }

    function showInfoPopUp(message) {
        let name = message.name ? message.name.toUpperCase() : message.id;
        let content = "<p><strong><u>" + name + "</u></strong></p>" +
            "<p><strong>Last:</strong> " + $.format.date(new Date(message.eventTime), "dd.MM.yyyy HH:mm:ss") + "</p>" +
            "<p><strong>Position:</strong> " + (message.latitude * 1).toFixed(4) + " S&nbsp;&nbsp;" + (message.longitude * 1).toFixed(4) + " E</p>" +
            "<p><strong>Speed:</strong> " + (message.speed * 1).toFixed(1) + " knots</p>" +
            "<p><strong>Heading</strong>: " + (message.heading * 1).toFixed(1) + "&deg;</p>";

        return content;
    }

    function getMarkerWithIds(x) {
        x = x || 0;
        x = (typeof x != 'undefined' && x instanceof Array) ? x : [x];
        map.addLayer(markers);
        for (let n in x) {
            if (typeof locations[x[n]] != 'undefined') {
                centerLeafletMapOnMarker(locations[x[n]].latitude, locations[x[n]].longitude);
                markers.addLayer(filterMarkers[x[n]]);
            }
        }
    }

    function deleteMarkerWithIds(x) {
        savePolyline.clearLayers();
        x = x || 0;
        x = (typeof x != 'undefined' && x instanceof Array) ? x : [x];
        for (let n in x) {
            if (typeof locations[x[n]] != 'undefined') {
                markers.removeLayer(filterMarkers[x[n]]);
            }
        }
    }

    $(".stopDrawing").click(function () {
        if ($('#checkAll').is(':checked')) {
            $("#tracking_table thead  input:checkbox[id=checkAll]").trigger("click");
        }
        $("#tracking_table thead  input:checkbox[id=checkAll]").prop("disabled", false);

        $('#tracking_table tbody tr.header input:checkbox').prop('disabled', false);
        $('#tracking_table tbody tr.header2 input:checkbox').prop('disabled', false);
        $(".startPoint").show();
        $(".stopDrawing").hide();
        drawPolylineStart = 0;
        if(lastDrawPoint) {
            deleteMarkerWithIds(lastDrawPoint);
            getMarkerWithIds(lastDrawPoint);
        }
    });

    $(".startPoint").click(function () {
        if ($('#checkAll').is(':checked')) {
            $("#tracking_table thead  input:checkbox[id=checkAll]").trigger("click");
        }
        $("#tracking_table thead  input:checkbox[id=checkAll]").prop("disabled", true);

        $("#tracking_table input:checkbox").not(this).prop("checked", false);
        $("#tracking_table tbody tr.row input:checkbox").trigger("change");

        $('#tracking_table tbody tr.header input:checkbox').prop('disabled', true);
        $('#tracking_table tbody tr.header2 input:checkbox').prop('disabled', true);

        $(".startPoint").hide();
        $(".stopDrawing").show();
        drawPolylineStart = 1;
    });

    function startPoint(e) {
        map.addLayer(savePolyline);
        let polygonDrawer = new L.Draw.Polyline(map);
        map.on('draw:created', function (e) {
            let type = e.layerType, layer = e.layer;
            savePolyline.addLayer(layer);
            drawLatLng[drawLatLng.length] = layer.getLatLngs();
            showPopUpSpeed();
        });

        polygonDrawer.enable();
        polygonDrawer.addVertex(drawLatLngInitial);

    }

    function showPopUpSpeed() {
        for (let i = 0; i < drawLatLng[0].length - 1; i++) {
            drawCountDistanceStartEndPoint = drawCountDistanceStartEndPoint + getDistance(drawLatLng[0][i].lat
                , drawLatLng[0][i].lng, drawLatLng[0][i + 1].lat, drawLatLng[0][i + 1].lng, "N");
        }

        let totalTime = convertDecimalToDate(drawCountDistanceStartEndPoint / drawSpeedValue);
        let totalDistance = (drawCountDistanceStartEndPoint * 1).toFixed(4) + ' Nautical Miles';
        //let html = 'Total distance ' + (drawCountDistanceStartEndPoint * 1).toFixed(4) + ' Nautical Miles <br> <br> ETA ' + totalTime;
        $('#totalTime').html(totalTime);
        $('#totalDistance').html(totalDistance);
        $('#box').show();
        drawPolylineStart = 0;

    }

    function getDistance(lat1, lon1, lat2, lon2, unit) {
        if ((lat1 == lat2) && (lon1 == lon2)) {
            return 0;
        } else {
            let radlat1 = Math.PI * lat1 / 180;
            let radlat2 = Math.PI * lat2 / 180;
            let theta = lon1 - lon2;
            let radtheta = Math.PI * theta / 180;
            let dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
            if (dist > 1) {
                dist = 1;
            }
            dist = Math.acos(dist);
            dist = dist * 180 / Math.PI;
            dist = dist * 60 * 1.1515;
            if (unit == "K") {
                dist = dist * 1.609344
            }
            if (unit == "N") {
                dist = dist * 0.8684
            }
            return dist;
        }
    }

    function convertDecimalToDate(decimalTimeString) {
        let decimalTime = parseFloat(decimalTimeString);
        decimalTime = decimalTime * 60 * 60;
        let hours = Math.floor((decimalTime / (60 * 60)));
        decimalTime = decimalTime - (hours * 60 * 60);
        let minutes = Math.floor((decimalTime / 60));
        decimalTime = decimalTime - (minutes * 60);
        let seconds = Math.round(decimalTime);
        if (hours < 10) {
            hours = "0" + hours;
        }
        if (minutes < 10) {
            minutes = "0" + minutes;
        }
        if (seconds < 10) {
            seconds = "0" + seconds;
        }

        let monthNames = [
            "January", "February", "March",
            "April", "May", "June", "July",
            "August", "September", "October",
            "November", "December"
        ];
        const date = new Date();
        date.setHours(date.getHours() + hours);
        date.setMinutes(date.getMinutes() + minutes);
        date.setSeconds(date.getSeconds() + seconds);


        let dateStr =
            ("00" + date.getDate()).slice(-2) + " " +
            monthNames[(date.getMonth())] + " " +
            date.getFullYear() + " " +
            ("00" + date.getHours()).slice(-2) + ":" +
            ("00" + date.getMinutes()).slice(-2) + ":" +
            ("00" + date.getSeconds()).slice(-2);

        return dateStr;
    }

    $("#addPoints").submit(function (e) {
        e.preventDefault();
        drawSpeedValue = $('#speedPolyline').val();
        $('#speedCount').modal('toggle');
        startPoint();
    });

    $("#checkAll").click(function () {
        $("#tracking_table input:checkbox").not(this).prop("checked", this.checked);
        $("#tracking_table tbody tr.row input:checkbox").trigger("change");
        if ($(this).prop("checked") === true) {
            getMarker();
        } else {
            markers.clearLayers();
        }
    });

    $(document).on("click", "#tracking_table tbody tr.header input:checkbox", function () {
        let checkbox_selector = "#tracking_table input[name='" + $(this).attr("name") + "']";
        $(checkbox_selector).not(this).prop("checked", this.checked);
        $(checkbox_selector).trigger("change");

        let getTerminalId = [];
        $.each($("#tracking_table input[name='" + $(this).attr("name") + "']"), function () {
            getTerminalId.push($(this).val());

        });
        getTerminalId.indexOf('on') !== -1 && getTerminalId.splice(getTerminalId.indexOf('on'), 1);
        if ($(checkbox_selector).prop("checked") === true) {
            getMarkerWithIds(getTerminalId);
        } else {
            deleteMarkerWithIds(getTerminalId);
        }
    });

    $(document).on("click", "#tracking_table tbody tr.header2 input:checkbox", function () {
        let checkbox_selector = "#tracking_table input[id='" + $(this).attr("id") + "']";
        $(checkbox_selector).not(this).prop("checked", this.checked);
        $(checkbox_selector).trigger("change");

        let getTerminalId = [];
        $.each($("#tracking_table input[id='" + $(this).attr("id") + "']"), function () {
            getTerminalId.push($(this).val());
        });

        getTerminalId.indexOf('on') !== -1 && getTerminalId.splice(getTerminalId.indexOf('on'), 1);
        if ($(checkbox_selector).prop("checked") === true) {
            getMarkerWithIds(getTerminalId);
        } else {
            deleteMarkerWithIds(getTerminalId);
        }
    });

    $(document).on("click", "#tracking_table tbody tr.row", function () {
        let id = $("input:checkbox", this).val();
        if (typeof locations[id] != "undefined") {
            filterMarkers[id].openPopup();
        } else {
            $("#tracking_table tbody  input:checkbox[value=" + id + "]").trigger("click");
        }
    });


    $(document).on("click", "#tracking_table tbody tr.row input:checkbox", function () {
        let id = $(this).val();
        let checked = $(this).is(":checked");
        lastDrawPoint = id;
        if (checked) {
            getMarkerWithIds(id);
            if (drawPolylineStart === 1) {
                $('#tracking_table tbody tr.row input:checkbox').prop('disabled', true);
                $('#tracking_table tbody tr.row input:checkbox[value="' + id + '"]').prop('disabled', false);
                $("#floating-panel .close").trigger("click");
                $('#speedCount').modal('toggle');
                drawLatLngInitial = L.latLng(locations[id].latitude, locations[id].longitude);
            }
        } else {
            $('#box').hide();
            deleteMarkerWithIds(id);

            if (drawPolylineStart === 1) {
                $('#tracking_table tbody tr.row input:checkbox').prop('disabled', false);
            }
        }
    });

    function getIcon(message) {
        let yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);

        let oneHoursBefore = new Date();
        oneHoursBefore.setHours(oneHoursBefore.getHours() - 1);

        let notActivityMoreThan24h = message.eventTime <= yesterday.getTime();
        let notActivityMoreThan1h = message.eventTime <= oneHoursBefore.getTime();

        let speedMoreThen05 = notActivityMoreThan24h ? '/images/kapal-go-red.png' : notActivityMoreThan1h ? '/images/kapal-go-orange.png' : '/images/kapal-go-green.png';
        let speedLessThen05 = notActivityMoreThan24h ? '/images/kapal-stop-red.png' : notActivityMoreThan1h ? '/images/kapal-stop-orange.png' : '/images/kapal-stop-green.png';

        return message.speed > 0.5 ? speedMoreThen05 : speedLessThen05;
    }

    getDataShip();

//History
    $("#downloadCSV").click(function () {
        let data = [["ID",
            "Event Time",
            "Ship Id",
            "Ship Name",
            "Latitude",
            "Longitude",
            "Speed",
            "Heading"]];

        for (let terminalId in locations) {
            let message = locations[terminalId];
            if (message.path) {
                let histories = message.histories;
                let path = [];
                $.each(histories, function (i, history) {
                    let nextDay = new Date(endDate);
                    nextDay.setDate(endDate.getDate() + 1);
                    let timeShip = Date.parse(history['message_utc']) + 7 * 60 * 60 * 1000;
                    if (timeShip > startDate.getTime() && timeShip < nextDay.getTime()) {
                        let jsonParse = JSON.parse(history['payload']);
                        let speed, latitude, longitude, heading;
                        for (const k in jsonParse['Fields']) {
                            if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'speed') {
                                speed = (jsonParse['Fields'][k]['Value'] * 1).toFixed(1);
                            }

                            if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'latitude') {
                                latitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                            }

                            if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'longitude') {
                                longitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                            }

                            if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'heading') {
                                heading = (jsonParse['Fields'][k]['Value'] * 1).toFixed(1);
                            }
                        }
                        data.push([history['id'],
                            '"' + $.format.date(new Date(timeShip), "dd.MM.yyyy HH:mm:ss") + '"',
                            '"' + history['ship_ids'] + '"',
                            '"' + (history['name'] ? history['name'] : '') + '"',
                            latitude,
                            longitude,
                            speed,
                            heading
                        ]);
                    }
                });
            }
        }

        let csvContent = "";
        data.forEach(function (infoArray, index) {
            let dataString = infoArray.join(",");
            csvContent += index < data.length ? dataString + "" : dataString;
        });

        if (window.navigator.msSaveOrOpenBlob) {
            let blobObject = new Blob(["\ufeff" + csvContent]);
            window.navigator.msSaveOrOpenBlob(blobObject, "terminal-messages.csv");
        } else {
            let link = document.createElement("a");
            link.setAttribute("href", encodeURI("data:text/csv;charset=utf-8," + csvContent));
            link.setAttribute("download", "terminal-messages.csv");
            document.body.appendChild(link); // Required for FF

            link.click();
        }
    });

    function showHistories(terminalId) {
        let selectedTR = $("#history_table tr.row").has("input:checkbox[value=" + terminalId + "]");

        if (selectedTR.next().length > 0 && !(selectedTR.next().hasClass("row") || selectedTR.next().hasClass("header"))) {
            return;
        }

        selectedTR.addClass("checked");
        let histories_html = "<tr><td></td><td><div class=\"inner-table\" style='line-height: 23px;'>";
        $.each(locations[terminalId].histories, function (i, history) {
            let nextDay = new Date(endDate);
            nextDay.setDate(endDate.getDate() + 1);
            let timeShip = Date.parse(history['message_utc']) + 7 * 60 * 60 * 1000;
            let speed, latitude, longitude;
            let jsonParse = JSON.parse(history['payload']);
            for (const k in jsonParse['Fields']) {
                if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'speed') {
                    speed = (jsonParse['Fields'][k]['Value'] * 1).toFixed(1);
                }
                if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'latitude') {
                    latitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                }

                if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'longitude') {
                    longitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                }
            }

            if (timeShip > startDate.getTime() && timeShip < nextDay.getTime() && typeof (latitude) !== 'undefined'
                && typeof (longitude) !== 'undefined') {
                histories_html += "<div class=\"inner-table-row\">";
                histories_html += '<div class="inner-table-icon-cell"><input type="checkbox" name="' + i + '" value="' + history['ship_ids'] + '"/></div>';
                histories_html += "<div class=\"inner-table-icon-cell\"></div>";
                histories_html += "<div class=\"inner-table-date-cell\">" + $.format.date(new Date(timeShip), "dd.MM.yyyy HH:mm:ss") + "</div>";
                histories_html += "<div>" + (speed * 0.1).toFixed(1) + " knots</div>";
                histories_html += "</div>";
            }
        });
        histories_html += "</div></td></tr>";

        selectedTR.after(histories_html);
    }

    function createPath(terminalId) {
        let histories = locations[terminalId].histories;
        let path = [];
        let historiesMarkers = [];
        let poliline = [];

        mapHistory.addLayer(markersHistory);
        $.each(histories, function (i, history) {
            let nextDay = new Date(endDate);
            nextDay.setDate(endDate.getDate() + 1);
            let timeShip = Date.parse(history['message_utc']) + 7 * 60 * 60 * 1000;
            let speed, latitude, longitude, heading;
            if (timeShip > startDate.getTime() && timeShip < nextDay.getTime()) {
                let jsonParse = JSON.parse(history['payload']);
                for (const k in jsonParse['Fields']) {
                    if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'speed') {
                        speed = (jsonParse['Fields'][k]['Value'] * 1).toFixed(1);
                    }

                    if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'latitude') {
                        if (typeof (jsonParse['Fields'][k]['Value']) !== 'undefined') {
                            latitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                        }
                    }

                    if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'longitude') {
                        if (typeof (jsonParse['Fields'][k]['Value']) !== 'undefined') {
                            longitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                        }
                    }

                    if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'heading') {
                        heading = (jsonParse['Fields'][k]['Value'] * 1).toFixed(1);
                    }
                }

                if (typeof (latitude) !== 'undefined' && typeof (longitude) !== 'undefined') {
                    path = {};
                    path['id'] = history['id'];
                    path['name'] = history['name'];
                    path['eventTime'] = timeShip;
                    path['heading'] = heading ? heading : 0;
                    path['speed'] = speed;
                    path['latitude'] = latitude;
                    path['longitude'] = longitude;
                }

                let greenIcon = new LeafIcon({iconUrl: getIcon(path)});
                let rotation = speed > 0.5 ? Math.round(heading * 0.7) : 0;
                let popup = showInfoPopUp(path);
                let markerHistory;
                if (typeof (latitude) !== 'undefined' && typeof (longitude) !== 'undefined') {
                    poliline[poliline.length] = new L.LatLng(latitude, longitude);

                    if (histories.length > 0 && i === 0) {
                        markerHistory = L.circle([latitude, longitude], {
                            color: '#000000',
                            fillColor: '#0000FF',
                            fillOpacity: 1,
                            radius: 500
                        });
                    } else if (histories.length > 1 && histories.length === i + 1) {
                        markerHistory = L.marker([latitude, longitude], {
                            rotationAngle: rotation, icon: greenIcon
                        });
                    } else {
                        markerHistory = L.circle([latitude, longitude], {
                            color: '#000000',
                            fillColor: '#ff0000',
                            fillOpacity: 1,
                            radius: 500
                        });
                    }

                    markerHistory.bindPopup(popup, {"closeOnClick": null});
                    markerHistory.on('click', function (e) {
                        this.openPopup();
                    });

                    markersHistory.addLayer(markerHistory);
                    historiesMarkers[i] = markerHistory;
                } else {
                    historiesMarkers[i] = {};
                }
            }
        });
        locations[terminalId].historiesMarkers = historiesMarkers;
        locations[terminalId].path = new L.polyline(poliline, {
            color: "#0000FF",
            weight: 2,
            opacity: 0.8
        }).addTo(mapHistory);

    }

    function removeHistories(terminalId) {
        let selectedTR = $("#history_table tr.row").has("input:checkbox[value=" + terminalId + "]");

        if (selectedTR.next().hasClass("row")) {
            return;
        }

        selectedTR.removeClass("checked");

        selectedTR.next().remove();
    }

    $(document).on("click", "#history_table tbody tr.row input:checkbox", function () {
        let id = $(this).val();
        let selectedMessage = locations[id];

        if (selectedMessage) {
            if (!selectedMessage.path) {
                if (selectedMessage.histories) {
                    createPath(id);
                    showHistories(id);
                } else {
                    let terminal_messages_url = "/admin/getDataHistoryShipById/" + id;
                    $.getJSON(terminal_messages_url, function (data) {
                        if (data.length > 0) {
                            let terminalId = data[0].ship_ids;
                            locations[terminalId].histories = data;
                            createPath(terminalId);
                            showHistories(id);
                        }
                    });
                }
            } else {
                let checked = $(this).is(":checked");
                selectedMessage.path.remove(checked);
                $.each(selectedMessage.historiesMarkers, function (i, marker) {
                    if (checked)
                        markersHistory.addLayer(marker);
                    else
                        markersHistory.removeLayer(marker);
                });

                if (checked) {
                    showHistories(id);
                } else {
                    removeHistories(id);
                }
            }
        }
    });

    $(document).on("click", "#history_table tbody tr.row", function () {
        let id = $("input:checkbox", this).val();
        let selectedMessage = locations[id];
        if (selectedMessage) {
            if (selectedMessage.path) {
                centerLeafletMapHistoriesOnMarker(locations[id].latitude, locations[id].longitude);
                selectedMessage.historiesMarkers[selectedMessage.historiesMarkers.length - 1].openPopup();
            }
        }
    });

    function searchForId(name, shipId, array) {
        for (let i in array) {
            if (array[i][0] === name && array[i][1] == shipId) {
                return i;
            }
        }
        return null;
    }

    $(document).on("click", "#history_table tbody tr .inner-table .inner-table-row .inner-table-icon-cell input:checkbox", function () {
        let id = $(this).val();
        let name = $(this).attr("name");
        let selectedMessage = locations[id];
        let checked = $(this).is(":checked");
        if (selectedMessage) {
            if (selectedMessage.path) {
                if (checked) {
                    let latitude, longitude;
                    let jsonParse = JSON.parse(selectedMessage.histories[name].payload);
                    for (const k in jsonParse['Fields']) {
                        if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'latitude') {
                            latitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                        }

                        if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'longitude') {
                            longitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                        }
                    }

                    centerLeafletMapHistoriesOnMarker(latitude, longitude);
                    average_speed.push([name, selectedMessage.histories[name].id, selectedMessage.histories[name].message_utc]);
                    selectedMessage.historiesMarkers[name].openPopup();
                } else {
                    let findId = searchForId(name, selectedMessage.histories[name].id, average_speed);
                    average_speed.splice(findId, 1);
                    selectedMessage.historiesMarkers[name].closePopup();
                }
            }
        }
        if(average_speed.length == 0){
            $('#averageSpeedTime').hide();
        }
        if (average_speed.length > 1 && average_speed.length % 2 === 0) {
            $.ajax({
                type: 'get',
                url: "/admin/getAverageSpeed/" + JSON.stringify(average_speed),
                success: function (data) {
                    if (data) {
                        let html = '<table class="table" align="center" style="text-align: left; font-size: 1em; min-width: 200px"><thead> <tr> <th width="50%">Name</th>' +
                            '<th width="50%">Speed</th></tr></thead><tbody>';
                        for (let i in data) {
                            html = html + '<tr><td>' + data[i].name + '</td><td>' + data[i].speed + ' knots</td></tr>';
                        }
                        html = html + '</tbody>' + '</table>';
                        $('#titleAverage').html('Average Speed Of The Ship');
                        $('#totalAverage').html(html);
                        $('#averageSpeedTime').show();

                        // Swal.fire({
                        //     title: '<h3>Average Speed Of The Ship</h3>',
                        //     html: html,
                        //     confirmButtonText: 'Close',
                        // });
                    }
                }
            });
        }
    });

    function updateTrackingInfo(terminalId, lastUpdate, speed) {
        $("#" + terminalId + "-last").text(getTimeDifference(lastUpdate));

        let speedInfo = $("#" + terminalId + "-speed");
        if (speed == 0) {
            speedInfo.text(0);
        } else {
            speedInfo.text((speed * 0.1).toFixed(1));
        }
    }

    setInterval(function () {
        (function () {
            $.getJSON("/admin/getDataShip/", function (data) {
                markers.clearLayers();
                for (let i in data) {
                    for (const j in data[i]) {
                        if (data[i][j]['ship_history_ships_latest'].length > 0) {
                            let timeShip = Date.parse(data[i][j]['ship_history_ships_latest'][0]['message_utc']) + 7 * 60 * 60 * 1000;
                            let location = locations[data[i][j].ship_ids];
                            let jsonParse = JSON.parse(data[i][j]['ship_history_ships_latest'][0]['payload']);
                            let speed, latitude, longitude, heading;
                            for (const k in jsonParse['Fields']) {
                                if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'speed') {
                                    speed = (jsonParse['Fields'][k]['Value'] * 1).toFixed(1);
                                }

                                if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'latitude') {
                                    latitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                                }

                                if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'longitude') {
                                    longitude = (jsonParse['Fields'][k]['Value'] * 1).toFixed(4);
                                }

                                if (jsonParse['Fields'][k]['Name'].toLowerCase() === 'heading') {
                                    heading = (jsonParse['Fields'][k]['Value'] * 1).toFixed(1);
                                }
                            }

                            if (location['id'] != data[i][j]['id']) {
                                location['id'] = data[i][j]['id'];
                                location['name'] = data[i][j]['name'];
                                location['eventTime'] = timeShip;
                                location['heading'] = heading ? heading : 0;
                                location['speed'] = speed;
                                location['latitude'] = latitude;
                                location['longitude'] = longitude;
                            }
                            let getChecked = $('#checkAll:checked').length;

                            let greenIcon = new LeafIcon({iconUrl: getIcon(locations[data[i][j].ship_ids])});
                            let rotation = speed > 0.5 ? Math.round(heading * 0.7) : 0;
                            let popup = showInfoPopUp(location);

                            let marker = L.marker([latitude, longitude],
                                {rotationAngle: rotation, icon: greenIcon});
                            marker.bindPopup(popup, {"autoClose": false, "closeOnClick": null});
                            filterMarkers[data[i][j].ship_ids] = marker;
                            let checked = [];
                            $('#tracking_table tbody tr.row input:checkbox:checked').each(function () {
                                checked.push($(this).val());
                            });

                            if (getChecked > 0 || checked.includes(data[i][j].ship_ids)) {
                                getMarkerWithIds(data[i][j].ship_ids);
                            }
                            updateTrackingInfo(data[i][j].ship_ids, timeShip, speed);
                        }
                    }
                }
            });

            $.each(locations, function (id, location) {
                if (location.histories) {
                    $.getJSON("/admin/getDataHistoryShipById/" + id, function (data) {
                        if (data.length > 0) {
                            let thisId = data[0]['ship_ids'];
                            let thisLocation = locations[thisId];
                            let maxIdNew = Math.max.apply(Math, data.map(function (msg) {
                                return msg.id;
                            }));
                            let maxIdOld = Math.max.apply(Math, thisLocation.histories.map(function (msg) {
                                return msg.id;
                            }));
                            if (maxIdNew != maxIdOld) {
                                thisLocation.histories = data;
                                let path = thisLocation.path;
                                if (path) {
                                    markersHistory.removeLayer(path);
                                    delete thisLocation.path;
                                    createPath(thisId);
                                    removeHistories(thisId);
                                    showHistories(thisId);
                                }
                            }
                        }
                    });
                }
            });
        })();
    }, 900000);
});
