<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups as AnnotationGroups;
use Symfony\Component\Validator\Constraint as Assert;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
 /**
  * @ApiResource(
   * collectionOperations={"GET", "POST"},
   * itemOperations={"GET", "PUT", "DELETE"},
   * subresourceOperations= {
   * "invoices_get_subresource" = {"path"="/customers/{id}/invoices"}
   * },
   * normalizationContext = {"groups" = {"invoices_read"}},
   * denormalizationContext={"groups"={"invoices_write"}},)
   * @ApiFilter(SearchFilter::class)
   * @ApiFilter(OrderFilter::class)
  */

class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[AnnotationGroups("invoices_read")]
    private $id;

    #[ORM\Column(type: 'float')]
    #[AnnotationGroups(["invoices_read", "invoices_write"])]
    private $amount;

    #[ORM\Column(type: 'datetime')]
    #[AnnotationGroups(["invoices_read"])]
    private $sentAt;

    #[ORM\Column(type: 'string', length: 255)]
    #[AnnotationGroups(["invoices_read", "invoices_write"])]
    private $status;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'invoices')]
    #[AnnotationGroups(["invoices_write", "invoices_read"])]
    #[ORM\JoinColumn(nullable: false)]
    private $customer;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[AnnotationGroups(["invoices_read", "invoices_write"])]
    private $chrono;
    #[AnnotationGroups("invoices_read")]
    public function getUtilisateur():User{
        return $this->customer->getUtilisateur();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getChrono(): ?int
    {
        return $this->chrono;
    }

    public function setChrono(?int $chrono): self
    {
        $this->chrono = $chrono;

        return $this;
    }

 


}
