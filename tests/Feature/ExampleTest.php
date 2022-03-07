<?php

it('has a home page', function () {
    $this->get('/')->assertStatus(200);
});
