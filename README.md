# StatsD PHP Library

A simple library for working with StatsD in PHP


## Install

Via Composer

    $ composer require marcqualie/statsd

Manually

    include '/path/tolib/StatsD.php';


## Usage

### Configuring

    $statsd = new Statsd();
    $statsd->configure(array(
        'host' => '127.0.0.1',
        'port' => 8125
    ));

OR

    $statsd1 = StatsD::instance('server1')->configure(array(...));


### Counters

    $statsd->increment('web.pageview');
    $statsd->decrement('storage.remaining');
    $statsd->increment(array(
        'first.metric',
        'second.metric'
    ), 2);


### Timers

    $statsd->timing('api.response_time', 256);


### Gauges

    $statsd->gauge('api.logged_in_users', 123456);


## Contributing

- Fork the project
- Create your own feature branch
- Make your changes
- Push to your own fork
- Create pull request to origin repo
