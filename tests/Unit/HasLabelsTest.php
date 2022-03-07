<?php

use App\Models\Epic;
use App\Models\Label;
use App\Models\Story;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can assign label to epic', function () {
    $epic = Epic::factory()->create();
    $label = Label::factory()->create();

    $epic->addLabel($label);
    $this->assertTrue($epic->hasLabel($label));
});

test('it can assign label using id', function () {
    $task = Task::factory()->create();
    $label1 = Label::factory()->create();
    $label2 = Label::factory()->create();

    $task->addLabel($label2->id);
    $this->assertTrue($task->hasLabel($label2->id));

    $this->assertFalse($task->hasLabel($label1->id));
});

test('it can be removed', function () {
    $story = Story::factory()->create();
    $label = Label::factory()->create();

    $story->addLabel($label);
    $this->assertTrue($story->hasLabel($label));

    $story->removeLabel($label);
    $this->assertFalse($story->hasLabel($label));
});

test('it supports adding multiple', function () {
    $epic = Epic::factory()->create();
    $label1 = Label::factory()->create();
    $label2 = Label::factory()->create();
    $label3 = Label::factory()->create();

    $epic->addLabel([$label1, $label2]);
    $this->assertTrue($epic->hasLabel([$label1, $label2]));
    $this->assertFalse($epic->hasLabel($label3));
});
