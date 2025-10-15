<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather', name: 'app_weather')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'WeatherController'
        ]);
    }

    #[Route('/weather/{city}', name: 'app_weather_city', requirements: ['city' => '.+'])]
    public function city(
        string $city,
        LocationRepository $locationRepository,
        MeasurementRepository $measurementRepository
    ): Response {
        $parts = explode('/', $city);
        $cityName = trim($parts[0]);
        $countryCode = isset($parts[1]) ? trim(strtoupper($parts[1])) : null;

        $criteria = ['city' => $cityName];
        if ($countryCode) {
            $criteria['country'] = $countryCode;
        }

        $location = $locationRepository->findOneBy($criteria);

        if (!$location) {
            throw $this->createNotFoundException("Location not found: $cityName" . ($countryCode ? ", $countryCode" : ""));
        }

        $measurements = $measurementRepository->findAllByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

}
