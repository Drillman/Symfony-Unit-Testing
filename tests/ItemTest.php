<?php
use App\Entity\Item;

use PHPUnit\Framework\TestCase;

final class ItemTest extends TestCase
{
    public function testIsValid(): void
    {
        $item = new Item();
        $item->setName('learn Python');
        $item->setContent('You need to learn Python');
        $item->setCreatedAt(new DateTime());
        $validation = $item->isValid();
        $this->assertEquals(true, $validation);
    }
    public function testHasNoContent(): void
    {
        $item = new Item();
        $item->setName('learn Python');
        $item->setContent('');
        $item->setCreatedAt(new DateTime());
        $validation = $item->isValid();
        $this->assertEquals(false, $validation);
    }
    public function testHasNoCreatedTimestamp(): void
    {
        $item = new Item();
        $item->setName('learn Python');
        $item->setContent('You need to learn Python');
        $validation = $item->isValid();
        $this->assertEquals(false, $validation);
    }
}
