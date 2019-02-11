<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/12/18
 * Time: 16:13
 */

namespace App\Service;


use App\Entity\Client;
use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailManager
{
    const FROM_EMAIL = 'no-reply@atyplace.com';

    protected $mailer;
    private $router;
    private $twig;
    private $spool;

    /**
     * Mailer constructor.
     * @param Swift_Mailer $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment $twig
     * @param \Swift_Spool $spool
     */
    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, \Swift_Spool $spool)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->spool = $spool;
    }

    private function sendEmail(string $template, array $to, string $subject, $vars = [])
    {
        $vars['template'] = $template;
        $html = $this->twig->render(
            sprintf('email/%s.html.twig', $template),
            $vars
        );

        $message = (new Swift_Message($subject))
            ->setFrom(self::FROM_EMAIL)
            ->setTo($to)
            ->setBody($html, 'text/html');

        return $this->mailer->send($message) === 1;
    }

    public function sendCollectivePasswordEmail(User $user)
    {
        $link = $this->router->generate('app_client_update_password', [
            'token' => $user->getToken()
        ], $this->router::ABSOLUTE_URL);

        $vars = ['ctaUrl' => $link];

        return $this->sendEmail(
            'reset-password',
            [$user->getEmail()],
            'Aty\'place | Réinitialisation de votre mot de passe',
            $vars
        );
    }

    public function sendSendBookingOffer(Client $client, string $offerName)
    {
        $vars = ['offer_title' =>  $offerName];

        return $this->sendEmail(
            'booking-offer',
            [$client->getEmail()],
            "Aty'place | Demande de reservation",
            $vars
        );
    }
}