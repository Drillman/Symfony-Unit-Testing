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

    public function testIsValidPassword(): void
    {
        $user = new User();
        $user->setPassword("TestDepuisLespace");
        $validation = $user->isValidPassword();
        $this->assertEquals(true, $validation);
    }

    public function testIsValidAge(): void
    {
        $user = new User();
        $user->setAge(16);
        $validation = $user->isValidAge();
        $this->assertEquals(true, $validation);
    }
    
}