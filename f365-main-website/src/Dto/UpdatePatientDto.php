<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePatientDto
{
    #[Assert\Length(max: 20, maxMessage: "Title can not be longer than 20 characters.")]
    public ?string $title = null;

    #[Assert\Length(max: 100, maxMessage: "First name cannot be longer than 100 characters.")]
    public ?string $firstName = null;

    #[Assert\Length(max: 100, maxMessage: "Last name cannot be longer than 100 characters.")]
    public ?string $lastName = null;

    #[Assert\Date(message: "Date of birth must be a valid date.")]
    #[Assert\LessThan("today", message: "Date of birth must be in the past.")]
    public ?string $dob = null;
}