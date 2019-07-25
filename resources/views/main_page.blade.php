<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>scoding_2</title>

    <link href="css/app.css" rel="stylesheet">
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiNMdvQsdIakjWrHL1AVHC4NP8RbFYpzE&callback=initMap"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            font-family: 'Nunito', sans-serif;
            font-weight: 400;
        }

        #map {
            height: 50vh;
            width: 100%;
        }


    </style>
</head>
<body class="bg-dark">
<div class="container-fluid">
    <div class="py-4">
        <div id="map"></div>
    </div>
    <form id="location_data" class="bg-secondary" method="GET" action="{{route('getWeather')}}">
        {{@csrf_field()}}
        <div class="row py-2 px-3 ">


            <div class="col-4">
                <input type="text" id="lat" class="w-100 input-group-text" name="lat" readonly="yes">
            </div>
            <div class="col-4">
                <input type="text" id="lng" class="w-100 input-group-text" name="lng" readonly="yes">
            </div>
            <div class="col-4">
                <input id="getWeather_button" class="btn btn-primary w-100" type="submit" value="Get Weather"/>
            </div>
        </div>
        <div class="row py-2 px-3 ">
            <div class="col-10">
                <input type="text" id="email" class="w-100 input-group-text" name="email">
            </div>
            <div class="col-2 text-white">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="emailCheck" id="emailCheck">
                    <label class="form-check-label" for="exampleCheck1">>10m/s warning sent to email</label>
                </div>
            </div>
        </div>
    </form>
    <div class="row py-4 px-3 bg-dark text-white">
        <div id="location" class="col-lg-6 col-12 w-100 text-center display-3">Select Location</div>
        <div id="temperature" class="col-lg-2 col-12 w-100 text-center display-4"> -</div>
        <div id="wind_direction" class="col-lg-2 col-12 w-100 text-center display-4"> -</div>
        <div id="wind_speed" class="col-lg-2 col-12 w-100 text-center display-4"> -</div>
    </div>
    <div class="row bg-light">
        <table class="table table-light table-borderless table-responsive-sm">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Location</th>
                <th scope="col">Temperature</th>
                <th scope="col">Wind Direction</th>
                <th scope="col">Wind Speed</th>
            </tr>
            </thead>
            <tbody>
            @foreach($locations as $location)
                <tr>
                    <th scope="row">{{$location->OWM_ID}}</th>
                    <td>{{$location->name}}</td>
                    <td>{{$location->latest_temp}} °C</td>
                    <td>{{$location->latest_direction}} °</td>
                    <td>{{$location->latest_speed}} m/s</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript" src="js/app.js"></script>

<script>

    // Patvirtinimo lentelė
    $('#getWeather_button').click(function (e) {
        e.preventDefault() // Don't post the form, unless confirmed
        var form = $("#location_data");
        $.ajax({
            type: "GET",
            url: form.attr("action"),
            data: form.serialize(),
            success: function (response) {
                console.log(response);
                response = $.parseJSON(response);
                console.log(response);

                $('#location').text(response.name);
                $('#temperature').text(response.main.temp + '°C');
                $('#wind_direction').text(response.wind.deg + '°');
                $('#wind_speed').text(response.wind.speed + 'm/s');

            }, fail: function (xhr, textStatus, errorThrown) {
                alert('Select a location');
            }
        });
    });
</script>

</body>
</html>
