<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 08/02/19
 * Time: 11:02
 */

namespace App\Twig;


use App\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('statut', [$this, "statusDisplay"])
        ];
    }

    public function statusDisplay(int $statusId)
    {
        return array_search($statusId, User::STATUS);
    }
}