<?php
use Mockery as m;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Controller\EmailController;

final class EmailTest extends TestCase
{
    public function testSendEmail(): void
    {
        $user = m::mock(User::class);
        $user->shouldReceive('getAge')
          ->andReturn(20);
        $this->assertEquals(true, EmailController::sendEmail($user));
    }

    public function tearDown(): void {
        m::close();
    }
}
