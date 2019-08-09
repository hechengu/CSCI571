<!DOCTYPE html>
<html>
<style>
	td {text-align:center}
	h1 {
		font-style: italic;
		height:20px;
		margin-top: 10px;
		text-align: center;
	}
	form{
		margin-left: 400px;
		width: 650px;
		margin-top: 50px;
		padding-left: 10px;
		padding-right: 10px;
		padding-bottom: 20px;
		background-color: #F3F3F3;
		font-weight: bold;
	}
	#inputRadio{
		margin-top: -15px;
        margin-left: 305px;
        margin-bottom: -13px;
        background-color: #F3F3F3;
	}
	#inputButton{
		margin-left:100px;
	}
	#eventTable{
		align-content: center;
        justify-content: center;
        margin-left: 300px;
        margin-top:30px;
        font-weight: 200;
        border-color: #D5D5D5;
        border-collapse: collapse;
	}
	#venue_detail{
		position: absolute; 
		align-content: center;
		margin-left:300px; 
		margin-top:200px
	}
  #map {
    display: none;
    position: absolute;
    border: 1px solid black;
    padding: 50px;
    text-align: justify;
    width: 50%;
    height: 60%;
  }
  #floating-panel {
    display: none;
    position: absolute;
    z-index: 1;
    background-color: #fff;
    padding: 5px;
    border: 1px solid #999;
    text-align: center;
    font-family: 'Roboto','sans-serif';
  }
</style>
<head>
	<title>Event Search Webpage</title>
</head>

<script type = "text/javascript">
	function clearAll()
  {
		document.getElementById('Keyword').value = "";
		document.getElementById('Distance').value = "";
		document.getElementById('Location').value = "";
		document.getElementById('Curr_poi').checked = true;
		document.getElementById('Def_poi').checked = false;

		var selection = document.getElementsByTagName('select');
		for(var i in selection)
    {
			selection[i].selectedIndex = 0;
		}
		var table1 = document.getElementById('eventTable');
    if (table1)
    {
        table1.parentNode.removeChild(table1);
    }
    var table2 = document.getElementById('emptyTable');
    if (table2)
    {
        table2.parentNode.removeChild(table2);
    }
    var M = document.getElementById('map');
    if(M)
    {
        M.remove();
    }
    var Info1 = document.getElementById('event_info');
    if(Info1)
    {
        Info1.remove();
    }
    var Info2 = document.getElementById('event_detail');
    if(Info2)
    {
        Info2.remove();
    }
    var Info3 = document.getElementById('venue_detail');
    if(Info3)
    {
        Info3.remove();
    }
    var m = document.getElementById('mode');
    if(m)
    {
        m.remove();
    }
    var A = document.getElementById('address');
    if(A)
    {
        A.remove();
    }
    var IMG = document.getElementById('vent_image');
    if(IMG)
    {
        IMG.remove();
    }
    <?php $var = 0;	$mylat = 0; $mylng = 0;?>
  }

  function setRequired(){
    document.getElementById('Location').required=true;
    document.getElementById('Curr_poi').checked = false;
	}

	function removeRequired()
  {
		if(document.getElementById('Location').required == true){
				document.getElementById('Location').required=false;
		}
    document.getElementById('Def_poi').checked = false;
	}

  function venue_description(lat, lng, mylat, mylng, mode) 
  {
    //console.log(lat, lng, mode);
    var elems = document.getElementsByClassName("googlemap");
    if (elems[1].style.display == "block") {
      elems[0].style.display = "none";
      elems[1].style.display = "none";
      return;
    }
    showMapPositioned(this.event, lat, lng, mylat, mylng, mode);
  }
  
  function showMapPositioned(event_, lat, lng, mylat, mylng, mode)
  {
    var x_cord = event_.pageX+120;
    var y_cord = event_.pageY-120;

    var elems = document.getElementsByClassName("googlemap");
    if (mode == 1) 
    {
      elems[1].style.left = x_cord + "px";
      elems[1].style.top = y_cord + "px";
      y_cord -= 40;
      elems[0].style.left = x_cord + "px";
      elems[0].style.top = y_cord + "px";
    }
    elems[1].style.display = "block";
    elems[0].style.display = "block";

    initMap(lat, lng, mylat, mylng);
  }

  function initMap(lat, lng, mylat, mylng) 
  {
    var directionsService = new google.maps.DirectionsService();
    var directionsDisplay = new google.maps.DirectionsRenderer();
    var loc = {lat: parseFloat(lat), lng: parseFloat(lng)};
    var mapOptions = { zoom: 14, center: loc };
    var map = new google.maps.Map(document.getElementById('map'), mapOptions);
    var marker = new google.maps.Marker({position: loc, map: map});
    directionsDisplay.setMap(map);

    calculateAndDisplayRoute(directionsService, directionsDisplay, lat, lng, mylat, mylng);
    document.getElementById('mode').addEventListener('change', function() {calculateAndDisplayRoute(directionsService, directionsDisplay, lat, lng, mylat, mylng); });
  }

      function calculateAndDisplayRoute(directionsService, directionsDisplay, lat, lng, mylat, mylng) {
        var selectedMode = document.getElementById('mode').value;
        directionsService.route({
          origin: {lat: parseFloat(mylat), lng: parseFloat(mylng)}, 
          destination: {lat: parseFloat(lat), lng: parseFloat(lng)}, 
          travelMode: google.maps.TravelMode[selectedMode]
        }, function(response, status) {
          if (status == 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            //window.alert('Directions request failed due to ' + status);
          }
        });
      }

