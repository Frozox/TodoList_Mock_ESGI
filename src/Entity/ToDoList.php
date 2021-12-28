<?php

namespace App\Entity;

use App\Repository\ToDoListRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ToDoListRepository::class)
 */
class ToDoList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="toDoList", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="toDoList", orphanRemoval=true)
     */
    private $items;

    public function __construct($name)
    {
        $this->name = $name;
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self|bool
    {
        if ($this->itemAddable($item)) {
            $this->items[] = $item;
            $item->setToDoList($this);
        }else{
            return false;
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getToDoList() === $this) {
                $item->setToDoList(null);
            }
        }

        return $this;
    }

    public function itemAddable(Item $item):bool {
        $items_liste = $this->items->toArray();
        if ($this->items->contains($item)){
            return false;
        }
        //On parcourt la liste d'items de la toDoListe et si la date de creation <= date creation de notre item - 30min OU si nom identique on renvoit false
        foreach ($items_liste as $item_liste){
            $created_at = $item_liste->getCreatedAt();
            $name = $item_liste->getName();
            if ($created_at >= $item->getCreatedAt() || $name === $item->getName()){
                return false;
            }
        }
        return true;
    }

    public function isValid(): bool
    {
        if (
            count($this->items) >= 0 &&
            count($this->items) <= 10
        ){
            return true;
        }else{
            return false;
        }
    }
}
