<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MeasureRepository;
use App\Repository\SensorMeasureRepository;
use App\Repository\SensorRepository;
use Symfony\Component\Serializer\SerializerInterface as Serializer;

class MainController extends AbstractController
{
    /**
     * Envoie toutes les données en une seul fois
     * @Route("/init", name="init")
     */
    public function index(Serializer $serializer, MeasureRepository $measureRepository, SensorMeasureRepository $sensorMeasureRepository, SensorRepository $sensorRepository)
    {
        $measures = $measureRepository->findAll();
        $sensors     = $sensorRepository->findAll();
        $allMeasures = $serializer->serialize($measures, 'json', ['groups' => 'get']);
        $allSensors  = $serializer->serialize($sensors, 'json', ['groups' => 'sensors']);

        $data = ['measures' => json_decode($allMeasures), 'sensors' => json_decode($allSensors)];

        return new JsonResponse(json_encode($data), 200, [], true);
    }

    /**
     * Renvoi les dernières données
     * @Route("/send-last", name="send-last")
     */
    public function sendLastData()
    {
        $data = ['message' => 'Hello'];
        return $this->json($data);
    }
}
