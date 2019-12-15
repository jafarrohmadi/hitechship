$(document).ready(function(){
	$('#floating-panel div.close').on('click', function(){
			var $this = $("#floating-panel");
			if ($this.hasClass('open')) {
			$this.animate({
				left : 0
			}).removeClass('open');
			$('#floating-panel div.close i').removeClass("fa-angle-double-right");
			$('#floating-panel div.close i').addClass("fa-angle-double-left");
		} else {
			$this.animate({
				left : '-425px'
			}).addClass('open');
			$('#floating-panel div.close i').removeClass("fa-angle-double-left");
			$('#floating-panel div.close i').addClass("fa-angle-double-right");
		}
	});

	(function() {
		[].slice.call( document.querySelectorAll( '.tabs' ) ).forEach( function( el ) {
			new CBPFWTabs( el );
		});
	})();

	$("#downloadCSV").click(function() {
		var data = [["id",
			"event time",
			"terminal id",
			"vessel name",
			"latitude",
			"longitude",
			"speed",
			"heading"]];

		for (var terminalId in locations) {
			var message = locations[terminalId];
			if(message.path && message.path.getVisible()) {
				var histories = message.histories;

				var path = [];
				$.each(histories, function(i, history) {
					var nextDay = new Date(endDate);
					nextDay.setDate(endDate.getDate() + 1);
					if(history.eventTime > startDate.getTime() && history.eventTime < nextDay.getTime()) {
						data.push([history.id,
						    '"' + $.format.date(new Date(history.eventTime), "dd.MM.yyyy HH:mm:ss") + '"',
						    '"' + history.terminalId + '"',
						    '"' + (history.vesselName? history.vesselName: '') + '"',
							history.position.latitude,
							history.position.longitude,
							history.speed,
							history.heading]);
					}
				});
			}
		}

		var csvContent = "";
		data.forEach(function(infoArray, index){
		   var dataString = infoArray.join(",");
		   csvContent += index < data.length ? dataString+ "\n" : dataString;
		});

		if(window.navigator.msSaveOrOpenBlob) {
			var blobObject = new Blob(["\ufeff"+csvContent]);
	        window.navigator.msSaveOrOpenBlob(blobObject, "terminal-messages.csv");
		} else {
			var link = document.createElement("a");
			link.setAttribute("href", encodeURI("data:text/csv;charset=utf-8,"+csvContent));
			link.setAttribute("download", "terminal-messages.csv");
			document.body.appendChild(link); // Required for FF

			link.click();
		}
	});

    $( ".datepicker" ).datepicker({
        dateFormat: 'dd.mm.yy',
        autoSize: true,
        firstDay: 1,
        constrainInput: true
    });

    var endDate = new Date();
    endDate.setHours(0,0,0,0);
    var startDate = endDate;

    $( ".datepicker.startDate" ).datepicker( "setDate", startDate );
    $( ".datepicker.endDate" ).datepicker( "setDate", endDate );

    $("#setDate").click(function() {
        endDate = $( ".datepicker.endDate" ).datepicker( "getDate" );
        startDate = $( ".datepicker.startDate" ).datepicker( "getDate" );
        $("div.inner-table").closest("tr").remove();
        for (var terminalId in locations) {
            var message = locations[terminalId];
            if(message.path) {
                message.path.setMap(null);

                $.each(message.historiesMarkers, function(i, marker) {
                    marker.setMap(null);
                })
                delete message.historiesMarkers;

                if(message.path.getVisible()) {
                    createPath(terminalId);
                    showHistories(terminalId);
                } else {
                    delete message.path;
                }
            }
        }
    });

	var mapProp = {
		zoom:5,
		mapTypeId:google.maps.MapTypeId.ROADMAP
	};

	var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
	var mapHistory = new google.maps.Map(document.getElementById("googleMapHistory"),mapProp);

	var measureTool = new MeasureTool(map, { unit: MeasureTool.UnitTypeId.IMPERIAL });
	var measureToolHistory = new MeasureTool(mapHistory, { unit: MeasureTool.UnitTypeId.IMPERIAL });

	var infowindow = new google.maps.InfoWindow();
	var bounds = new google.maps.LatLngBounds();
	var zIndex = 0;

	$("#checkAll").click(function() {
		$("#tracking_table input:checkbox").not(this).prop("checked", this.checked);
		$("#tracking_table tbody tr.row input:checkbox").trigger("change");
		infowindow.close();
	});

	$("#tracking_table tbody tr.header input:checkbox").click(function() {
		var checkbox_selector = "#tracking_table input[name=" + $(this).attr("name") + "]";
		$(checkbox_selector).not(this).prop("checked", this.checked);
		$(checkbox_selector).trigger("change");
		infowindow.close();
	});

	function focusAndShowInfoWindow(terminalId) {
		showInfoWindow(terminalId);

		var message = locations[terminalId];
		message.marker.setZIndex(++zIndex);
		message.marker.setAnimation(google.maps.Animation.DROP);
		map.setCenter(new google.maps.LatLng(message.latitude, message.longitude));
	}

	function showInfoWindow(terminalId, marker) {
		var message = locations[terminalId];
		var name = message.name? message.name: terminalId;
		var content = "<p><strong><u>" + name + "</u></strong></p>" +
			"<p><strong>Last:</strong> " + $.format.date(new Date(message.eventTime), "dd.MM.yyyy HH:mm:ss") + "</p>" +
			"<p><strong>Position:</strong> " + message.latitude.toFixed(4) + " S&nbsp;&nbsp;" + message.longitude.toFixed(4) + " E</p>" +
			"<p><strong>Speed:</strong> " + (message.speed * 0.1).toFixed(1) + " knots</p>" +
			"<p><strong>Heading</strong>: " + (message.heading * 0.1).toFixed(1) + "&deg;</p>";
		infowindow.setContent(content);
		infowindow.open(map, marker? marker: message.marker);
	}

	function getVesselIcon(message) {
		var yesterday = new Date();
		yesterday.setDate(yesterday.getDate() - 1);
		var olderThan24h = message.eventTime <= yesterday.getTime();

		var moving_vessel = {
			path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
			scale: 3,
			fillColor: olderThan24h? "#FFA07A": "#69FD92",
			strokeColor: "#000000",
			fillOpacity: 1,
			strokeWeight: 2,
			rotation: message.speed > 0 ? Math.round(message.heading * 0.1): 0,
		};

		var vessel = {
			path: "M24-8c0 4.4-3.6 8-8 8h-32c-4.4 0-8-3.6-8-8v-32c0-4.4 3.6-8 8-8h32c4.4 0 8 3.6 8 8v32z",
			scale: 0.25,
			fillColor: olderThan24h? "#FFA07A": "#69FD92",
			strokeColor: "#000000",
			fillOpacity: 1,
			strokeWeight: 2
		};

		return message.speed > 0 ? moving_vessel: vessel;
	}

	$("#tracking_table tbody tr.row").click(function() {
		var id = $("input:checkbox", this).val();
		var selectedMessage = locations[id];
		if(selectedMessage) {
			if(selectedMessage.marker.getVisible()) {
				focusAndShowInfoWindow(id);
				selectedMessage.marker.setAnimation(null);
			} else {
				$("#tracking_table tbody input:checkbox[value=" + id + "]").trigger("click");
			}
		}
	});

	$("#tracking_table tbody tr.row input:checkbox").click(function(e) {
		e.stopPropagation();
		var id = $(this).val();
		var selectedMessage = locations[id];
		if(selectedMessage) {
			var checked = $(this).is(":checked");
			selectedMessage.marker.setVisible(checked);
			if(checked) {
				focusAndShowInfoWindow(id);
			} else {
				infowindow.close();
			}
		}
	});

	$("#tracking_table tbody tr.row input:checkbox").change(function() {
		var id = $(this).val();
		var checked = $(this).is(":checked");
		var selectedMessage = locations[id];
		if(selectedMessage) {
			selectedMessage.marker.setVisible(checked);
		}
	});

	for (var terminalId in locations) {
		var message = locations[terminalId];

		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(message.latitude, message.longitude),
			icon: getVesselIcon(message),
			map: map,
			zIndex: 0
		});

		bounds.extend(marker.position);

		google.maps.event.addListener(marker, 'click', (function (marker, terminalId) {
			return function () {
				showInfoWindow(terminalId, marker);
			}
		})(marker, terminalId));

		message.marker = marker;
	}
	map.fitBounds(bounds);
	mapHistory.fitBounds(bounds);

	$("#tab-track").click(function() {
		$("#googleMap").show();
		$("#googleMapHistory").hide();
	});

	$("#tab-history").click(function() {
		$("#googleMapHistory").show();
		$("#googleMap").hide();
	});

	google.maps.event.addListenerOnce(mapHistory, 'idle', function(){
		$("#googleMapHistory").hide();
	});

	function createPath(terminalId) {
		var histories = locations[terminalId].histories;

		var path = [];
		var historiesMarkers = [];
		$.each(histories, function(i, history) {
			var nextDay = new Date(endDate);
			nextDay.setDate(endDate.getDate() + 1);
			if(history.eventTime > startDate.getTime() && history.eventTime < nextDay.getTime()) {
				var latLng = new google.maps.LatLng(history.position.latitude, history.position.longitude)
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

				google.maps.event.addListener(historyMarker, 'mouseover', function() {
					historyInfoWindow.open(mapHistory, historyMarker);
				});
				google.maps.event.addListener(historyMarker, 'mouseout', function() {
					historyInfoWindow.close();
				});

				historiesMarkers[historiesMarkers.length] = historyMarker;
			}
		});
		locations[terminalId].historiesMarkers = historiesMarkers;

		if(historiesMarkers.length > 0) {
			historiesMarkers[0].setIcon({
				path: google.maps.SymbolPath.CIRCLE,
				scale: 6,
				fillColor: "#0000FF",
				strokeColor: "#000000",
				fillOpacity: 1,
				strokeWeight: 2
			});
		}

		if(historiesMarkers.length > 1) {
			historiesMarkers[historiesMarkers.length-1].setIcon(
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
		mapHistory.setCenter(path[path.length-1]);
	}

	function showHistories(terminalId) {
		var selectedTR = $("#history_table tr.row").has("input:checkbox[value=" + terminalId + "]");

		if(selectedTR.next().length > 0 && !(selectedTR.next().hasClass("row") || selectedTR.next().hasClass("header"))) {
			return;
		}

		selectedTR.addClass("checked");
		var histories_html = "<tr><td></td><td><div class=\"inner-table\">";
		$.each(locations[terminalId].histories, function(i, history) {
			var nextDay = new Date(endDate);
			nextDay.setDate(endDate.getDate() + 1);
			if(history.eventTime > startDate.getTime() && history.eventTime < nextDay.getTime()) {
				histories_html += "<div class=\"inner-table-row\">";
				histories_html += "<div class=\"inner-table-icon-cell\"><i class=\"fa fa-compass\"></i></div>";
				histories_html += "<div class=\"inner-table-date-cell\">" + $.format.date(new Date(history.eventTime), "dd.MM.yyyy HH:mm:ss") + "</div>";
				histories_html += "<div>" + (history.speed * 0.1).toFixed(1) + " knots</div>"
				histories_html += "</div>";
			}
		});
		histories_html += "</div></td></tr>";

		selectedTR.after(histories_html);
	}

	function removeHistories(terminalId) {
		var selectedTR = $("#history_table tr.row").has("input:checkbox[value=" + terminalId + "]");

		if(selectedTR.next().hasClass("row")) {
			return;
		}

		selectedTR.removeClass("checked");

		selectedTR.next().remove();
	}

	function setCenter(loc) {
		var lastPosition = loc.path.getPath().getArray()[loc.path.getPath().getArray().length-1];
		mapHistory.setCenter(lastPosition);
	}

	$("#history_table tbody tr.row input:checkbox").click(function(e) {
		e.stopPropagation();
		var id = $(this).val();
		var selectedMessage = locations[id];

		if(selectedMessage) {
			if(!selectedMessage.path) {
				if(selectedMessage.histories) {
					createPath(id);
					showHistories(id);
				} else {
					var terminal_messages_url = "/app/terminal/" + id + "/terminal_messages/";
					$.getJSON(terminal_messages_url, function(data) {
						if(data.length > 0)
						{
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
				$.each(selectedMessage.historiesMarkers, function(i, marker) {
					if(checked) marker.setMap(mapHistory);
					else marker.setMap(null);
				});

				if(checked) {
					setCenter(selectedMessage);
					showHistories(id);
				} else {
					removeHistories(id);
				}
			}
		}
	});

	$("#history_table tbody tr.row").click(function() {
		var id = $("input:checkbox", this).val();
		var selectedMessage = locations[id];
		if(selectedMessage) {
			if(selectedMessage.path && selectedMessage.path.getVisible()) {
				setCenter(selectedMessage);
			} else {
				$("#history_table tbody input:checkbox[value=" + id + "]").trigger("click");
			}
		}
	});

	function getTimeDifference(fromDate) {
		if(!fromDate) {
			return "-";
		}

		var toDate = new Date().getTime();

		var seconds = Math.round((toDate - fromDate) / 1000);
		var minutes = 0, hours = 0, days = 0, weeks = 0, months = 0, years = 0;

		var result = seconds + "s";

		if(seconds >= 60) {
			minutes = Math.floor(seconds / 60);
			seconds = seconds % 60;
			result = minutes + "m" + seconds + "s";
		}

		if(minutes >= 60) {
			hours = Math.floor(minutes / 60);
			minutes = minutes % 60;
			result = hours + "h" + minutes + "m";
		}

		if(hours >= 24) {
			days = Math.floor(hours / 24);
			hours = hours % 24;
			result = days + "d" + hours + "h";
		}

		if(days >= 7 && days < 30) {
			weeks = Math.floor(days / 7);
			days = days % 7;
			result = weeks + "w" + days + "d";
		} else if(days >= 30 && days < 365) {
			months = Math.floor(days / 30);
			weeks = Math.floor(days % 30 / 7);
			result = months + "m" + weeks + "w";
		} else if(days >= 365) {
			years = Math.floor(days / 365);
			months = Math.floor(days % 365 / 30);
			result = years + "y" + months + "m";
		}

		return result;
	}

	function updateTrackingInfo(terminalId, lastUpdate, speed) {
		$("#" + terminalId + "-last").text(getTimeDifference(lastUpdate));

		var speedInfo = $("#" + terminalId + "-speed");
		if(speed == 0) {
			speedInfo.text(0);
		} else {
			speedInfo.text((speed * 0.1).toFixed(1));
		}
	}

	setInterval(function(){
		(function() {
			$.getJSON("/app/terminal_messages/", function(data) {
				$.each(data, function(i, msg) {
					var location = locations[msg.terminalId];
					if(location.id != msg.id) {
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

			$.each(locations, function(id, location) {
				if(location.histories) {
					$.getJSON("/app/terminal/" + id + "/terminal_messages/", function(data) {
						if(data.length > 0)
						{
							var thisId = data[0].terminalId;
							var thisLocation = locations[thisId];
							var maxIdNew = Math.max.apply(Math, data.map(function(msg) { return msg.id; }))
							var maxIdOld = Math.max.apply(Math, thisLocation.histories.map(function(msg) { return msg.id; }))
							if(maxIdNew != maxIdOld) {
								thisLocation.histories = data;
								var path = thisLocation.path;
								if(path) {
									path.setMap(null);
									delete thisLocation.path;

									if(path.getVisible()) {
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
