<?php

namespace Tests\Unit;

use App\User;
use App\Cheque;
use Carbon\Carbon;
use Tests\TestCase;
use App\Notifications\ChequeNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Thomasjohnkane\Snooze\ScheduledNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
	public function setUp() :void {
		parent::setUp();
		// $this->artisan('migrate --seed');
	}

	public function test_database_notification_sent() {
        Notification::fake();
        $user = User::first();

        // Assert that no notifications were sent...
        Notification::assertNothingSent();

        $cheque = Cheque::create(['due_date' => now()->addDay()]);

        Notification::assertSentTo(
            $user,
            ChequeNotification::class,
            function ($notification, $channels) use ($cheque) {
                return $notification->cheque === $cheque;
            }
        );

        // Assert a notification was sent to the given users...
        Notification::assertSentTo(
            [$user], ChequeNotification::class
        );
	}

    public function testItCreatesAndSendsNotification()
    {
        Notification::fake();
        Carbon::setTestNow('2025-01-01 01:00:00');

        $target = User::find(1);
        $cheque = Cheque::first();

        /** @var ScheduledNotification $notification */
        $notification = $target->notifyAt(new ChequeNotification($cheque), Carbon::now()->addSeconds(10));

        $this->assertInstanceOf(ScheduledNotification::class, $notification);
        $this->assertDatabaseHas('scheduled_notifications', ['id' => $notification->getId()]);

        $notification->sendNow();

        $this->assertTrue($notification->isSent());
        $this->assertFalse($notification->isRescheduled());
        $this->assertFalse($notification->isCancelled());
        $this->assertSame(ChequeNotification::class, $notification->getType());

        $this->assertEquals(Carbon::now(), $notification->getSentAt());
        $this->assertNull($notification->getCancelledAt());
        $this->assertNull($notification->getRescheduledAt());

        $this->assertInstanceOf(\DateTimeInterface::class, $notification->getSendAt());
        $this->assertInstanceOf(\DateTimeInterface::class, $notification->getCreatedAt());
        $this->assertInstanceOf(\DateTimeInterface::class, $notification->getUpdatedAt());

        $this->assertEquals(1, $notification->getTargetId());
        $this->assertSame(User::class, $notification->getTargetType());

        Notification::assertSentTo(
            $target,
            ChequeNotification::class,
            function ($notification) use ($cheque) {
                return $notification->cheque->id == $cheque->id;
            }
        );

        $this->assertNotNull(ScheduledNotification::find($notification->getId()));
    }

}
