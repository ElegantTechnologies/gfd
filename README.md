# gfd
Edit `phpunit.xml` for user/pass and temp db

try 
```
php vendor/bin/phpunit
```

Status:
=======
1/23' 
- The DB tests are generic and don't do clever class introspection
- EH Calendar planning calls `Gfd_Validations_Stm::PrevalidateCandidates_forClass` 
  and then the update via `GftSchoolDay::FetchMergeUpdate_onValidPayload`
- We days to handle validation differently, since we really don't want to make a mock.
    Too distinct from a full gfd class. We'd need to ignore the whole writing aspect. At least overriding reading and write, which I find infinitely confusing.

Next Steps
==========
1/23'
- Refactor to make easier to understand via inspection
- More tests and examples, including a non-class based example
- 
    