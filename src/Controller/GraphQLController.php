<?php

namespace DieSchittigs\ContaoGraphQLBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use GraphQL\Type\Schema;
use GraphQL\GraphQL;
use GraphQL\Error\FormattedError;
use GraphQL\Error\Debug;
use DieSchittigs\ContaoGraphQLBundle\Types;
use DieSchittigs\ContaoGraphQLBundle\ObjectType\QueryType;
use Contao\CoreBundle\Framework\ContaoFramework;

/**
 * @Route("/graphql", defaults={"_scope" = "frontend", "_token_check" = false})
 */
class GraphQLController extends AbstractController
{
    public function __construct(ContaoFramework $framework)
    {
        $framework->initialize();
    }

    /**
     * @return Response
     *
     * @Route("/", name="contao_graphql")
     */
    public function handle(Request $request)
    {
        $debug = Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE;

        $payload = (object) [
            'query' => $request->get('query'),
            'variables' => $request->get('variables'),
            'operationName' => $request->get('operationName'),
        ];

        if ($request->isMethod('POST') && is_null($payload = json_decode($request->getContent()))) {
            $result['errors'] = [ FormattedError::create(json_last_error_msg()) ];
            return $this->json($result);
        }

        try {
            $schema = new Schema([
                'query' => new QueryType
            ]);

            $result = GraphQL::executeQuery(
                $schema,
                $payload->query,
                null,
                null,
                $payload->variables,
                $payload->operationName
            );
        } catch (\Exception $error) {
            $result['errors'] = [ FormattedError::createFromException($error, $debug) ];
        }

        return $this->json($result);
    }
}
