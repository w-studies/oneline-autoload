<?php

use Core\View;

/**
 * examples:
 * ```php
 * view(); // currentModule/views/index.php
 * view('add'); // currentModule/views/add.php
 * view('users/show'); // Modules/Users/views/show.php
 * view('dashboard/graph/linear'); // Modules/Dashboard/views/graph/linear.php
 * ```
 */
function view($view = 'index', array $viewReceivedData = [], $mergeData = [])
{
  View::render($view, $viewReceivedData);
}

function currency($value)
{
  $formatter = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);

  return  $formatter->formatCurrency($value, 'BRL');

}
