<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['default', 'ci'];
    }

    public function load(ObjectManager $manager): void
    {
        $questions = [
            new Question(
                'Qu’est-ce qu’un témoin ?',
                'Il s’agit de toute personne qui aurait assisté aux faits dont vous avez été victime et qui accepterait de témoigner pour vous.'
            ),
            new Question(
                'Qu’est-ce qu’un suspect ?',
                'Il s’agit de la personne qui a potentiellement commis les faits dont vous avez été victime.'
            ),
            new Question(
                "Qu'est-ce qu'une plainte ?",
                "Le dépôt de plainte permet à une victime d’infraction pénale d'informer le procureur de la République."
            ),
        ];

        foreach ($questions as $question) {
            $manager->persist($question);
        }

        $manager->flush();
    }
}
