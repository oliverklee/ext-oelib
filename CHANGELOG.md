# Change log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](https://semver.org/).

## x.y.z

### Added
- Composer script for PHP linting (#4)
- add TravisCI builds

### Changed
- move the extension to GitHub

### Deprecated

### Removed
- remove obsolete TypoScript files (#8)

### Fixed
- require static_info_tables for dev (#14)
- skip tests that require static_info_tables if the extension is not installed (#11, #12, #13)
- fix autoloading when running the tests in the BE module in non-composer mode (#9)
- fix the "replace" section in the composer.json of the test extensions
- provide null page cache in the testing framework
- test failure about the framework hook in 8.7
- Db::enableFields should be able to find expired records

## 1.3.0

The [change log up to version 1.3.0](Documentation/changelog-archive.txt)
has been archived.
