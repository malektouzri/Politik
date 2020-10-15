<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/getCouncillors", name="getCouncillors")
     */
    public function getCouncillorsAction()
    {
        $councillors = file_get_contents($this->get('kernel')->getRootDir() . '/../web/assets/api/councillor.json');

        $json = json_decode($councillors, true);


        return $this->render('default/councillor.html.twig',
            ['councillors' => $json]
        );
    }

    /**
     * @Route("/getCouncillorsApi", name="getCouncillorsApi")
     */
    public function getCouncillorsApiAction()
    {
        $list = [];
        for($i=1; $i<6; $i++)
        {
            array_push($list, file_get_contents('http://ws-old.parlament.ch/councillors?entryDateFilter=2018/12/31&format=xml&pageNumber='.$i));
        }

        $response = new Response($this->renderView('default/councillor.xml.twig', ['councillors' => $list]));
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');
        return $response;
    }

    /**
     * @Route("/getRandomCouncillors", name="getRandomCouncillors")
     */
    public function getRandomCouncillorsAction()
    {
        $list = [];
        while(count($list)<5)
        {
            if(file_get_contents('http://ws-old.parlament.ch/councillors/'.rand(74,4317).'?format=xml'))
            {
                $councillor = file_get_contents('http://ws-old.parlament.ch/councillors/'.rand(74,4317).'?format=xml');
                array_push($list,$councillor);
            }
        }

        $response = new Response($this->renderView('default/random.xml.twig', ['councillors' => $list]));
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');
        return $response;
    }
}
