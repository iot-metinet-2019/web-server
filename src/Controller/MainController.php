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
     * @Rest\Get("/get-data/{from}")
     */
    public function getData(Serializer $serializer, MeasureRepository $measureRepository, $from)
    {
        $measures = $measureRepository->findAllWithTimeGreaterThan(new \DateTime('@' . $from));
        $measuresData = $serializer->serialize($measures, 'json', ['groups' => 'get']);

        return new JsonResponse($measuresData, 200, [], true);
    }

    /**
     * Récupère la liste des capteurs
     * @Rest\Get("/get-sensors")
     */
    public function getSensors(Serializer $serializer, SensorRepository $sensorRepository)
    {
        $sensors = $sensorRepository->findAll();
        $sensorsData = $serializer->serialize($sensors, 'json', ['groups' => 'sensors']);

        return new JsonResponse($sensorsData, 200, [], true);
    }

    /**
     * Reçoit les valeurs d'un capteur et les enregistre dans la BDD
     * @Rest\Put("/add-data")
     */
    public function addData(EntityManagerInterface $em, SensorRepository $sensorRepository, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $measure = new Measure();
        $measure->setTime(new \DateTime('now'));
        $em->persist($measure);

        foreach ($data as $key => $value)
        {
            $sensorMeasure = new SensorMeasure();
            $sensor = $sensorRepository->findOneBy(['mac' => $value['mac']]);

            $sensorMeasure->setMeasure($measure);
            $sensorMeasure->setValue($value['value']);
            $sensorMeasure->setSensor($sensor);

            $em->persist($sensorMeasure);
        }

        $em->flush();

        return new Response('');
    }
}
