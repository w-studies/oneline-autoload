<?php

/**
 * Sends a JSON response with optional data and HTTP status code.
 *
 * @param mixed $data The data to be included in the response. Defaults to null.
 * @param int $code The HTTP status code to be set. Defaults to 200.
 *
 * @throws void
 */
function jsonResponse(mixed $data = null, int $code = 200): void
{
  // clear the old headers
  header_remove();
  // set the actual code
  http_response_code($code);

  // treat this as json
  header('Content-Type: application/json');

  $status = [
    200 => '200 OK',
    201 => '201 Created',
    400 => '400 Bad Request',
    404 => '404 Not Found',
    422 => 'Unprocessable Entity',
    500 => '500 Internal Server Error',
  ];

  // ok, validation error, or failure
  header('Status: ' . $status[$code]);

  if ($code !== 200) {
    // guarda os dados do backtrace
    $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];

    if (is_string($data)) {
      $data .= "<small class='text-secondary'><b>FILE</b>: $debug[file], <b>LINE</b>: $debug[line]<small>";
    } elseif(is_object($data)) {
      $data->debug = $debug;
    } elseif(is_array($data)) {
      $data['debug'] = $debug;
    }
  }
  // return the encoded json
  exit(is_array($data) || is_object($data) ? json_encode($data) : '"' . $data . '"');
}
