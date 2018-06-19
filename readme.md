# Contao GraphQL Bundle
This bundle adds a GraphQL API to your Contao installation.

**Work in progress and not ready for use.**

### Configuration
You can add your own GraphQL types or override the configuration of existing ones by specifying a `graphql` section in your application's `parameters.yml`. 

```yaml
graphql:
  tl_child:
    singular: Child
    plural: Children
    resolver: MyVendor\Namespace\ChildResolver  # Needs to extend DieSchittigs\ContaoGraphQLBundle\Type\Resolvers\Resolver
```
