<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 08/02/19
 * Time: 11:02
 */

namespace App\Twig;


use App\Entity\Request;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Service\DateAvailableManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $dateAvailableManager;

    private $rolesEnum;

    public function __construct(DateAvailableManager $dateAvailableManager, RolesEnum $rolesEnum)
    {
        $this->dateAvailableManager = $dateAvailableManager;
        $this->rolesEnum = $rolesEnum;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('statut', [$this, "statusDisplay"]),
            new TwigFilter('roleDisplay', [$this, "roleDisplay"]),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('displayBookingDate', [$this, 'numberBookingDate'])
        ];
    }

    public function statusDisplay(int $statusId)
    {
        return array_search($statusId, User::STATUS);
    }

    public function roleDisplay(string $role)
    {
        $roles = $this->rolesEnum->getRoleEnum();

        return $roles[$role];
    }

    public function numberBookingDate(Request $request)
    {
        return $this->dateAvailableManager->displayBookingDate($request);
    }
}
