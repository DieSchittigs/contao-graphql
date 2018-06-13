<?php
namespace GraphQL\Examples\Blog;

/**
 * Class AppContext
 * Instance available in all GraphQL resolvers as 3rd argument
 *
 * @package GraphQL\Examples\Blog
 */
class AppContext
{
    public $rootUrl;
    public $viewer;
    public $request;
}