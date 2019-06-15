<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Services\MailerServices ;

class RegistrationController extends AbstractController
{
    private $mailerServices;

    public function __construct(MailerServices $mailerServices)
    {        
        $this->mailerServices = $mailerServices;
    }

    /**
     * 
     * @Route("/register", name="app_register", methods={"GET","POST"})
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder): Response
    {
        //dump( $this->mailerServices->index() );
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            # encode the plain password            
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            #token             
            $token = $this->generateToken();            
            $user->setToken($token);     
            $user->setActive(false);            
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist( $user );
            $entityManager->flush();

            # flash message 
            $this->addFlash('success', 'Your account is created');
            # send mail
            $this->mailerServices->send("badr");
            // do anything else you need here, like send an email
            return $this->redirectToRoute('home.index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    /**
     * confirmation
     * @Route("/confirm/{user}/{id}/{token}",
     *         name="app_confirm",
     *         requirements={"page"="\d+"},
     *         methods={"GET"})
     */
    public function confirm($user, $id, $token): Response
    {

        # check if user exist        
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository( User::class );        
        $user = $repository->find($id);
        
        # user exist
        if( $user && hash_equals( $user->getToken(), $token) ){                         
            # activate user 
            $user->setActive(true);
            $em->flush();
            # send flash message  
            $this->addFlash('success', 'Your account is activated');
            # autneticate user             
            return $this->redirectToRoute('home.index');
        }
        
        $this->addFlash('danger', 'Please check your email or send a new verification code');
        return $this->redirectToRoute('home.index');

    }

    /**
     * generate token 
     * deplacer fonction 
     */
    public function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

}
