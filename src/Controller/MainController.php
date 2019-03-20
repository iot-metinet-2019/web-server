<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MeasureRepository;
use App\Repository\SensorMeasureRepository;
use App\Repository\SensorRepository;

class MainController extends AbstractController
{
    /**
     * Envoie toutes les données en une seul fois
     * @Route("/init", name="init")
     */
    public function index(MeasureRepository $measureRepository, SensorMeasureRepository $sensorMeasureRepository, SensorRepository $sensorRepository)
    {
        $allMeasures = $measureRepository->findAll();
        $allSensorMeasures = $sensorMeasureRepository->findAll();
        $allSensor = $sensorRepository->findAll();

        $data = [
            'measures' => $allMeasures,
            'sensorMeasures' => $allSensorMeasures,
            'sensor' => $allSensor,
        ];

        return $this->json($data);
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
