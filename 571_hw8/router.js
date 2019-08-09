var express = require('express'); //Express web server frame
const router = express.Router();
const https = require('https'); //http module, deal with http request and response 
var geohash = require('ngeohash');//geohash module, calculate latitude and altitude of a position and get the position
var SpotifyWebApi = require('spotify-web-api-node'); //spotify API is used to get information about Arts & Music
var XMLHttpRequest = require('xmlhttprequest').XMLHttpRequest; //xhr used to exchange data between server and clients
var xhr = new XMLHttpRequest();
xhr.timeout = 5000;
var LocalStorage = require('node-localstorage').LocalStorage; //store data from server to local client
var localStorage = new LocalStorage('./scratch'); //JSON data from server will be stored in scratch document

router.get('/', function(req, res){
  res.render('index', { msg: null });
});

router.post('/', function(req, res){
  localStorage.setItem('request', JSON.stringify(req.body));
  res.redirect('result');
});

router.get('/result', function(req, res){
  var request = JSON.parse(localStorage.getItem('request'));

  // Get the geoPoint of location using Google Maps Geocoding API
  var lat = '', lng = '';
  try {
    if (request['from'] == 'current') {
      //console.log("Current position");
      // First get cilent's ip address, otherwise would use heroku's server ip address
      var ipAddr = req.headers["x-forwarded-for"];
      if (ipAddr) { var list = ipAddr.split(","); ipAddr = list[list.length-1]; }
      else { ipAddr = req.connection.remoteAddress; }
      var currentLocationUrl = 'http://ip-api.com/json/'+ipAddr;
      var currentLocationResult = JSON.parse(httpRequest(currentLocationUrl));
      lat = currentLocationResult['lat'];
      lng = currentLocationResult['lon'];
    } else {
      //console.log("Other position");
      var location = request['location'];
      var geoEncodingUrl = "https://maps.googleapis.com/maps/api/geocode/json?address="+location+"&key=AIzaSyAmOJZ9uWZmpdms5RC0OCVPhCQuWgLantQ";
      var geoEncodingResult = JSON.parse(httpRequest(geoEncodingUrl));
      lat = geoEncodingResult['results'][0]['geometry']['location']['lat'];
      lng = geoEncodingResult['results'][0]['geometry']['location']['lng'];
    }
    var geoPoint = geohash.encode(lat, lng);

    // Search events using Ticketmaster API Event Search Service
    var keyword = request['keyword'];
    var radius = request['distance'];
    var eventSearchUrl = "https://app.ticketmaster.com/discovery/v2/events.json?apikey=QA9J0vN1YlUO2SOBLFINfy2bgm7Zu6gK&keyword="+keyword+"&radius="+radius+"&unit=miles&geoPoint="+geoPoint;
    var eventSearchResults = JSON.parse(httpRequest(eventSearchUrl));
    //console.log(eventSearchResults);
    if (eventSearchResults['_embedded'] && eventSearchResults['_embedded']['events'].length > 0) {
      var events = eventSearchResults['_embedded']['events'];
      res.render('result', { events: events });
    } else {
      res.render("index", { msg: "no_records" });
    }
  } catch(error) {
    console.error(error);
    res.render("index", { msg: "error" })
  }
});

router.post('/result', function(req, res){
  localStorage.setItem('request', JSON.stringify(req.body));
  res.redirect('result');
});


//trigger when the event is pressed
router.get("/event/:id", function(req, res){
  //console.log("STARTDETAIL");
  var eventId = req.params.id;
  var eventDetailUrl = 'https://app.ticketmaster.com/discovery/v2/events/'+eventId+'?apikey=QA9J0vN1YlUO2SOBLFINfy2bgm7Zu6gK';
  var eventDetail = JSON.parse(httpRequest(eventDetailUrl));

  var venueId = eventDetail['_embedded']['venues'][0]['id'];
  var venueName = eventDetail['_embedded']['venues'][0]['name'];
  var venueDetailUrl = 'https://app.ticketmaster.com/discovery/v2/venues/'+venueId+'?apikey=QA9J0vN1YlUO2SOBLFINfy2bgm7Zu6gK';
  var venueDetail = JSON.parse(httpRequest(venueDetailUrl));

  var upcomingVenueIdUrl = 'https://api.songkick.com/api/3.0/search/venues.json?query='+venueName+'&apikey=Cuo06LlMBGZh0Osq';
  var upcomingVenueIdDetail = JSON.parse(httpRequest(upcomingVenueIdUrl));
  var upcomingVenueId = upcomingVenueIdDetail['resultsPage']['results']['venue'][0]['id'];
  var upcomingsUrl = 'https://api.songkick.com/api/3.0/venues/'+upcomingVenueId+'/calendar.json?apikey=Cuo06LlMBGZh0Osq';
  var upcomingsDetail = JSON.parse(httpRequest(upcomingsUrl));

  var classification = eventDetail['classifications'][0]['segment']['name'];
  if (classification == "Music") {
    var spotifyApi = new SpotifyWebApi({
      clientId: '82f944e0f49c461eaaf73c327feac22a',
      clientSecret: '5940e66cfed74ec0b616bbf548fc0851'
    });

    spotifyApi.clientCredentialsGrant().then(
      function(data) {
        spotifyApi.setAccessToken(data.body['access_token']);
        spotifyApi.searchArtists(eventDetail['_embedded']['attractions'][0]['name']).then(
          function(data) {
            var artistDetail = data.body;
            res.render('detail', { eventDetail: eventDetail, venueDetail: venueDetail, artistDetail: artistDetail, upcomingsDetail: upcomingsDetail });
          },
          function(err) { console.error(err); }
        );
       },
      function(err) { console.log('Error when retrieving access token', err); }
    );
  } else res.render('detail', { eventDetail: eventDetail, venueDetail: venueDetail, artistDetail: null, upcomingsDetail: upcomingsDetail });
});

function httpRequest(url) {
  xhr.open("GET", url, false);
  xhr.send();
  if ((xhr.status === 200) && (xhr.readyState === 4)) { //check status
    jsonDoc = xhr.responseText;
  } else jsonDoc = null;
  return jsonDoc;
};

module.exports = router;
