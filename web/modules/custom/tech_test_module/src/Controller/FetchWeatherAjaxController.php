<?php

namespace Drupal\tech_test_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for ajax call to retrieve current weather for postcode.
 */
class FetchWeatherAjaxController extends ControllerBase {

  /**
   * Returns a json value representing the weather for given postcode.
   */
  public static function fetchWeather($postcode) {
    /*
     * Here I am setting up a mapping from possible api responses to output
     * expected by the task, I am choosing to interpret snow as 'rainy' and
     * fog as 'cloudy' to stick to that format.  In reality I would look into
     * updating the requirements to include snowy and foggy
     *
     */
    $sunny = 'sunny';
    $cloudy = 'cloudy';
    $rainy = 'rainy';

    $weather_mapping = [
      'clear sky' => $sunny,
      'few clouds' => $sunny,
      'clouds' => $cloudy,
      'scattered clouds' => $cloudy,
      'broken clouds' => $cloudy,
      'shower rain' => $rainy,
      'rain' => $rainy,
      'thunderstorm' => $rainy,
      'snow' => $rainy,
      'mist' => $cloudy,

    ];

    $return = ['Currently unable to fetch weather'];
    $postcode = substr($postcode, 0, 3);
    $api_Key = 'e15f84fb261bbcc82b4a19fa0df7d330';
    $request_url = "api.openweathermap.org/data/2.5/weather?zip=$postcode,gb&appid=$api_Key";

    $client = \Drupal::httpClient();
    $response = $client->get($request_url);

    if ($response->getStatusCode() == 200) {
      $body = (string) $response->getBody();
      $data = json_decode($body);
      $weather = strtolower(reset($data->weather)->main);
      if (array_key_exists($weather, $weather_mapping)) {
        $return = [$weather_mapping[$weather]];
      }
    }
    return new JsonResponse($return);
  }
}
