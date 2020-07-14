<!doctype html>
<html>
<head>
    <title>Forecast Weather using OpenWeatherMap with Laravel PHP</title>
</head>
<body>
<div class="report-container">
    <div>{{ $forecast->city->name }} current weather</div>
    <div>Temperature: {{$lastTemp}} &#186;C</div>
    <div>Sunrise: {{$sunrise}}</div>
    <div>Wind speed: {{$windSpeed}} m/s</div>
    <div>Wind direction: {{$windDirection}}</div>
    <div>5 day average temp: {{round($tempAverage, 1)}} &#186;C</div>
    <div class="weather-forecast">
    </div>
</div>
</body>
</html>
