# Change Log
All notable changes to this project will be documented in this file.
Updates should follow the [Keep a CHANGELOG](https://keepachangelog.com/) principles.

## [Unreleased][unreleased]
### Added
 - Added new `StatsDClient` interface, which `Client` now implements
 - Added new `Exception` interface, which all exceptions now implement

### Changed
 - Supported PHP versions are now 7.4, 8.0, and 8.1
 - All properties and methods now have type hints where applicable
 - The following methods return the `StatsDClient` interface instead of `Client`:
     - `ConfigurationException::getInstance()`
     - `ConnectionException::getInstance()`
 - The following `Client` methods now return `void` instead of returning `$this`:
     - `increment()`
     - `decrement()`
     - `startTiming()`
     - `endTiming()`
     - `timing()`
     - `timings()`
     - `time()`
     - `gauge()`
     - `set()`
     - `send()`
 - Renamed `Client::$instance_id` to `Client::$instanceId`

## [1.5.0] - 2018-10-09
### Added
 - Added tags supporting Datadog format (#52)

## [1.4.3] - 2017-07-17
### Added
 - Added Silex 2 support (#43)

### Changed
 - Dropped support for PHP <5.6
 - Test against PHP 7.1

## [1.4.2] - 2017-02-09
### Changed
 - Use `config` to allow publishing differently to views or assets within Laravel

## [1.4.1] - 2017-02-02
### Added
 - Added Laravel 5.4 support

## [1.4.0] - 2016-04-21
### Added
 - Custom timeout configurations
 - Exception handling is now configurable
 - Built-in Laravel 5 support

### Fixed
 - DNS lookup failures no longer raise exceptions

## [1.3.0] - 2015-06-11
### Changed
 - Throwing an exception is now optional during connections to server and can be silently ignored

## [1.2.0] - 2015-05-15
### Added
 - Configurable timeouts
 - SET method: Count the number of unique values passed to a key
 - PHP 5.6 testing on Travis
 - Various test patches and improvements

## [1.1.0] - 2014-02-01
### Added
 - PSR-4 support
 - New documentation

## [1.0.0] - 2013-08-27

This is the first fully stable version of StatsD library. This version has the following features:

 - Counters
 - Gauges
 - Timers
 - Timing Blocks
 - 100% Code Coverage
 - Silex Service Provider

[unreleased]: https://github.com/thephpleague/statsd/compare/1.5.0...master
[1.5.0]: https://github.com/thephpleague/statsd/compare/1.4.5...1.5.0
[1.4.5]: https://github.com/thephpleague/statsd/compare/1.4.4...1.4.5
[1.4.4]: https://github.com/thephpleague/statsd/compare/1.4.3...1.4.4
[1.4.3]: https://github.com/thephpleague/statsd/compare/1.4.2...1.4.3
[1.4.2]: https://github.com/thephpleague/statsd/compare/1.4.1...1.4.2
[1.4.1]: https://github.com/thephpleague/statsd/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/thephpleague/statsd/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/thephpleague/statsd/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/thephpleague/statsd/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/thephpleague/statsd/compare/v1.0...1.1.0
[1.0.0]: https://github.com/thephpleague/statsd/releases/tag/v1.0
