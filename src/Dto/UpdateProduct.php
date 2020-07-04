<?php


namespace App\Dto;


use Symfony\Component\Validator\Constraints as Assert;

class UpdateProduct
{
    /**
     * @Assert\Type(type="string")
     */
    public ?string $title = null;
    /**
     * @Assert\Type(type="double")
     */
    public ?float $price = null;
}