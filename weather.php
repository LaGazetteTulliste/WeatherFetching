<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Function to translate English weather descriptions to French
function getFrenchDescription($englishDescription) {
    $descriptionMap = array(
        'Clear' => 'Dégagé',
        'Rain' => 'Pluvieux',
        'Clouds' => 'Nuageux',
        'Few clouds' => 'Quelques nuages',
        'Scattered clouds' => 'Nuages épars',
        'Broken clouds' => 'Nuages fragmentés',
        'Overcast clouds' => 'Nuageux',
        'Mist' => 'Brume',
        'Fog' => 'Brouillard',
        'Light rain' => 'Pluie légère',
        'Moderate rain' => 'Pluie modérée',
        'Heavy intensity rain' => 'Pluie intense',
        'Light snow' => 'Neige légère',
        'Moderate snow' => 'Neige modérée',
        'Heavy snow' => 'Neige forte',
        'Thunderstorm' => 'Orage',
        'Drizzle' => 'Bruine',
        'Haze' => 'Brume légère',
        'Smoke' => 'Fumée',
        'Dust' => 'Poussière',
        'Sand' => 'Sable',
        'Tornado' => 'Tornade',
        'Squall' => 'Rafales de vent',
        'Ash' => 'Cendres volcaniques',
        'Volcanic ash' => 'Cendres volcaniques',
        'Sand/dust whirls' => 'Tourbillons de sable/poussière',
        'Hail' => 'Grêle',
        'Tropical storm' => 'Tempête tropicale',
        'Hurricane' => 'Ouragan',
        'Heavy shower rain' => 'Forte pluie',
        'Light rain shower' => 'Averses de pluie légère',
        'Light snow shower' => 'Averses de neige légère',
        'Heavy snow shower' => 'Averses de neige fortes',
        'Light drizzle' => 'Bruine légère',
        'Heavy shower snow' => 'Forte averse de neige',
        'Light shower sleet' => 'Averses légères de neige fondue',
        'Heavy shower sleet' => 'Forte averse de neige fondue',
        'Patchy light rain' => 'Pluie légère éparses',
        'Patchy light snow' => 'Neige légère éparses',
        'Patchy moderate snow' => 'Neige modérée éparses',
        'Patchy heavy snow' => 'Neige forte éparses',
        'Patchy moderate rain' => 'Pluie modérée éparses',
        'Patchy light drizzle' => 'Bruine légère éparses',
        'Patchy moderate drizzle' => 'Bruine modérée éparses',
        'Patchy heavy drizzle' => 'Bruine forte éparses',
    );

    return isset($descriptionMap[$englishDescription]) ? $descriptionMap[$englishDescription] : 'Translation Not Available';
}

// Check if latitude and longitude parameters are set in the request
if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $latitude = $_GET['lat'];
    $longitude = $_GET['lon'];
    $apiKey = "YOUR_API_KEY";

    // Construct the API URL for weather data
    $url = "https://api.openweathermap.org/data/2.5/weather?lat=$latitude&lon=$longitude&appid=$apiKey";

    // Attempt to fetch weather data from the API
    $response = @file_get_contents($url); // The '@' suppresses warnings

    // Check if the API request was successful
    if ($response !== false) {
        // Decode the JSON response
        $data = json_decode($response, true);

        // Check if the response contains valid weather data
        if (isset($data['name'], $data['main']['temp'], $data['weather'][0]['main'])) {
            // Extracting relevant information
            $location = $data['name'];
            $temperature = number_format($data['main']['temp'] - 273.15, 2) . '°C';
            $description = getFrenchDescription($data['weather'][0]['main']);
            $iconCode = $data['weather'][0]['icon'];
            $iconUrl = "https://openweathermap.org/img/wn/$iconCode.png";

            // Constructing the response array
            $weatherData = array(
                'location' => $location,
                'temperature' => $temperature,
                'description' => $description,
                'iconUrl' => $iconUrl
            );

            // Send the weather data as JSON response
            echo json_encode($weatherData);
        } else {
            // Return an error message for invalid or incomplete weather data
            echo json_encode(['error' => 'Invalid or incomplete weather data.']);
        }
    } else {
        // Return an error message for failed API request
        echo json_encode(['error' => 'Unable to fetch weather data.']);
    }
} else {
    // Return an error message for invalid parameters
    echo json_encode(['error' => 'Invalid parameters.']);
}
?>
