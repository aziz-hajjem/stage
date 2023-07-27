<?php

namespace App\Entity;

use App\Repository\CongeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CongeRepository::class)]
class Conge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"id_conge")]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false,name:"id_user",referencedColumnName:"id_user")]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"Ce champs est obligatoire")]
    #[Assert\GreaterThan('today',message:"La date Debut doit etre postérieur a la date d'aujourd'hui")]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"Ce champs est obligatoire")]
    #[Assert\Expression("this.getDateDebut() < this.getDateFin()",message:"La date fin doit etre postérieur a la date debut")  ]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column]
    private ?string $statut_conge = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Ce champs est obligatoire")]
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getStatutConge(): ?string
    {
        return $this->statut_conge;
    }

    public function setStatutConge(string $statut_conge): static
    {
        $this->statut_conge = $statut_conge;

        return $this;
    }


    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
