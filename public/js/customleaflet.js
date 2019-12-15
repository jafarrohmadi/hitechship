$(document).ready(function () {
    let locations = {};
    let LeafIcon = L.Icon.extend({
        options: {
            iconSize: [17, 17],
            shadowSize: [10, 12],
            iconAnchor: [22, 94],
            shadowAnchor: [4, 62],
            popupAnchor: [-3, -76]
        }
    });

    let map, mapHistory;
    let markers = new L.FeatureGroup();
    let filterMarkers = [];

//Set Awal
    (function () {
        [].slice.call(document.querySelectorAll('.tabs')).forEach(function (el) {
            new CBPFWTabs(el);
        });
    })();

    $("#tab-track").click(function () {
        $("#googleMap").show();
        $("#googleMapHistory").hide();
    });

    $("#tab-history").click(function () {
        $("#googleMapHistory").show();
        $("#googleMap").hide();
        getDataMapHistory();
    });


    $(".datepicker").datepicker({
        dateFormat: 'dd.mm.yy',
        autoSize: true,
        firstDay: 1,
        constrainInput: true
    });

    var endDate = new Date();
    endDate.setHours(0, 0, 0, 0);
    var startDate = endDate;

    $(".datepicker.startDate").datepicker("setDate", startDate);
    $(".datepicker.endDate").datepicker("setDate", endDate);

    $("#setDate").click(function () {
        endDate = $(".datepicker.endDate").datepicker("getDate");
        startDate = $(".datepicker.startDate").datepicker("getDate");
        $("div.inner-table").closest("tr").remove();
        for (var terminalId in locations) {
            var message = locations[terminalId];
            if (message.path) {
                message.path.setMap(null);

                $.each(message.historiesMarkers, function (i, marker) {
                    marker.setMap(null);
                });
                delete message.historiesMarkers;

                if (message.path.getVisible()) {
                    createPath(terminalId);
                    showHistories(terminalId);
                } else {
                    delete message.path;
                }
            }
        }
    });


    $('#floating-panel div.close').on('click', function () {
        var $this = $("#floating-panel");
        if ($this.hasClass('open')) {
            $this.animate({
                left: 0
            }).removeClass('open');
            $('#floating-panel div.close i').removeClass("fa-angle-double-right");
            $('#floating-panel div.close i').addClass("fa-angle-double-left");
        } else {
            $this.animate({
                left: '-425px'
            }).addClass('open');
            $('#floating-panel div.close i').removeClass("fa-angle-double-left");
            $('#floating-panel div.close i').addClass("fa-angle-double-right");
        }
    });

    function getDataMap() {
        map = L.map('googleMap', {center: [0, 118.8230631], zoom: 5});

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

    }

    function getDataMapHistory() {
        mapHistory = L.map('googleMapHistory', {center: [0, 118.8230631], zoom: 5});
        
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapHistory);

    }
//TRACK
    function getDataShip() {
        $.ajax({
            type: 'get',
            url: "/admin/getDataShip",
            success: function (data) {
                let getDataShip = '';
                let getDataHistoryShip = '';
                let damask, jsonParse, lastSeeShip = '';
                let timeShip, speed, latitude, longitude, key = '';
                let k = 0;
                for (var i in data) {
                    if (i === "") {
                        damask = 'Unassigned terminals';
                    } else {
                        damask = i;
                    }

                    getDataShip = getDataShip + '<tr class="header"><td><input type="checkbox" name="' + k + '" checked="checked"/></td> <td colspan="3">' + damask + '</td> </tr>';

                    getDataHistoryShip = getDataHistoryShip + '<tr class="header"><td></td><td>' + damask + '</td></tr>';

                    for (const j in data[i]) {
                        if (data[i][j]['ship_history_ships_latest'].length > 0) {
                            timeShip = Date.parse(data[i][j]['ship_history_ships_latest'][0]['message_utc']) + 7*60*60*1000;
                            lastSeeShip = getTimeDifference(timeShip);
                           
                            jsonParse = JSON.parse(data[i][j]['ship_history_ships_latest'][0]['payload']);
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
                                    heading = (jsonParse['Fields'][k]['Value'] * 0.1).toFixed(1);
                                }
                            }
                            key = data[i][j]['ship_ids'];
                            locations[key] = {};
                            locations[key]['id'] = data[i][j]['id'];
                            locations[key]['name'] = data[i][j]['name'];
                            locations[key]['eventTime'] = timeShip;
                            locations[key]['heading'] = heading;
                            locations[key]['speed'] = speed;
                            locations[key]['latitude'] = latitude;
                            locations[key]['longitude'] = longitude;
                        } else {
                            lastSeeShip = '-';
                            speed = 0;
                        }

                        getDataShip = getDataShip + '<tr class="row">' +
                            '<td><input type="checkbox" name="' + k + '" value="' + data[i][j]['ship_ids'] + '" checked="checked"/></td>' +
                            '<td>' + data[i][j]['name'] + ' </td>' +
                            '<td id="' + data[i][j]['ship_ids'] + '-last">' + lastSeeShip + '</td>' +
                            '<td id="' + data[i][j]['ship_ids'] + '-speed">' + speed + '</td></tr>';
                        getDataHistoryShip = getDataHistoryShip +
                            '<tr class="row">' +
                                '<td><input type="checkbox" name="' + i + '" value="' + data[i][j]['ship_ids'] + '"/></td>' +
                                '<td>' + data[i][j]['name'] + '</td>' +
                            '</tr>';
                    }
                    k++;
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
            let greenIcon = new LeafIcon({iconUrl: getIcon(message)});
            let rotation = message.speed > 0 ? Math.round(message.heading * 0.1) : 0;
            let popup = showInfoPopUp(message);
            let marker = L.marker([message.latitude, message.longitude],
                {rotationAngle: rotation, icon: greenIcon});
            marker.bindPopup(popup);
            filterMarkers[terminalId] = marker;
            markers.addLayer(marker);
        }
    }

    function showInfoPopUp(message) {
        var name = message.name ? message.name : message.id;
        var content = "<p><strong><u>" + name + "</u></strong></p>" +
            "<p><strong>Last:</strong> " + $.format.date(new Date(message.eventTime), "dd.MM.yyyy HH:mm:ss") + "</p>" +
            "<p><strong>Position:</strong> " + (message.latitude * 1).toFixed(4) + " S&nbsp;&nbsp;" + (message.longitude * 1).toFixed(4) + " E</p>" +
            "<p><strong>Speed:</strong> " + (message.speed * 1).toFixed(1) + " knots</p>" +
            "<p><strong>Heading</strong>: " + (message.heading * 0.1).toFixed(1) + "&deg;</p>";

        return content;
    }

    function getMarkerWithIds(x) {
        x = x || 0;
        x = (typeof x != 'undefined' && x instanceof Array) ? x : [x];
        map.addLayer(markers);
        for(let n in x) {
            if(typeof locations[x[n]] != 'undefined') {
                markers.addLayer(filterMarkers[x[n]]);
            }
        }
    }

    function deleteMarkerWithIds(x) {
        x = x || 0;
        x = (typeof x != 'undefined' && x instanceof Array) ? x : [x];
        for(let n in x) {
            if(typeof locations[x[n]] != 'undefined') {
                markers.removeLayer(filterMarkers[x[n]]);
            }
        }
    }

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

    $(document).on("click", "#tracking_table tbody tr.row", function () {
        let id = $("input:checkbox", this).val();
        if (typeof locations[id] != "undefined") {
            filterMarkers[id].openPopup();
        } else {
            $(document).on("click", "#tracking_table tbody  input:checkbox[value=" + id + "]").trigger("click");
        }
    });

    $(document).on("click", "#tracking_table tbody tr.row input:checkbox", function () {
        let id = $(this).val();
        let checked = $(this).is(":checked");
        if (checked) {
            getMarkerWithIds(id);
        } else {
            deleteMarkerWithIds(id);
        }
    });

    function getIcon(message) {
        var yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);

        var oneHoursBefore = new Date();
        oneHoursBefore.setHours(oneHoursBefore.getHours() - 1);

        var notActivityMoreThan24h = message.eventTime <= yesterday.getTime();
        var notActivityMoreThan1h = message.eventTime <= oneHoursBefore.getTime();

        var speedMoreThen05 = notActivityMoreThan24h ? '/images/0.5red-ship.png' : notActivityMoreThan1h ? '/images/0.5orange-ship.png' : '/images/0.5green-ship.png';
        var speedLessThen05 = notActivityMoreThan24h ? '/images/0.05red-ship.png' : notActivityMoreThan1h ? '/images/0.05orange-ship.png' : '/images/0.05green-ship.png';

        return message.speed > 0.5 ? speedMoreThen05 : speedLessThen05;
    }

    getDataShip();

//History
    function createPath(terminalId) {
        var histories = locations[terminalId].histories;

        var path = [];
        var historiesMarkers = [];
        $.each(histories, function (i, history) {
            var nextDay = new Date(endDate);
            nextDay.setDate(endDate.getDate() + 1);
            if (history.eventTime > startDate.getTime() && history.eventTime < nextDay.getTime()) {
                var latLng = new google.maps.LatLng(history.position.latitude, history.position.longitude);
                path[path.length] = latLng;

                var historyMarker = new google.maps.Marker({
                    position: new google.maps.LatLng(history.position.latitude, history.position.longitude),
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 4,
                        fillColor: "#ff0000",
                        strokeColor: "#000000",
                        fillOpacity: 1,
                        strokeWeight: 2
                    },
                    map: mapHistory
                });

                var historyInfoWindow = new google.maps.InfoWindow({
                    content: "<p><strong>Time:</strong> " + $.format.date(new Date(history.eventTime), "dd.MM.yyyy HH:mm:ss") + "</p>" +
                        "<p><strong>Position:</strong> " + history.position.latitude.toFixed(4) + " S&nbsp;&nbsp;" + history.position.longitude.toFixed(4) + " E</p>" +
                        "<p><strong>Speed:</strong> " + (history.speed * 0.1).toFixed(1) + " knots</p>" +
                        "<p><strong>Heading</strong>: " + (history.heading * 0.1).toFixed(1) + "&deg;</p>"
                });

                google.maps.event.addListener(historyMarker, 'mouseover', function () {
                    historyInfoWindow.open(mapHistory, historyMarker);
                });
                google.maps.event.addListener(historyMarker, 'mouseout', function () {
                    historyInfoWindow.close();
                });

                historiesMarkers[historiesMarkers.length] = historyMarker;
            }
        });
        locations[terminalId].historiesMarkers = historiesMarkers;

        if (historiesMarkers.length > 0) {
            historiesMarkers[0].setIcon({
                path: google.maps.SymbolPath.CIRCLE,
                scale: 6,
                fillColor: "#0000FF",
                strokeColor: "#000000",
                fillOpacity: 1,
                strokeWeight: 2
            });
        }

        if (historiesMarkers.length > 1) {
            historiesMarkers[historiesMarkers.length - 1].setIcon(
                getVesselIcon(locations[terminalId])
            );
        }

        locations[terminalId].path = new google.maps.Polyline({
            path: path,
            strokeColor: "#0000FF",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            map: mapHistory
        });
        mapHistory.setCenter(path[path.length - 1]);
    }

    function showHistories(terminalId) {
        var selectedTR = $("#history_table tr.row").has("input:checkbox[value=" + terminalId + "]");

        if (selectedTR.next().length > 0 && !(selectedTR.next().hasClass("row") || selectedTR.next().hasClass("header"))) {
            return;
        }

        selectedTR.addClass("checked");
        var histories_html = "<tr><td></td><td><div class=\"inner-table\">";
        $.each(locations[terminalId].histories, function (i, history) {
            var nextDay = new Date(endDate);
            nextDay.setDate(endDate.getDate() + 1);
            if (history.eventTime > startDate.getTime() && history.eventTime < nextDay.getTime()) {
                histories_html += "<div class=\"inner-table-row\">";
                histories_html += "<div class=\"inner-table-icon-cell\"><i class=\"fa fa-compass\"></i></div>";
                histories_html += "<div class=\"inner-table-date-cell\">" + $.format.date(new Date(history.eventTime), "dd.MM.yyyy HH:mm:ss") + "</div>";
                histories_html += "<div>" + (history.speed * 0.1).toFixed(1) + " knots</div>";
                histories_html += "</div>";
            }
        });
        histories_html += "</div></td></tr>";

        selectedTR.after(histories_html);
    }

    function removeHistories(terminalId) {
        var selectedTR = $("#history_table tr.row").has("input:checkbox[value=" + terminalId + "]");

        if (selectedTR.next().hasClass("row")) {
            return;
        }

        selectedTR.removeClass("checked");

        selectedTR.next().remove();
    }

    function setCenter(loc) {
        var lastPosition = loc.path.getPath().getArray()[loc.path.getPath().getArray().length - 1];
        mapHistory.setCenter(lastPosition);
    }
    $(document).on("click", "#history_table tbody tr.row input:checkbox", function () {
        var id = $(this).val();
        var selectedMessage = locations[id];

        if (selectedMessage) {
            if (!selectedMessage.path) {
                if (selectedMessage.histories) {
                    createPath(id);
                    showHistories(id);
                } else {
                    var terminal_messages_url = "/app/terminal/" + id + "/terminal_messages/";
                    $.getJSON(terminal_messages_url, function (data) {
                        if (data.length > 0) {
                            var terminalId = data[0].terminalId;
                            locations[terminalId].histories = data;
                            createPath(terminalId);
                            showHistories(id);
                        }
                    });
                }
            } else {
                var checked = $(this).is(":checked");
                selectedMessage.path.setVisible(checked);
                $.each(selectedMessage.historiesMarkers, function (i, marker) {
                    if (checked) marker.setMap(mapHistory);
                    else marker.setMap(null);
                });

                if (checked) {
                    setCenter(selectedMessage);
                    showHistories(id);
                } else {
                    removeHistories(id);
                }
            }
        }
    });

    $("#history_table tbody tr.row").click(function () {
        var id = $("input:checkbox", this).val();
        var selectedMessage = locations[id];
        if (selectedMessage) {
            if (selectedMessage.path && selectedMessage.path.getVisible()) {
                setCenter(selectedMessage);
            } else {
                $("#history_table tbody input:checkbox[value=" + id + "]").trigger("click");
            }
        }
    });

    function getTimeDifference(fromDate) {
        if (!fromDate) {
            return "-";
        }

        var toDate = new Date().getTime();

        var seconds = Math.round((toDate - fromDate) / 1000);
        var minutes = 0, hours = 0, days = 0, weeks = 0, months = 0, years = 0;

        var result = seconds + "s";

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

    function updateTrackingInfo(terminalId, lastUpdate, speed) {
        $("#" + terminalId + "-last").text(getTimeDifference(lastUpdate));

        var speedInfo = $("#" + terminalId + "-speed");
        if (speed == 0) {
            speedInfo.text(0);
        } else {
            speedInfo.text((speed * 0.1).toFixed(1));
        }
    }

    setInterval(function () {
        (function () {
            $.getJSON("/app/terminal_messages/", function (data) {
                $.each(data, function (i, msg) {
                    var location = locations[msg.terminalId];
                    if (location.id != msg.id) {
                        location.id = msg.id;
                        location.name = msg.vesselName;
                        location.eventTime = msg.eventTime;
                        location.heading = msg.heading;
                        location.speed = msg.speed;
                        location.latitude = msg.position.latitude;
                        location.longitude = msg.position.longitude;
                    }
                    location.marker.setPosition(new google.maps.LatLng(location.latitude, location.longitude));
                    location.marker.setIcon(getVesselIcon(location));

                    updateTrackingInfo(msg.terminalId, msg.eventTime, msg.speed);
                });
            });

            $.each(locations, function (id, location) {
                if (location.histories) {
                    $.getJSON("/app/terminal/" + id + "/terminal_messages/", function (data) {
                        if (data.length > 0) {
                            var thisId = data[0].terminalId;
                            var thisLocation = locations[thisId];
                            var maxIdNew = Math.max.apply(Math, data.map(function (msg) {
                                return msg.id;
                            }));
                            var maxIdOld = Math.max.apply(Math, thisLocation.histories.map(function (msg) {
                                return msg.id;
                            }));
                            if (maxIdNew != maxIdOld) {
                                thisLocation.histories = data;
                                var path = thisLocation.path;
                                if (path) {
                                    path.setMap(null);
                                    delete thisLocation.path;

                                    if (path.getVisible()) {
                                        createPath(thisId);
                                        removeHistories(thisId);
                                        showHistories(thisId);
                                    }
                                }
                            }
                        }
                    });
                }
            });
        })();
    }, 600000);
});
