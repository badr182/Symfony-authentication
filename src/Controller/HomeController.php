<?php
namespace App\Controller ;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\HttpFoundation\Request ;

use Symfony\Component\Routing\Annotation\Route ;

class HomeController  extends AbstractController{


    /**
     * 
     * @Route("/home", name="home.index")
     */
    public function index()
    {
        #return new Response('hi there ');
        $name = " full stack ";
        return $this->render('home/home.html.twig',[
            'name' => $name
        ]);
    }

    /**
     * 
     * @Route("/", name="home")
     */
    public function home()
    {
        #return new Response('hi there ');
        $name = " full stack ";
        return $this->redirectToRoute('home.index');
    }

}