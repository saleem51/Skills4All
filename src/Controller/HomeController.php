<?php

namespace App\Controller;

use App\data\SearchData;
use App\Form\SearchForm;
use App\Repository\CarRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(protected CarRepository $carRepository){}


    #[Route('/', name: 'app_home')]
    public function home(Request $request): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);

        $form->handleRequest($request);


        $cars = $this->carRepository->findSearch($data);



        return $this->render('home/home.html.twig', [
            'cars' => $cars,
            'form' => $form->createView(),
        ]);
    }
}
