<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\EmailController;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TodolistRepository")
 */
class Todolist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="todolist")
     */
    private $user_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="todolist")
     */
    private $items;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId(User $userId): self
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item, EmailController $emailService = null): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setTodolist($this);
        }
        if (!$emailService) {
            $emailService = new EmailController();
        }
        $emailService::sendEmail($this->getUserId());

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getTodolist() === $this) {
                $item->setTodolist(null);
            }
        }

        return $this;
    }

    public function canAddItem(Item $item)
    {
        $todoListItems = $this->getItems();
        if (sizeof($todoListItems) >= 10) {
            return null;
        }
        if (!$item->isValid()) {
            return null;
        }
        // Test if the item name is unique
        $itemsName = $todoListItems->map(function($item) {
            return $item->getName();
        });
        if (in_array($item->getName(), (array) $itemsName)) {
            return null;
        }

        // Test if it's been at least 30 minutes since the previous creation
        $latestItemAddDate = $todoListItems->last()->getCreatedAt();
        if (!$latestItemAddDate) { return $item; }
        $interval = $latestItemAddDate->diff($item->getCreatedAt());
        $interval = (int) $interval->format('%i');
        if($interval < 30) {
            return null;
        }
        return $item;
    }
}
