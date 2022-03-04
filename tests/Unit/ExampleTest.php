<?php

test('basic assertion', function () {
    $this->assertTrue(true);

    expect(true)->toBeTrue();
});
