<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/12/18
 * Time: 16:13
 */

namespace App\Service;


use App\Entity\Client;
use App\Entity\Offer;
use App\Entity\Recipient;
use App\Entity\Request;
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

    public function sendEnableEmail(User $user)
    {
        $link = $this->router->generate('app_index_enable', [
            'token' => $user->getEnableToken()
        ], $this->router::ABSOLUTE_URL);

        $vars = ['ctaUrl' => $link];

        return $this->sendEmail('enable-user',
            [$user->getEmail()],
            "Aty'place | Activation de compte",
            $vars
        );
    }

    public function sendEnableRecipient(Recipient $recipient)
    {
        return $this->sendEmail('enable-recipient',
            [$recipient->getEmail()],
            "Aty'place | Activation de votre compte prestataire"
        );
    }

    public function sendDisableRequestToClient(Client $client, Request $request)
    {
        $vars = [
            'city'=> $request->getAvailableOffer()->getOffer()->getCity(),
            'startDate' => $request->getStartDate(),
            'endDate' => $request->getEndDate()
        ];

        return $this->sendEmail('disable-request-client',
            [$client->getEmail()],
            "Aty'palce | Votre demande à été annulé",
            $vars
        );
    }

    public function sendEnableRequestToCLient(Client $client, Request $request)
    {
        $vars = [
            'city'=> $request->getAvailableOffer()->getOffer()->getCity(),
            'startDate' => $request->getStartDate(),
            'endDate' => $request->getEndDate()
        ];

        return $this->sendEmail('enable-request-client',
            [$client->getEmail()],
            "Aty'palce | Votre demande de réservation à été accepter",
            $vars
        );
    }

    public function sendRemoveRecipientOffer(Request $request)
    {
        $vars = [
            'client_name' => $request->getClients()[0]->getLastname(). " ". $request->getClients()[0]->getFirstname(),
            'startDate' => $request->getStartDate()->format('d/m/y'),
            'endDate' => $request->getEndDate()->format('d/m/y')
        ];

        return $this->sendEmail('remove-recipient-request',
            [$request->getOffers()[0]->getRecipient()->getEmail()],
            "Aty'place | Annulation de la réservation du ". $request->getStartDate()->format('d/m/y'). " au ". $request->getEndDate()->format('d/m/y'),
            $vars
        );
    }

    public function sendContactForm(array $data)
    {
        return $this->sendEmail('contact-form',
            ["alexandre.vagnair@sooyoos.com"],
            "Aty'place | Demande de contact de ". $data["email"],
            $data
        );
    }
}