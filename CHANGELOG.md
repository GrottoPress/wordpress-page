# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 0.4.0 - 2017-09-28
### Changed
- Changed signature of page URL method; accepts string 'full' to indicate full URL (ie with query params).
- Undo execute methods logic once for multiple calls in a page cycle.

## 0.3.0 - 2017-09-13
### Changed
- Code compliant with PSR-1, PSR-2 and PSR-4.

## 0.2.1 - 2017-09-04
### Added
- Added ability to get current page number.

### Changes
- Prevented multiple calls to database when methods called multiple times.

## 0.2.0 - 2017-08-13
### Changed
- Requires PHP version 7.0 and newer
- Added ability to get current page URL

## 0.1.1 - 2017-08-10
### Changed
- Fixed typos in readme.

## 0.1.0 - 2017-08-10
### Added
- `Page` class
- Set up test suite with [PHPUnit](https://phpunit.de)
