<?php

declare(strict_types=1);

namespace modules\Tickets;

class Tickets
{
  public function index(string $id = null)
  {
    view('index', [
      'tickets' => [
        ['val' => 'ticket 01'],
        ['val' => 'ticket 03'],
        ['val' => 'ticket 05'],
        ['val' => 'ticket 06'],
      ],
    ]);
  }

  public function trips()
  {

    $trips = ['trips' => [
      ['name' => 'trip 01'],
      ['name' => 'trip 02'],
      ['name' => 'trip 03'],
    ]];

    jsonResponse($trips);
  }
}
