<?php
header('Content-Type: application/json');

// Supported countries
$supportedCountries = ['US', 'CA', 'GB']; // USA, Canada, UK

// Fetch the user's IP address
$userIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

// Use an IP geolocation service to get the country code and name
$response = @file_get_contents("https://ipapi.co/{$userIp}/json/");

// Check if the response is false (indicating an error)
if ($response === false) {
    echo json_encode([
        'supported' => false,
        'error' => 'Could not retrieve location data.'
    ]);
    exit;
}

$data = json_decode($response, true);

// Get the country code and country name
$countryCode = isset($data['country']) ? $data['country'] : null;
$countryName = isset($data['country_name']) ? $data['country_name'] : null;

// Check if the country is supported
if (in_array($countryCode, $supportedCountries)) {
    echo json_encode([
        'supported' => true,
        'country_code' => $countryCode,
        'country_name' => $countryName
    ]);
} else {
    echo json_encode([
        'supported' => false,
        'country_code' => $countryCode,
        'country_name' => $countryName
    ]);
}
?>
