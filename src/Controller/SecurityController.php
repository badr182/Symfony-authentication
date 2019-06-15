<?php

// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\Form\Extension\Core\Type\EmailType;

use App\Entity\User ;
use App\Services\MailerServices;

class SecurityController extends AbstractController
{

    private $mailerServices;

    public function __construct(MailerServices $mailerServices)
    {        
        $this->mailerServices = $mailerServices;
    }

    /**
     * 
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * 
     * @Route("/forgotPassword" , name="security.forgotPassword", methods={"GET","POST"})
     */
    public function updatePassword( Request $request )
    {        
        # check if email exist 
        # if exist send mail with token         
        $defaultData = ['message' => 'forgot password'] ;
        $form = $this->createFormBuilder($defaultData)
                ->add('email', EmailType::class,[
                    'label' => false,
                    'attr' => ['placeholder' => 'Email' ]
                ])
                ->getForm();
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            
            $em = $this->getDoctrine()->getEntityManager();
            $repository = $em->getRepository( User::class );            
            $email = $form->get('email')->getData();
            $user = $repository->findOneBy(['email' => $email ]);            
            # check if this email exist in database 
            # user exist 
            if( $user ){
                # generate token                 
                $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
                # flash messages success
                $this->addFlash('success', 'Check your email for a link to reset your password');
                #  durré de vie 
                $user->setResetPassword($token);
                $em->flush();
                # send mail with token 
            }else{
                # flash messages error 
                $this->addFlash('danger', "Can't find that email, sorry.");
            }

                        
            //dump( $token );

        }
        return $this->render('security/forgotPassword.html.twig',[
            'formForgotPassword' => $form->createView()
        ]);        
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {

    }
}