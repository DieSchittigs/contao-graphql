<?php

namespace DieSchittigs\ContaoGraphQLBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;
use \GraphQL\Error\FormattedError;
use \GraphQL\Error\Debug;
use DieSchittigs\ContaoGraphQLBundle\Types;
use DieSchittigs\ContaoGraphQLBundle\Type\QueryType;

/**
 * @Route("/graphql", defaults={"_scope" = "frontend", "_token_check" = false})
 */
class GraphQLController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/", name="contao_graphql")
     */
    public function handle(Request $request)
    {
        $this->container->get('contao.framework')->initialize();

        try {
            $payload = json_decode($request->getContent());

            $schema = new Schema([ 'query' => new QueryType ]);
            $result = GraphQL::executeQuery($schema, $payload->query);
        } catch (\Exception $error) {
            $debug = Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE;
            $result['errors'] = [ FormattedError::createFromException($error, $debug) ];
        }

        return $this->json($result);
    }
}
