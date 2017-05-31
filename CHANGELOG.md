# CHANGELOG

## 1.4.4

### Fixed

- Fixed handling of falsey arguments in `addClass`

## 1.4.3

### Added

- Added ability to override self-closing behavior per-tag

### Fixed

- Fixed boolean attributes not being parsed properly (was outputting `checked="false"` per example), for real this time I swear

## 1.4.2

### Fixed

- Fixed boolean attributes not being rendered properly (was outputting `checked="false"` per example)

## 1.4.1

### Fixed

- Fixed ability to replace children
- Fixed null attributes being left removed

## 1.4.0

### Added

- Added `TreeObject::prepend` and `TreeObject::append` to set childs before/after other children

### Fixed

- Fixed case where `nest` would create invalid tags from strings

## 1.3.0

### Added

- Added `Table` element

### Fixed

- Fixed a bug in the rendering of children

## 1.2.0

### Added

- Children are now rendered via their `render` method instead of toString
- Added Tag::removeAttribute

## 1.1.2

### Fixed

- Bugfixes

## 1.1.1

### Fixed

- Fixed a bug in classes removing

## 1.1.0

### Added

- Allow camelCased setting of attributes
- Allow flat fetching of children (ie. ignore dot notation)
- Added `getAttribute` method

### Fixed

- Fix a bug in numerical attributes (min, max, etc)
- Fix JSON attributes being encoded on rendering

## 1.0.0

### Added

- Initial release
