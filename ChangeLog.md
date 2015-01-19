Match DSL ChangeLog
===================

## ?.?.? / ????-??-??

## 0.1.0 / 2015-01-19

* Improved performance of type lookups by adding special case handling
  for primitives to the `util.match.TypeOf` class.
  (@thekid) 
* Added new class, `util.match.KeyOf`. It works only with strings and
  integers but has higher performance than the `ValueOf` class.
  (@thekid) 
* Improved performance by applying the following refactorings:
  . Rewriting __invoke to direct calls
  . Rewriting array storing conditionals to class
  . Rewriting closures to classes
  (@thekid) 
* Initial implementation - @thekid