</script>


<form class = "EventForm" method = "get">
	<h1>Events Search</h1><hr style = "height:2px;"/>Keyword<input type = "text" name = "keyword" id = "Keyword" required placeholder = "keyword" value = "<?php echo isset($_GET["keyword"]) ? $_GET["keyword"] : ""?>">
	<p style = "height:2px padding-bottom:2px;"/>Category
	<select name="category" class = "select">
		<option value = "0" <?php echo (isset($_GET["category"]) && $_GET["category"] == '0') ? 'selected = "selected"' : ''; ?>>Default</option>
		<option value = "KZFzniwnSyZfZ7v7nJ" <?php echo (isset($_GET["category"]) && $_GET["category"] == 'KZFzniwnSyZfZ7v7nJ') ? 'selected = "selected"' : ''; ?>>Music</option>
		<option value = "KZFzniwnSyZfZ7v7nE" <?php echo (isset($_GET["category"]) && $_GET["category"] == 'KZFzniwnSyZfZ7v7nE') ? 'selected = "selected"' : ''; ?>>Sport</option>
		<option value = "KZFzniwnSyZfZ7v7na" <?php echo (isset($_GET["category"]) && $_GET["category"] == 'KZFzniwnSyZfZ7v7na') ? 'selected = "selected"' : ''; ?>>Art&Theatre</option>
		<option value = "KZFzniwnSyZfZ7v7nn" <?php echo (isset($_GET["catehory"]) && $_GET["category"] == 'KZFzniwnSyZfZ7v7nn') ? 'selected = "selected"' : ''; ?>>Film</option>
		<option value = "KZFzniwnSyZfZ7v7n1" <?php echo (isset($_GET["category"]) && $_GET["category"] == 'KZFzniwnSyZfZ7v7n1') ? 'selected = "selected"' : ''; ?>>Miscellaneous</option>
	</select>
</p>

<p style = "height:2px;">Distance(miles)<input type="text" name="distance" id = "Distance" placeholder="10" value = "<?php echo isset($_GET["distance"]) ? $_GET["distance"] : '10'?>"> from

	<div id = "inputRadio">
	<label><input name = "position" type = "radio" id = "Curr_poi" checked onclick = "removeRequired()" value = "current" <?php if(!isset($_GET['position']) || (isset($_GET['position']) && $_GET['position'] == 'current'));?>> Here</label><br>
	<label><input name = "position" type = "radio" id = "Def_poi" onclick = "setRequired()" value = "costume" <?php if(!isset($_GET['poisition']) || (isset($_GET['position']) && $_GET['position'] == 'costume'));?>> Other<input type = "text" name = "location" id = "Location" laceholder="The place you want to search" value = "<?php echo isset($_GET["location"]) ? $_GET["location"] : ''?>" ></label><br><br>
	</div>

	<div id = "inputButton">
	<input type ="submit"  name="Search" value="search" style ="margin-right:10px">
  <input type ="button" name="Clear" value ="clear" onclick="clearAll();"><br><br>
	</div>
</p>
</form>

<?php include 'geoHash.php' ?>
<?php $var = 0;
      $mylat = 0;
      $mylng = 0;
 ?>
 <?php
