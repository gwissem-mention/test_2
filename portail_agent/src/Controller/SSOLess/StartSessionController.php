<?php

declare(strict_types=1);

namespace App\Controller\SSOLess;

use App\Entity\User;
use App\Security\SSOLess;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;

class StartSessionController extends AbstractController
{
    #[Route(path: '/sso-less/start-session', name: 'app_sso_less_start_session', condition: "env('ENABLE_SSO') === 'false'")]
    public function __invoke(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('user', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('u')
                        ->orderBy('u.serviceCode', 'ASC');
                },
                'choice_label' => static fn (User $user): string => sprintf(
                    '%s (%s) %s',
                    $user->getAppellation(),
                    $user->getInstitution()->value,
                    $user->isSupervisor() ? '(Superviseur)' : ''
                ),
                'constraints' => [new NotNull()],
            ])->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->get('user')->getData();

            $response = $this->redirectToRoute('home');
            $response->headers->setCookie(
                Cookie::create(SSOLess::COOKIE_NAME, (string) $user->getId())
            );

            return $response;
        }

        return $this->render('pages/start_session.html.twig', ['form' => $form]);
    }
}
