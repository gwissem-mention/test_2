<?php

declare(strict_types=1);

namespace App\Controller\SSOLess;

use App\AppEnum\Institution;
use App\Entity\User;
use App\Referential\Entity\Unit;
use App\Referential\Repository\UnitRepository;
use App\Repository\UserRepository;
use App\Security\SSOLessInterface;
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
    public function __invoke(Request $request, UnitRepository $unitRepository, UserRepository $userRepository): Response
    {
        $choices = [];
        foreach ($userRepository->findAll() as $user) {
            /** @var Unit $unit */
            $unit = $unitRepository->findByService((string) $user->getServiceCode());
            if ($unit instanceof Unit) {
                /** @var Institution $institutionCode * */
                $institutionCode = $unit->getInstitutionCode();
                $choices[$institutionCode->value][$unit->getName()][] = $user;
            }
        }

        foreach ($choices as $institutionCode => $units) {
            ksort($choices[$institutionCode]);
        }

        $form = $this->createFormBuilder()
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choices' => $choices,
                'choice_label' => static fn (User $user): string => sprintf(
                    '%s %s',
                    $user->getAppellation(),
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
                Cookie::create(SSOLessInterface::COOKIE_NAME, (string) $user->getId())
            );

            return $response;
        }

        return $this->render('pages/start_session.html.twig', ['form' => $form]);
    }
}