// Turn off all error reporting
error_reporting(0);
?>
<?php
    function curl_get_contents($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
?>

<?php if(isset($_GET["Search"])): ?>
<?php
  function Display_event($keyword, $category, $distance, $geopoint)
  {
    $jsonResponse = curl_get_contents('https://app.ticketmaster.com/discovery/v2/events.json?apikey=QA9J0vN1YlUO2SOBLFINfy2bgm7Zu6gK&keyword='.$keyword.'&segmentId='.$category.'&radius='.$distance.'&unit=miles&geoPoint='.$geopoint);
    $jsonResponse = json_decode($jsonResponse, true);
            ?>
       <script>
          var data = <?php echo json_encode($jsonResponse); ?>;
          console.log(data);
        </script>
      <?php
    if($jsonResponse['page']['totalElements'] > 0)
      {
        $jsonData = $jsonResponse['_embedded']['events'];
      }
      else
      {
        $jsonData = "";
      }

    if($jsonData != "")
    {
      if(($_GET['category'] == '0' && $GLOBALS['var'] < 5) || $_GET['category'] != '0')
      {

        echo '<table border ="1px" id="eventTable">
            <tr>
            <th>Date</th>
            <th>Icon</th>
            <th>Event</th>
            <th>Genre</th>
            <th>Venue</th>
            </tr>';
        $GLOBALS['var'] = 1;
      }

      for ($i = 0; $i < sizeof($jsonData); $i++)
      {
        ?>
        <tr>
          <td><?php echo $jsonData[$i]['dates']['start']['localDate']; ?></td>
          <td><img src="<?php echo $jsonData[$i]['images'][0]['url'] ?>" height=120, width=180/></td>
          <td><a href="<?php echo '?eventID='.$jsonData[$i]['id'] ?>" target="_blank"><?php echo $jsonData[$i]['name']; ?></a></td>
          <td><?php echo $jsonData[$i]['classifications'][0]['segment']['name']; ?></td>
          <td id = "venueID" onclick = "venue_description(<?php echo $jsonData[$i]['_embedded']['venues'][0]['location']['latitude'];?>, <?php echo $jsonData[$i]['_embedded']['venues'][0]['location']['longitude']; ?>, <?php echo $GLOBALS['mylat']; ?>, <?php echo $GLOBALS['mylng']; ?>, 1)"><?php echo $jsonData[$i]['_embedded']['venues'][0]['name']; ?></td>
        </tr>
      <?php
      }
          echo '<div id="floating-panel" class="googlemap">';
            echo '<b>Mode of Travel: </b>';
            echo '<select id="mode">';
            echo '<option value="DRIVING">Driving</option>';
            echo '<option value="WALKING">Walking</option>';
            echo '<option value="BICYCLING">Bicycling</option>';
            echo '<option value="TRANSIT">Transit</option>';
            echo '</select></div>';
          echo '<div id="map" class="googlemap"></div>';
          echo '</table>';
    }
    else
    {
      if($_GET['category'] == '0')
      {
        $GLOBALS['var'] = $GLOBALS['var'] + 1;
      }
      if(($_GET['category'] == '0' && $GLOBALS['var'] == 5) || $_GET['category'] != '0')
      {
        echo '<table  border="2px"  id ="emptyTable" align="center" style="margin-left:600px; margin-top: 30px">';
        echo '<tr><td>No Record has been found</td></tr></table>';

      }
    }
  }
?>

<?php
  if ($_GET['position'] == "costume") {
    $temp = str_replace(" ", "+",$_GET["location"]);
    $jsonResponse = curl_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$temp.'&key=AIzaSyAmOJZ9uWZmpdms5RC0OCVPhCQuWgLantQ');
    $jsonResponse = json_decode($jsonResponse, true);
    $GLOBALS['mylat'] = $jsonResponse['results'][0]['geometry']['location']['lat'];
    $GLOBALS['mylng'] = $jsonResponse['results'][0]['geometry']['location']['lng'];
    }
    else{
      $jsonResponse = curl_get_contents('http://ip-api.com/json');
      $jsonResponse = json_decode($jsonResponse, true);
      $GLOBALS['mylat'] = $jsonResponse['lat'];
      $GLOBALS['mylng'] = $jsonResponse['lon'];
    }
    $geoPoint = encode($GLOBALS['mylat'], $GLOBALS['mylng']);
    if($_GET['category'] == "0")
    {
      Display_event($_GET['keyword'], 'KZFzniwnSyZfZ7v7nJ', $_GET['distance'], $geoPoint);
      Display_event($_GET['keyword'], 'KZFzniwnSyZfZ7v7nE', $_GET['distance'], $geoPoint);
      Display_event($_GET['keyword'], 'KZFzniwnSyZfZ7v7na', $_GET['distance'], $geoPoint);
      Display_event($_GET['keyword'], 'KZFzniwnSyZfZ7v7nn', $_GET['distance'], $geoPoint);
      Display_event($_GET['keyword'], 'KZFzniwnSyZfZ7v7n1', $_GET['distance'], $geoPoint);

    }
    else
    {
      Display_event($_GET['keyword'], $_GET['category'], $_GET['distance'], $geoPoint);
    }
