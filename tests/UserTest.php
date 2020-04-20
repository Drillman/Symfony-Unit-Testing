<?php
use App\Entity\User;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

final class UserTest extends TestCase
{
    public function testIsValid(): void
    {
        $user = new User();
        $user->setName("Lucas");
        $user->setFirstname("Lavander");
        $user->setPassword("TestDepuisLespace");
        $user->setAge(16);
        $user->setEmail("Lucaslavander@test.fr");
        $validation = $user->isValid();
        $this->assertEquals(true, $validation);
    }

    public function testNoFirstname(): void
    {
        $user = new User();
        $user->setName("Lucas");
        $user->setPassword("TestDepuisLespace");
        $user->setAge(16);
        $user->setEmail("Lucas.Lavander@test.fr");
        try {
            $user->isValid();
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), 'name or firstname is empty');
        }
    }
    public function testNoName(): void
    {
        $user = new User();
        $user->setFirstname("Lucas");
        $user->setPassword("TestDepuisLespace");
        $user->setAge(16);
        $user->setEmail("Lucas.Lavander@test.fr");
        try {
            $user->isValid();
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), 'name or firstname is empty');
        }
    }
    public function testInvalidEmail(): void
    {
        $user = new User();
        $user->setName("Lucas");
        $user->setFirstname('Lavander');
        $user->setPassword("TestDepuisLespace");
        $user->setAge(16);
        $user->setEmail("Lucas.Lavander@test.");
        try {
            $user->isValid();
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), 'Lucas.Lavander@test. is not a valid email format');
        }
    }
    public function testTooYoung(): void
    {
        $user = new User();
        $user->setName("Lucas");
        $user->setFirstname('Lavander');
        $user->setPassword("TestDepuisLespace");
        $user->setAge(10);
        $user->setEmail("Lucas.Lavander@test.fr");
        try {
            $user->isValid();
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), 'The age can\'t be under 13');
        }
    }
    public function testInvalidPassword(): void
    {
        $user = new User();
        $user->setName("Lucas");
        $user->setFirstname('Lavander');
        $user->setPassword("a");
        $user->setAge(10);
        $user->setEmail("Lucas.Lavander@test.fr");
        try {
            $user->isValid();
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), 'The password length must be between 8 and 40');
        }
    }
}
