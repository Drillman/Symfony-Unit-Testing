<?php
use App\Entity\Item;
use App\Entity\User;

use App\Entity\Todolist;
use PHPUnit\Framework\TestCase;

final class TodolistTest extends TestCase
{
    protected $todolist;
    protected $user;
    protected $validItem;

    protected function setUp(): void
    {
        $this->user = new User();
        $this->user->setName("Lucas");
        $this->user->setFirstname("Lavander");
        $this->user->setPassword("TestDepuisLespace");
        $this->user->setAge(21);
        $this->user->setEmail("Lucaslavander@test.fr");

        $this->todolist = new TodoList();
        $this->todolist->setUserId($this->user);

        $initialItem = new Item();
        $initialItem->setName('inital item');
        $initialItem->setCreatedAt(new DateTime());

        $this->todolist->addItem($initialItem);

        $this->validItem = new Item();
        $this->validItem->setName('learn Python');
        $this->validItem->setContent('You need to learn Python');
        $this->validItem->setCreatedAt(new DateTime());

    }
    public function testCanAddItem(): void
    {
        $validation = $this->todolist->canAddItem($this->validItem);
        $this->assertEquals(true, $validation);
    }
}
