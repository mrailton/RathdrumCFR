<?php

declare(strict_types=1);

use App\Models\Member;
use App\Models\TrainingSession;

beforeEach(function (): void {
    $this->user = createAdminUser();
});

test('training sessions index requires authentication', function (): void {
    $response = $this->get(route('admin.training-sessions.index'));

    $response->assertRedirect(route('login'));
});

test('training sessions index displays for authenticated users', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.training-sessions.index'));

    $response->assertOk();
    $response->assertViewIs('admin.training-sessions.index');
});

test('training sessions index shows all sessions', function (): void {
    TrainingSession::factory()->count(3)->create();

    $response = $this->actingAs($this->user)->get(route('admin.training-sessions.index'));

    $response->assertOk();
    $response->assertViewHas('trainingSessions', fn ($trainingSessions) => 3 === $trainingSessions->count());
});

test('training sessions index can filter by date range', function (): void {
    TrainingSession::factory()->create(['date' => '2024-01-15', 'topic' => 'January Session']);
    TrainingSession::factory()->create(['date' => '2024-03-15', 'topic' => 'March Session']);
    TrainingSession::factory()->create(['date' => '2024-06-15', 'topic' => 'June Session']);

    $response = $this->actingAs($this->user)->get(route('admin.training-sessions.index', [
        'from' => '2024-02-01',
        'to' => '2024-04-30',
    ]));

    $response->assertOk();
    $response->assertViewHas('trainingSessions', fn ($trainingSessions) => 1 === $trainingSessions->count()
            && 'March Session' === $trainingSessions->first()->topic);
});

test('can view create training session page', function (): void {
    $response = $this->actingAs($this->user)->get(route('admin.training-sessions.create'));

    $response->assertOk();
    $response->assertViewIs('admin.training-sessions.create');
});

test('can create a new training session', function (): void {
    $members = Member::factory()->count(2)->create();

    $sessionData = [
        'date' => '2024-05-15',
        'topic' => 'CPR Training',
        'notes' => 'Annual certification renewal',
        'members' => $members->pluck('id')->toArray(),
    ];

    $response = $this->actingAs($this->user)->post(route('admin.training-sessions.store'), $sessionData);

    $response->assertRedirect();
    $this->assertDatabaseHas('training_sessions', [
        'topic' => 'CPR Training',
        'notes' => 'Annual certification renewal',
    ]);

    $trainingSession = TrainingSession::where('topic', 'CPR Training')->first();
    expect($trainingSession->members)->toHaveCount(2);
});

test('training session creation validates required fields', function (): void {
    $response = $this->actingAs($this->user)->post(route('admin.training-sessions.store'), []);

    $response->assertSessionHasErrors(['date', 'topic', 'members']);
});

test('training session creation requires at least one member', function (): void {
    $sessionData = [
        'date' => '2024-05-15',
        'topic' => 'CPR Training',
        'members' => [],
    ];

    $response = $this->actingAs($this->user)->post(route('admin.training-sessions.store'), $sessionData);

    $response->assertSessionHasErrors(['members']);
});

test('can view a training session', function (): void {
    $trainingSession = TrainingSession::factory()->create(['topic' => 'First Aid Training']);

    $response = $this->actingAs($this->user)->get(route('admin.training-sessions.show', $trainingSession));

    $response->assertOk();
    $response->assertViewIs('admin.training-sessions.show');
    $response->assertSee('First Aid Training');
});

test('can view edit training session page', function (): void {
    $trainingSession = TrainingSession::factory()->create();

    $response = $this->actingAs($this->user)->get(route('admin.training-sessions.edit', $trainingSession));

    $response->assertOk();
    $response->assertViewIs('admin.training-sessions.edit');
});

test('can update a training session', function (): void {
    $members = Member::factory()->count(2)->create();
    $trainingSession = TrainingSession::factory()->create(['topic' => 'Old Topic']);
    $trainingSession->members()->attach($members->first()->id);

    $response = $this->actingAs($this->user)->put(route('admin.training-sessions.update', $trainingSession), [
        'date' => '2024-06-20',
        'topic' => 'New Topic',
        'notes' => 'Updated notes',
        'members' => $members->pluck('id')->toArray(),
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('training_sessions', [
        'id' => $trainingSession->id,
        'topic' => 'New Topic',
        'notes' => 'Updated notes',
    ]);

    $trainingSession->refresh();
    expect($trainingSession->members)->toHaveCount(2);
});

test('can delete a training session', function (): void {
    $trainingSession = TrainingSession::factory()->create();

    $response = $this->actingAs($this->user)->delete(route('admin.training-sessions.destroy', $trainingSession));

    $response->assertRedirect(route('admin.training-sessions.index'));
    $this->assertSoftDeleted('training_sessions', ['id' => $trainingSession->id]);
});

test('can attach members to a training session', function (): void {
    $members = Member::factory()->count(3)->create();

    $sessionData = [
        'date' => '2024-07-10',
        'topic' => 'AED Training',
        'notes' => null,
        'members' => $members->pluck('id')->toArray(),
    ];

    $response = $this->actingAs($this->user)->post(route('admin.training-sessions.store'), $sessionData);

    $response->assertRedirect();

    $trainingSession = TrainingSession::where('topic', 'AED Training')->first();
    expect($trainingSession->members)->toHaveCount(3);

    foreach ($members as $member) {
        $this->assertDatabaseHas('member_training_session', [
            'training_session_id' => $trainingSession->id,
            'member_id' => $member->id,
        ]);
    }
});
