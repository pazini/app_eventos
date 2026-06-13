<?php

namespace Tests\Unit;

use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventTicketType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EventAuditTest extends TestCase
{
    /**
     * @dataProvider auditableModelProvider
     */
    public function test_authenticated_user_is_recorded_on_creation(string $modelClass): void
    {
        $user = $this->authenticatedUser();
        $model = new $modelClass();

        $this->dispatchModelEvent('creating', $model);

        $this->assertSame($user->id, $model->created_by);
        $this->assertSame($user->id, $model->updated_by);
    }

    /**
     * @dataProvider auditableModelProvider
     */
    public function test_authenticated_user_is_recorded_on_update(string $modelClass): void
    {
        $user = $this->authenticatedUser();
        $model = new $modelClass();
        $model->updated_by = '00000000-0000-4000-8000-000000000000';

        $this->dispatchModelEvent('updating', $model);

        $this->assertSame($user->id, $model->updated_by);
    }

    public function auditableModelProvider(): array
    {
        return [
            'event' => [Event::class],
            'ticket type' => [EventTicketType::class],
        ];
    }

    private function authenticatedUser(): User
    {
        $user = new User();
        $user->id = '11111111-1111-4111-8111-111111111111';
        Auth::setUser($user);

        return $user;
    }

    private function dispatchModelEvent(string $event, Model $model): void
    {
        $model::getEventDispatcher()->dispatch(
            "eloquent.{$event}: ".$model::class,
            $model
        );
    }
}
