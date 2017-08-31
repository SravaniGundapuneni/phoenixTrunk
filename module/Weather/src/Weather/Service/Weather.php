<?php
namespace Weather\Service;
use Phoenix\Service\ServiceAbstract;

class Weather extends \ListModule\Service\Lists
{
    const ACCU_WEATHER = 'http://travelclick.accu-weather.com/widget/travelclick/weather-data.asp?metric=1&location=';
    private $currentWeather;

    public function callApi($options)
    {
        $ch          = curl_init();
        curl_setopt_array($ch, $this->getCurlHeaders($options));
        $xmlResponse = curl_exec($ch);
        $err         = curl_error($soap_do);
        curl_close($ch);

        return $this->getResponse($xmlResponse);
    }

    private function getCity($currentWeather)
    {
        return (string) $this->currentWeather->local->city;
    }

    private function getCode($currentWeather)
    {
        return (string) $this->currentWeather->currentconditions->weathericon;
    }

    private function getCurlHeaders($options)
    {
        return array(
            CURLOPT_HEADER         => 0,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_URL            => $this->getWeatherFeed($options)
        );
    }

    private function getResponse($xml)
    {
        $response = array();
        $this->currentWeather = simplexml_load_string($xml);

        $response['city']        = $this->getCity();
        $response['code']        = $this->getCode();
        $response['tempC']       = $this->getTempC();
        $response['text']        = $this->getText(); 
        $response['url']         = $this->getUrl();
        $response['weathertext'] = $this->getWeatherText();

        return $response;
    }

    private function getTempC($currentWeather)
    {
        return (int) $this->currentWeather->currentconditions->temperature;
    }

    private function getText($currentWeather)
    {
        return (string) $this->currentWeather->currentconditions->weathertext;
    }

    private function getUrl($currentWeather)
    {
        return (string) $this->currentWeather->currentconditions->url;
    }

    private function getWeatherFeed($options)
    {
        return self::ACCU_WEATHER . $this->getWeatherLocation($options);
    }

    private function getWeatherLocation($options)
    {
        if (isset($options['weatherLocation'])) {
            $weatherLocation = $options['weatherLocation'];
        } else {
            throw new \Exception('Weather Location is undefined');
        }

        return $weatherLocation;
    }

    private function getWeatherText($currentWeather)
    {
        return (string) $this->currentWeather->currentconditions->weathertext;
    }
}
