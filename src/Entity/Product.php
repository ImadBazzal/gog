<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use App\Dto\CreateProduct;
use App\Dto\UpdateProduct;
use App\Dto\ProductOutput;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity("title")
 * @ApiResource(
 *     output=ProductOutput::class,
 *     attributes={
 *         "pagination_client_enabled"=false,
 *         "pagination_client_items_per_page"=false,
 *         "pagination_items_per_page"=3
 *     },
 *     collectionOperations={
 *         "get"={"security"="is_granted('ROLE_USER')"},
 *         "post"={
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "input"=CreateProduct::class,
 *          }
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_USER')"},
 *         "put"={
 *             "security"="is_granted('ROLE_ADMIN')",
 *             "input"=UpdateProduct::class,
 *         },
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private string $title;

    /**
     * @ORM\Column(type="integer")
     */
    private int $price;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
