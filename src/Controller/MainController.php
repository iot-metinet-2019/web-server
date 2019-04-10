<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MeasureRepository;
use App\Repository\SensorMeasureRepository;
use App\Repository\SensorRepository;
use App\Entity\Measure;
use App\Entity\SensorMeasure;
use Symfony\Component\Serializer\SerializerInterface as Serializer;
use FOS\RestBundle\Controller\Annotations as Rest;

class MainController extends AbstractController
{

    /**
     * Vue principale
     * @Route("/", name="root")
     */
    public function index(){
        return $this->render('main.html.twig');
    }

    /**
     * Envoie toutes les données en une seul fois
     * @Rest\Get("/init")
     */
    public function init(Serializer $serializer, MeasureRepository $measureRepository, SensorMeasureRepository $sensorMeasureRepository, SensorRepository $sensorRepository)
    {
        $measures = $measureRepository->findAll();
        $sensors     = $sensorRepository->findAll();
        $allMeasures = $serializer->serialize($measures, 'json', ['groups' => 'get']);
        $allSensors  = $serializer->serialize($sensors, 'json', ['groups' => 'sensors']);

        $data = ['measures' => json_decode($allMeasures), 'sensors' => json_decode($allSensors)];

        return new JsonResponse(json_encode($data), 200, [], true);
    }

    /**
     * Reçoit les valeurs d'un capteur et les enregistre dans la BDD
     * @Rest\Put("/add-data")
     */
    public function addData(EntityManagerInterface $em, SensorRepository $sensorRepository, Request $request)
    {
	$data = json_decode($request->getContent(), true);

        foreach ($data as $key => $value)
        {
            $measure = new measure;
            $sensorMeasure = new sensorMeasure;

            $sensor = $sensorRepository->findOneBy(['mac' => $value['mac']]);
            $measure->setTime(new \DateTime('now'));
            $sensorMeasure->setMeasure($measure);
            $sensorMeasure->setValue($value['value']);
            $sensorMeasure->setSensor($sensor);

            $em->persist($sensorMeasure);
            $em->persist($measure);
        }

        $em->flush();

        return new Response('OK');
    }
}
