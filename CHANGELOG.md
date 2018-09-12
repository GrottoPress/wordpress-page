# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Unreleased

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
