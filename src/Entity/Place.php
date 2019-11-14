<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 */
class Place
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande", inversedBy="id")
     */
    private $commande_id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="App\Entity\Evenement", inversedBy="id")
     */
    private $evenement_id;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommandeId(): ?int
    {
        return $this->commande_id;
    }

    public function setCommandeId(int $commande_id): self
    {
        $this->commande_id = $commande_id;

        return $this;
    }

    public function getEvenementId(): ?int
    {
        return $this->evenement_id;
    }

    public function setEvenementId(int $evenement_id): self
    {
        $this->evenement_id = $evenement_id;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
