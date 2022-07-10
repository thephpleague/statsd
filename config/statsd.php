<?php

return [
    'scheme' => env('STATSD_SCHEME', 'udp'),

    'host' => env('STATSD_HOST', '127.0.0.1'),

	'port' => env('STATSD_PORT', 8125),

	'namespace' => env('STATSD_NAMESPACE', ''),

	'throwConnectionExceptions' => true
];
