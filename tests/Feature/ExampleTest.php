<?php

it('redirects root route to login', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
