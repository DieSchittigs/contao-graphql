# Contao GraphQL Bundle
This bundle adds a GraphQL API to your Contao installation.

**Work in progress and not ready for use.**

### Configuration
You can add your own GraphQL types or override the configuration of existing ones by specifying a `graphql` section in your application's `parameters.yml`. 

```yaml
graphql:
  # Full options
  tl_child:
    type: Child
    singular: Child
    plural: Children
    resolver: MyVendor\Namespace\ChildResolver
  
  # When using shorthand notation, the value will be used for type and singular
  # There will be no pluralized version of this table
  tl_child: Child
  
  # If type is omitted, singular has to be specified and will be used instead
  tl_child:
    singular: Child
    plural: Children
```
