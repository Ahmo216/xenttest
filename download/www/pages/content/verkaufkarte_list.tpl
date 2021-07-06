<div id="tabs">
    <ul>
        <li><a href="#tabs-1"></a></li>
    </ul>
<!-- ende gehort zu tabview -->

<!-- erstes tab -->
<div id="tabs-1">
	<div id="text">
		[MESSAGE]
		[TAB1]
    <form method="POST">
    <table width="100%"><tr><td valign="top">
    
    </td><td valign="top" width="100%">
    <fieldset height="50"><legend>{|Filter|}</legend>
    <table>
    <tr><td>von</td><td><input type="text" id="datumvon" name="datumvon" value="[DATUMVON]" /></td><td>bis</td><td><input type="text" id="datumbis" name="datumbis" value="[DATUMBIS]" /></td><td><input type="submit" value="laden"></td></tr>
    </table></td></tr></table>
    </fieldset>
    </form>
    <div id="map" style="height:[HEIGHT]; margin:10px; ">
		</div>
		[TAB1NEXT]

	</div>
	<script>
		function initMap(){
			var cities = [CITIES];
			var map = new google.maps.Map(document.getElementById('map'), {
				center: new google.maps.LatLng(50.55581, 9.680845),
				zoom: 7
			});
			cities.forEach(function(entry) {
				var coordInfoWindow = new google.maps.InfoWindow();
				coordInfoWindow.setContent(createInfoWindowContent(entry[0], map.getZoom(), entry[1]));
				coordInfoWindow.setPosition(entry[0]);
				coordInfoWindow.open(map);
			});

			map.addListener('zoom_changed', function() {
				coordInfoWindow.setContent(createInfoWindowContent(augsburg[0], map.getZoom(), augsburg[1]));
			    coordInfoWindow.setContent(createInfoWindowContent(muenchen[0], map.getZoom(), muenchen[1]));
			    coordInfoWindow.open(map);
			});
		}
		var TILE_SIZE = 256;

		function createInfoWindowContent(latLng, zoom,numberof) {
		  
		  var scale = 1 << zoom;

		  var worldCoordinate = project(latLng);

		  var pixelCoordinate = new google.maps.Point(
			  Math.floor(worldCoordinate.x * scale),
			  Math.floor(worldCoordinate.y * scale));

		  var tileCoordinate = new google.maps.Point(
			  Math.floor(worldCoordinate.x * scale / TILE_SIZE),
			  Math.floor(worldCoordinate.y * scale / TILE_SIZE));

		  return [
			numberof// + ' St&uuml;ck'
		  ].join('<br>');
		}

		// The mapping between latitude, longitude and pixels is defined by the web
		// mercator projection.
		function project(latLng) {
		  var siny = Math.sin(latLng.lat() * Math.PI / 180);

		  // Truncating to 0.9999 effectively limits latitude to 89.189. This is
		  // about a third of a tile past the edge of the world tile.
		  siny = Math.min(Math.max(siny, -0.9999), 0.9999);

		  return new google.maps.Point(
			  TILE_SIZE * (0.5 + latLng.lng() / 360),
			  TILE_SIZE * (0.5 - Math.log((1 + siny) / (1 - siny)) / (4 * Math.PI)));
		}
	</script>
	<script async defer
		 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA19Ta_yOhrfEsVqU7j8tlTLWc0zWYZY9U&signed_in=true&callback=initMap">
	</script>
</div>

<!-- tab view schließen -->
</div>

