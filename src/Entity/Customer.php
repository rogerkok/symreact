<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups as AnnotationGroups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Invoice;

/**
  * @ApiResource(
   * collectionOperations={"GET", "POST"},
   * itemOperations={"GET", "PUT", "DELETE"},
   * normalizationContext={"groups"={"customers_read"}},
   * denormalizationContext={"groups"={"customers_write"}},
   * subresourceOperations= {
   * "invoices_get_subresource" = {"path"="/customers/{id}/invoices"}
   * })
   * @ApiFilter(SearchFilter::class)
   * @ApiFilter(OrderFilter::class)
  */
#[ORM\Entity(repositoryClass: CustomerRepository::class)]

class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[AnnotationGroups("customers_read")]
    #[ORM\Column(type: 'integer')]
    
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[AnnotationGroups(["customers_read", "customers_write"])]
    /**
     * firstName
     *
     *
     *
     * @Assert\NotBlank(message = "Le prénom du client est obligatoire")
     * @Assert\Length(min=4, minMessage="Le prénom doit avoir au minimum 4 caractères", max = 255, maxMessage="Le prénom doit avoir au maximum 255 caractères")
     */
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[AnnotationGroups(["customers_read", "customers_write"])]
    
    /**
     * lastName
     *
     * @var [string]
     * 
     * @Assert\NotBlank(message = "Le nom du client est obligatoire")
     * @Assert\Length(min=4, minMessage="Le nom doit avoir au minimum 4 caractères", max = 255, maxMessage="Le prénom doit avoir au maximum 255 caractères")
     */
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[AnnotationGroups(["customers_read", "customers_write"])]
   
    /**
     * Email
     *
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide."
     * )
     */
    private $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[AnnotationGroups(["customers_read", "customers_write"])]
    private $company;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Invoice::class)]
    /**
     * @ApiSubresource
     */
    #[AnnotationGroups("customers_read")]
    private $invoices;
    #[AnnotationGroups(["customers_read", "customers_write"])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: true)]
    
    private $utilisateur;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
    }
    #[AnnotationGroups("customers_read")]
    public function getTotalAmount():float{
        return array_reduce($this->invoices->toArray(), function($total, $invoice){
            return $total+$invoice->getAmount();
        }, 0);
    }
    #[AnnotationGroups("customers_read")]
    public function getUmpaidAmount():float{
        return array_reduce($this->invoices->toArray(), function($total, $invoice){
            return $total+($invoice->getStatus()==='PAID'|| $invoice->getStatus()==='CANCELED'? 0 :$invoice->getAmount());
        }, 0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setCustomer($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getCustomer() === $this) {
                $invoice->setCustomer(null);
            }
        }

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
