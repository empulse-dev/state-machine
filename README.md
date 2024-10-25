
## Installation

Docker images needed:

* composer/composer
* php:cli

## Tests

Test with phpunit

```bash
  chmod +x test.sh
  ./test.sh /empulse/tests/ --testdox

  PHPUnit 11.3.3 by Sebastian Bergmann and contributors.

  Runtime:       PHP 8.3.11

  .........                                                           9 / 9 (100%)

  Time: 00:00.004, Memory: 8.00 MB

  Product Publication
  ✔ Flag and set data on product scenario
  ✔ Direct to publish product scenario
  ✔ Stay on created product scenario

  Trigger
  ✔ No map
  ✔ Map
  ✔ One transition
  ✔ To final state
  ✔ Infinite loop transition
  ✔ Stop after apply
```