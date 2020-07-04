<?php


namespace App\Dto;


use Symfony\Component\Validator\Constraints as Assert;

class CreateProduct
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    public string $title;
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="double")
     */
    public float $price;
}