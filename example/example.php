<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Kata\TemplateManager;
use Kata\Entity\Quote;
use Kata\Entity\Template;

$faker = Faker\Factory::create();

$template = new Template(
    1,
    'Votre voyage avec une agence locale [quote:destination_name]',
    "
Bonjour [user:first_name],

Merci d'avoir contacté un agent local pour votre voyage [quote:destination_name].

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
");
$templateManager = new TemplateManager();

$message = $templateManager->getTemplateComputed(
    $template,
    [
        'quote' => new Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->date())
    ]
);

echo $message->subject . "\n" . $message->content;