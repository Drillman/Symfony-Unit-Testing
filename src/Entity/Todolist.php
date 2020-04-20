<?php

namespace App\Entity;

use DateInterval;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
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
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="todolist")
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
     * @return Collection|User[]
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id[] = $userId;
            $userId->setTodolist($this);
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        if ($this->user_id->contains($userId)) {
            $this->user_id->removeElement($userId);
            // set the owning side to null (unless already changed)
            if ($userId->getTodolist() === $this) {
                $userId->setTodolist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setTodolist($this);
        }

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
            return 1;
        }
        if (!$item->isValid()) {
            return 2;
        }
        // Test if the item name is unique
        $itemsName = $todoListItems->map(function($item) {
            var_dump($item);
            return $item->name;
        });
        if (in_array($item->getName(), (array) $itemsName)) {
            return 3;
        }

        // Test if it's been at least 30 minutes since the previous creation
        $latestItemAddDate = array_reduce((array) $todoListItems, function($acc, $el) {
            return $el->getId() > $acc->getId() ? $el : $acc;
        })->getCreatedAt();
        if (!$latestItemAddDate) { return $item; }
        $allowedDate = new DateTime($latestItemAddDate->format('Y-m-d H:i:s'));
        $allowedDate = $allowedDate->add(new DateInterval('PT30M')); // latestItem add date + 30 minutes
        $interval = $allowedDate->diff(new DateTime());
        if((int) $interval->format('%i') < 30) {
            return 4;
        }

        return $item;
    }
}
