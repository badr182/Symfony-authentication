<?php
namespace App\Controller ;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\HttpFoundation\Request ;

use Symfony\Component\Routing\Annotation\Route ;

class AdminController extends AbstractController{

    /**
     * 
     * @Route("/profile", name="admin.index")
     */
    public function index(Request $request){


        return $this->render('profile/index.html.twig');

    }

}