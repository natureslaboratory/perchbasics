# Change Log
All notable changes to Perch Shop.

## [1.2.6] - 2018-03-27
### Added
- Minor compatability updates for Perch/Runway 3.1
### Fixed
- Extra runtime checks for error conditions during checkout

## [1.2.5] - 2017-09-21
### Fixed
- Fixes some type coercion errors in newer PHP/MySQL builds that were affecting cart calculations
- Fixes link formatting issues in product edit page
### Added
- Adds lookup fields to searchable output e.g. to search products by brand name

## [1.2.4] - 2017-08-15
### Fixed
- Formatting issues on member tag page
### Added
- Details of promo code to Orders export

## [1.2.3] - 2017-07-17
### Fixed
- Fixes a bug with updating countries.

## [1.2.1] - 2017-06-05
### Fixed
- Fixes an issue with cart pricing when sale prices are implemented

## [1.2] - 2017-05-04
### Added
- Added fixed sales periods as part of Promotions
- Added "use sale price" promotion type
- Added country management
- Adds API events to all shop entity types

### Fixed
- Fixes bug with order items not being processed in emails when triggered from control panel
- Adds missing `perch_shop.emails.edit` privilege
- Resets environment after logout
- Fixed a bug with creating a promotion using multiple shipping methods

## [1.1] - 2017-03-27
### Fixed
- Updated UI for Perch Runway 3

## [1.0.12] - 2017-01-25
### Fixed
- Makes some optimisations to generating variants to make the process faster
- Fixes bug that prevented custom fields in Customer master template from being added to a customer
- Fixes bug with deleted addresses still being listed

## [1.0.11] - 2017-01-07
### Fixed
- Fixes bug with disabling promotions (small template update)
- Fixes bug with `perch_shop_product_variants()` not returning expected results

## [1.0.10] - 2016-11-18
### Fixed
- Fixes bug where deleted brands were not filtered out of the listings
- Improves performance of product selection queries for larger sites
- More fixes for strict MySQL configurations

### Added
- Adds `perch_shop_set_tax_location()` runtime function. Takes a tax location ID
- Adds `perch_shop_delete_adddress()` runtime function. Takes an address ID


## [1.0.9] - 2016-10-09
### Fixed
- Sets more default values for stricter database server configurations
- Prices in templates can now be compared against the default currency using `perch:if`

### Added
- Adds `perch:variants` tag pair to product templates
- Adds `perch_shop_product_variants()` runtime function

## [1.0.8] - 2016-08-17
### Fixed
- Fixes problem with default values for decimal characters on new installations
- Corrects labelling on promotion trigger values
- Fixes a bug in the customer edit form when a customer changes their email address
- Fixes a bug in the customer edit form when a customer doesn't change their email address


## [1.0.7] - 2016-08-11
### Fixed 
- Fixes a bug with `perch_shop_set_discount_code()`
- Fixes a bug where inactive products were being returned in search results
- Fixes a bug where a product is incorrectly listed as having variants after all variants have been deleted
- Fixes a bug with `perch_shop_location_selected()` not working at all

###??Added
- Adds the `output` attribute to a number of different Shop field types to enable e.g. `output="slug"` on a `shop_brand` field
- Adds `perch_shop_set_cart_property()` runtime function
- Adds support for WorldPay gateway
- Adds decimal point and thousand separator character options to currencies

## [1.0.6] - 2016-06-22
### Fixed
- Fixes a bug with the installer

## [1.0.5] - 2016-06-21
### Fixed
- Fixes a bug with role privileges not being created

### Added
- Adds a 'priority' option for setting the order of shipping methods

## [1.0.4] - 2016-06-13
### Fixed
- Fixes a potential issue when buying a product that has never been edited
- Fixes a bug with Braintree orders >1000 currency units
- Fixes a bug with Braintree test card numbers with no country set
- Fixes a bug with cart tax location not being set automatically for returning customers

## [1.0.3] - 2016-06-04
### Fixed
- Brands are now correctly indexed against a product to enable listing products by brand
- Fixes an install bug with creating countries

## [1.0.2] - 2016-05-13
### Fixed
- Fixed a bug with duplicate customer addresses being listed
- Fixed a bug with cart properties registering as set if passed an empty string
- Fixed a couple of typos in country names
- Fixed some hard-coded database prefixes in variant generation that should have been using `PERCH_DB_PREFIX`

### Added
- Added Vatican City and Saint Kitts and Nevis to the countries list
- Added `total_items_discounted_with_tax` cart value

## [1.0.1] - 2016-04-24
### Fixed
- Fixed a fatal error when changing an order status to dispatched
- Fixed a bug with tax evidence not being logged due to change in order status events
- Fixed spelling of 'persistent'.

## [1.0] - 2016-04-11
- Initial public release