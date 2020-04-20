<?php
use App\Entity\User;
use PHPUnit\Framework\TestCase;


final class UserTest extends TestCase
{
    public function testIsValid(): void
    {
        $user = new User();
        $user->setName("Lucas");
        $user->setFirstname("Lavander");
        $user->setPassword("TestDepuisLespace");
        $user->setAge(16);
        $user->setEmail("Lucas.Lavander@test.fr");
        $validation = $user->isValid();
        $this->assertEquals(true, $validation);
    }

    public function testNameEmpty(): void
    {
        $user = new User();
        $user->setFirstname("Lavander");
        $user->setPassword("TestDepuisLespace");
        $user->setAge(16);
        $user->setEmail("Lucas.Lavander@test.fr");
        $validation = $user->isValid();
        $this->assertEquals(true, $validation);
    }
}
