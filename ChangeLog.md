Match DSL ChangeLog
===================

## ?.?.? / ????-??-??

* Added new class, `util.match.KeyOf`. It works only with strings and
  integers but has higher performance than the `ValueOf` class.
  (@thekid) 
* Improved performance by applying the following refactorings:
  . Rewriting __invoke to direct calls
  . Rewriting array storing conditionals to class
  . Rewriting closures to classes
  (@thekid) 
* Initial implementation - @thekid
