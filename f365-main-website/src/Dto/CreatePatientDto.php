<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[Assert\Callback('validateDob')]
class CreatePatientDto
{
    #[Assert\NotBlank(message: "Title is required.")]
    #[Assert\Length(max: 20, maxMessage: "Title can not be longer than 20 characters.")]
    public ?string $title = null;

    #[Assert\NotBlank(message: "First name is required.")]
    #[Assert\Length(max: 100, maxMessage: "First name cannot be longer than 100 characters.")]
    public ?string $firstName = null;

    #[Assert\NotBlank(message: "Last name is required.")]
    #[Assert\Length(max: 100, maxMessage: "Last name cannot be longer than 100 characters.")]
    public ?string $lastName = null;

    #[Assert\NotBlank(message: "Date of birth is required.")]
    #[Assert\Date(message: "Date of birth must be a valid date.")]
    public ?string $dob = null;

    public function validateDob(ExecutionContextInterface $context): void
    {
        if ($this->dob) {
            $dobDate = \DateTime::createFromFormat('Y-m-d', $this->dob);
            if (!$dobDate) {
                $context->buildViolation("Invalid date format. Use Y-m-d.")
                    ->atPath('dob')
                    ->addViolation();
                return;
            }

            $today = new \DateTime();
            if ($dobDate >= $today) {
                $context->buildViolation("Date of birth must be in the past.")
                    ->atPath('dob')
                    ->addViolation();
            }
        }
    }
}