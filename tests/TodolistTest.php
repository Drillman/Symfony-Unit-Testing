<?php
use Mockery as m;
use App\Entity\Item;
use App\Entity\User;
use App\Entity\Todolist;
use PHPUnit\Framework\TestCase;
use App\Controller\EmailController;

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
        $this->todolist->setUser($this->user);

        $initialItem = new Item();
        $initialItem->setName('inital item');
        $initialItem->setCreatedAt(new DateTime());

        $this->todolist->addItem($initialItem);

        $this->validItem = new Item();
        $this->validItem->setName('learn Python');
        $this->validItem->setContent('You need to learn Python');
        $creationDate = new DateTime();
        $creationDate = $creationDate->add(new DateInterval('PT45M'));
        $this->validItem->setCreatedAt($creationDate);

    }
    public function testSendEmail(): void
    {
        $emailService = m::mock(EmailController::class);
        $emailService->shouldReceive('sendEmail')->andReturn(true);
        $this->assertEquals(
            $this->todolist,
            $this->todolist->addItem($this->validItem, $emailService)
        );
    }

    public function testWrongInterval(): void
    {
        $itemTooSoon = $this->validItem;
        $creationDate = new DateTime();
        $creationDate = $creationDate->add(new DateInterval('PT10M'));
        $itemTooSoon->setCreatedAt($creationDate);
        $validation = $this->todolist->canAddItem($itemTooSoon);
        $this->assertEquals(null, $validation);
    }

    public function testFullTodoList(): void
    {
        for($i = 0; $i < 8; $i++) {
            $this->todolist->addItem($this->validItem);
        }
        $item = $this->validItem;
        $item->setName('learn Kobol');
        $validation = $this->todolist->canAddItem($this->validItem);
        $this->assertEquals(null, $validation);
    }

    public function testInvalidItem(): void
    {
        $invalidItem = $this->validItem;
        $invalidItem->setContent('');
        $validation = $this->todolist->canAddItem($invalidItem);
        $this->assertEquals(null, $validation);
    }

    public function testDuplicateName(): void
    {
        $this->todolist->addItem($this->validItem);
        $validation = $this->todolist->canAddItem($this->validItem);
        $this->assertEquals(null, $validation);
    }

    public function tearDown(): void {
        Mockery::close();
    }
}
