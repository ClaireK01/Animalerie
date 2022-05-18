<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Form\NewPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LostPasswordController extends AbstractController
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private ResetPasswordRepository $resetPasswordRep,
    ){}

    #[Route('/lost_password', name: 'app_lost_password')]
    public function index(Request $request): Response
    {
        // Formulaire pour la demande d'email pour reset le mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        // Si le formulaire est bien soumis et valide
        if($form->isSubmitted() && $form->isValid()){
                
                $mail = $form->getData('email');
                
                $userEntity = $this->userRepository->findOneBy(['email'=>$mail]);

                // Si l'utilisateur existe
                if($userEntity){
                    // Instancie une entite reset password avec son token, son user et son boolean isReset (false par defaut)
                    $resetPassword = new ResetPassword;
                    $resetPassword->setToken(uniqid());
                    $resetPassword->setIsReset(false);
                    $resetPassword->setUser($userEntity);
                    $this->em->persist($resetPassword);
                    $this->em->flush();
                    return $this->redirectToRoute('app_mailer', ['email'=> $userEntity->getEmail()]);
                } 
        }

        return $this->render('lost_password/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    #[Route('/mailer', name:'app_mailer')]
    public function mail(MailerInterface $mailer, HttpFoundationRequest $request){
        $mail = $request->query->get('email');
        // Vérifie si un user correspond à l'email donné
        $userEntity = $this->userRepository->findOneBy(['email'=>$mail]);
        // recupere une entité resetPassword a partir du user
        $resetPassword = $this->resetPasswordRep->findOneBy(['user'=>$userEntity]);
        // Si le user n'est pas null
        if($userEntity){
            $token = $resetPassword->getToken();
            // Permet de générer une url adaptable en fonction du domaine
            $url = $this->generateUrl('app_reset',['token' =>$token], UrlGeneratorInterface::ABSOLUTE_URL);
            // Cree et envoie un email
            $email = new Email();
            dd($url);
            $email
                ->from('animalerie15@gmail.com')
                ->to($userEntity->getEmail())
                ->subject('Réinitialisation de mot de passe')
                
                ->html("<h1>Réinitialiser votre mot de passe</h1>
                <p>Cliquer sur le lien ci joint pour changer votre mot de passe:<br>
                    <a href='$url'>Changer mon mot de passe</a></p>");
            $mailer->send($email);
            // Notifie le user
            $message = 'Un mail vous a été communiqué. Merci de vérifier votre boite mail.';

            return $this->render('lost_password/confirmation_reset.html.twig', [
                'message' => $message,
            ]);

        }else{
            $message = 'Il y eu un problème lors de la réinitialisation de votre mot de passe.';
            
            return $this->render('lost_password/confirmation_reset.html.twig', [
                'message' => $message,
            ]);
        }
       
    }

        #[Route('/reset_password/{token}', name:'app_reset')]
        public function reset($token, Request $request){

            $resetEntity = $this->resetPasswordRep->findOneBy(['token'=>$token]);

            // Verifie le token correspond bien à une entité resetPassword
            if($resetEntity){
                // Verifie si le token n'a pas déja été utilisé
                if($resetEntity->getIsReset() === false){
                    // Crée le formulaire
                    $form = $this->createForm(NewPasswordType::class);
                    $form->handleRequest($request);
                    // Si le forumulaire est remplis

                    if($form->isSubmitted() && $form->isValid()){
                        dump($form->getData()['password']);
                        
                        // Si le mot de passe est bien le meme dans les 2 champs
                        if($form->getData()['password'] == $form->getData()['password_confirmation']){

                            // Recupere le user a partir du resetPassword
                            $userEntity = $resetEntity->getUser();
                            $password = $form->getData()['password'];
                            $userEntity->setPassword($this->userPasswordHasher->hashPassword(
                                $userEntity,
                                $password
                            ));

                            // Change le status de isReset
                            $resetEntity->setIsReset(true);

                            // Met a jour dans la base
                            $this->em->persist($userEntity);
                            $this->em->flush();
                            
                            // Notifie l'utilisateur
                            $message = 'Votre mot de passe à bien été changé';
                            return $this->render('lost_password/confirmation_reset.html.twig', [
                                'message' => $message,
                            ]);
                                

                        }else{
                            $form->addError( $error = new FormError('Votre mot de passe ne correspond pas dans les deux champs.'));
                            $errorMessage = $error->getMessage();
                            return $this->render('lost_password/reset_password.html.twig',[
                                'form'=>$form->createView(),
                                'error'=>$errorMessage
                            ]);
                        }

                        
                    }

                    return $this->render('lost_password/reset_password.html.twig',[
                        'form'=>$form->createView(),
                    ]);

                }else{
                    $message = 'Jeton de réinitialisation épuisé.';
                    return $this->render('lost_password/confirmation_reset.html.twig', [
                        'message' => $message,
                    ]);
                }

            }else{
                $message = 'Token invalide.';
                    return $this->render('lost_password/confirmation_reset.html.twig', [
                        'message' => $message,
                    ]);
            }

            
        }

        
}

