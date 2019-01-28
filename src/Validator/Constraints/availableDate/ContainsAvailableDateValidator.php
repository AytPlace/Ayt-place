<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 24/01/19
 * Time: 20:21
 */

namespace App\Validator\Constraints\availableDate;


use App\Repository\OfferRepository;
use App\Service\DateAvailableManager;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsAvailableDateValidator extends ConstraintValidator
{

    private $offerRepository;
    private $dateAvailableManager;

    public function __construct(OfferRepository $offerRepository, DateAvailableManager $dateAvailableManager)
    {
        $this->offerRepository = $offerRepository;
        $this->dateAvailableManager = $dateAvailableManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (count($value->toArray()) < 1) {
            return;
        }

        if (!$constraint instanceof ContainsAvailableDate) {
            throw new UnexpectedTypeException($constraint, ContainsAvailableDate::class);
        }

        if (!$value instanceof PersistentCollection) {
            throw new UnexpectedValueException($value, 'interval de date');
        }

        foreach ($value as $availableDate) {
            if (is_null($availableDate->getId()) && !$this->dateAvailableManager->checkDateAvailable($availableDate)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameters(['{{ startDate }}', $availableDate->getStartDate(), '{{ endDate }}', $availableDate->getEndDate()])
                    ->addViolation()
                ;
            }
        }
    }
}