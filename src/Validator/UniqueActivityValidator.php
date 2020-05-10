<?php

namespace App\Validator;

use App\Repository\ActivityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueActivityValidator extends ConstraintValidator
{
    private $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function validate($value, Constraint $constraint)
    {

        /* @var $constraint \App\Validator\UniqueActivity */

        if (null === $value->getName() || '' === $value->getName()) {
            return;
        }

        $existingActivity = $this->activityRepository->findOneBy([
            'name' => $value->getName()
        ]);

        if (!$existingActivity || $existingActivity->getId() == $value->getId()) {
            return;
        }
        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value->getName())
            ->atPath('name')
            ->addViolation();
    }
}
