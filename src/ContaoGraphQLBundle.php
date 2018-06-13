<?php

namespace DieSchittigs\ContaoGraphQLBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use DieSchittigs\ContaoGraphQLBundle\DependencyInjection\GraphQLExtension;

class ContaoGraphQLBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new GraphQLExtension();
    }
}
