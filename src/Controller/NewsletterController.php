<?php

// src/Controller/NewsletterController.php
namespace App\Controller;

use App\Entity\Subscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewsletterController extends AbstractController
{
    #[Route('/subscribe', name: 'newsletter_subscribe', methods: ['POST'])]
    public function subscribe(
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $name = $data['name'] ?? null;

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json(['error' => 'Nieprawidłowy e-mail.'], Response::HTTP_BAD_REQUEST);
        }

        $subscriber = new Subscriber();
        $subscriber->setEmail($email);
        $subscriber->setName($name);
        $em->persist($subscriber);
        $em->flush();

        $email = (new Email())
            ->from('newsletter@book.d3v.eu')
            ->to($subscriber->getEmail())
            ->subject('Potwierdź subskrypcję')
            ->html(sprintf(
                'Kliknij, aby potwierdzić: <a href="%s">%s</a>',
                $this->generateUrl('confirm_subscription', ['token' => $subscriber->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
                'Potwierdź subskrypcję'
        ));

        $mailer->send($email);
        return $this->json(['message' => 'Dziękujemy za zapis! Sprawdź skrzynkę e-mail i potwierdź subskrypcję.']);
    }

    #[Route('/confirm/{token}', name: 'confirm_subscription', methods: ['GET'])]
    public function confirm(string $token, EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(Subscriber::class);
        $subscriber = $repo->findOneBy(['token' => $token]);

        if (!$subscriber) {
            throw $this->createNotFoundException('Nieprawidłowy token.');
        }

        $subscriber->setConfirmed(true);
        $em->flush();

        return new Response('<h1>Dziękujemy za potwierdzenie subskrypcji!</h1>');
    }
}