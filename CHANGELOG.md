# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2023-05-31

### Added
- First stable release

## [0.8.5] - 2023-05-12

### Added
- Add support for privacy policy page

## [0.8.4] - 2023-04-13

### Added
- Add support for PHP 8

### Changed
- Replace Travis CI with GitHub actions

## [0.8.3] - 2020-02-08

### Added
- Add support for PHP 7.4

### Fixed
- Fix deprecation notice in codeception

## 0.8.2 - 2019-04-17

### Added
- Add `.gitattributes`

## 0.8.1 - 2019-04-16

### Added
- Add PHP 7.3 to travis-ci build matrix

## 0.8.0 - 2018-10-06

### Changed
- Rename `LICENSE.md` TO `LICENSE`
- Move `lang/` and `lib/` under a new `src/` directory

## 0.7.1 - 2018-09-12

### Changed
- Update translations template

## 0.7.0 - 2018-09-12

### Added
- Add `.editorconfig`
- Add translations template

### Changed
- Rename `src/` directory to `lib/`

## 0.6.0 - 2018-08-21

### Changed
- Move classes one level up the filesystem for a shorter namespace

## 0.5.2 - 2018-03-07

### Changed
- Updated documentation (readme)

## 0.5.0 - 2018-03-02

### Added
- Set up [travis-ci](https://travis-ci.org/GrottoPress/getter)
- `.security.txt`

### Changed
- Replaced WP tests with isolated unit tests

### Removed
- Redundant doc blocks, comments.

## 0.4.3 - 2018-01-20

### Added
- Added shell script to tag and release new versions
- Added check for whether a page is custom template

## 0.4.2 - 2017-11-16

### Changed
- Using strict equality operator (`===` and `!==`) for checks

## 0.4.1 - 2017-11-10

### Fixed
- Fixed error when getting page number: `number` attribute not defined.

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