?>
<?php endif; ?>
<?php if(isset($_GET['eventID'])){
        $eventResponse = curl_get_contents('https://app.ticketmaster.com/discovery/v2/events/'.$_GET['eventID'].'?apikey=QA9J0vN1YlUO2SOBLFINfy2bgm7Zu6gK');
        $eventResponse = json_decode($eventResponse, true);
        ?>
        <script>
          var data = <?php echo json_encode($eventResponse); ?>;
          console.log(data);
        </script>
        <?php
        echo '<div id="event_info" align="center" style="margin-left:75px; margin-top: 30px"><h2>'.$eventResponse['name'].'</h2></div>';
        echo '<div id="event_detail" style="position: relative; top: 40px; margin-left:300px;"><p><b>Date</b><br>'.$eventResponse['dates']['start']['localDate'].' '.$eventResponse['dates']['start']['localTime'].'</p>';
        echo '<p><b>Artist / Team</b><br><a href ="'.$eventResponse['_embedded']['attractions'][0]['url'].'" target = "_blank">'.$eventResponse['_embedded']['attractions'][0]['name'].'</a> | <a href ="'.$eventResponse['_embedded']['attractions'][1]['url'].'" target = "_blank">'. $eventResponse['_embedded']['attractions'][1]['name'].'</a></p>';
        echo '<p><b>Venue</b><br>'.$eventResponse['_embedded']['venues'][0]['name'].'</p>';

        if($eventResponse['classifications'][0]['subType']['name'] && $eventResponse['classifications'][0]['type']['name'])
        {
        echo '<p><b>Genres</b><br>'.$eventResponse['classifications'][0]['subGenre']['name'].' | '.$eventResponse['classifications'][0]['genre']['name'].' | '.$eventResponse['classifications'][0]['segment']['name'].' | '.$eventResponse['classifications'][0]['subType']['name']. ' | '.$eventResponse['classifications'][0]['type']['name'].'</p>';
        }else{
          echo '<p><b>Genres</b><br>'.$eventResponse['classifications'][0]['subGenre']['name'].' | '.$eventResponse['classifications'][0]['genre']['name'].' | '.$eventResponse['classifications'][0]['segment']['name'];
        }
        echo '<p><b>Ticket Status</b><br>'.$eventResponse['dates']['status']['code'].'</p>';
        echo '<p><b>Buy Ticket At</b><br><a href="'.$eventResponse['url'].'">TicketMaster</a></p>';
        echo '<div id="event_image" style="position: absolute; top:40px; margin-left:400px;"><img src="'.$eventResponse['seatmap']['staticUrl'].'"></div></div>';

        $venueResponse = curl_get_contents('https://app.ticketmaster.com/discovery/v2/venues/'.$eventResponse['_embedded']['venues'][0]['id'].'?apikey=QA9J0vN1YlUO2SOBLFINfy2bgm7Zu6gK');
        $venueResponse = json_decode($venueResponse, true);

       echo '<div id="venue_detail"><button onClick="showVenueInfo(); venue_description('.$venueResponse['location']['latitude'].','.$venueResponse['location']['longitude'].','.$GLOBALS['mylat'].','.$GLOBALS['mylng'].',2)">Show venue info</button>
      <div id="venueInfo" style="display: none">
      <p><b>Name</b><br>'.$venueResponse['name'].'</p>'.
      '<div id="floating-panel" class="googlemap" style="position: relative; width: 40%; height: 10%; margin-left:600px; align-content: right">
      </div>'.
      '<div id="map" class="googlemap" style="margin-left:600px; align-content: right width: 200px; height: 400px"><b>Map</b></div>'.
      '<div id = "address" style="top: 60px; position:absolute"><p><b>Address</b><br>'.$venueResponse['address']['line1'].'</p>'.
      '<p><b>City</b><br>'.$venueResponse['city']['name'].' '.$venueResponse['state']['stateCode'].'</p>'.
      '<p><b>Postal Code</b><br>'.$venueResponse['postalCode'].'</p>'.
      '<p><b>Upcoming Events</b><br><a href="'.$venueResponse['url'].'">TicketMaster</a></p>'.
      '<p><img src="'.$venueResponse['images'][0]['url'].'" height=300></p></div>'.
      '</div></div>

      <script>
      function showVenueInfo() {
        var x = document.getElementById("venueInfo");
        if (x.style.display === "none") {
          x.style.display = "block";
        }
        else x.style.display = "none";
      }
      </script>';

        }
?>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmOJZ9uWZmpdms5RC0OCVPhCQuWgLantQ&callback=initMap"></script>
</html>