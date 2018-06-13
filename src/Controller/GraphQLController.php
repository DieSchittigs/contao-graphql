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
    public function queryAction(Request $request)
    {   
        /*
        set_error_handler(function($severity, $message, $file, $line) use (&$phpErrors) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });
        */
        $debug = Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE;
        $payload = (object) [
            'query' => null,
            'variables' => null,
            'operationName' => null
        ];
        if(!($payload->query = $request->get('query'))){
            $payload = json_decode($request->getContent());
        }
        try{
            $this->container->get('contao.framework')->initialize();
            
            $schema = new Schema([
                'query' => Types::query()
            ]);

            $result = GraphQL::executeQuery(
                $schema,
                (string) $payload->query/*,
                null,
                null,
                (array) $data['variables']*/
            );
            $output = $result->toArray($debug);
        } catch (\Exception $error) {
            $httpStatus = 500;
            $output['errors'] = [
                FormattedError::createFromException($error, $debug)
            ];
        }
        return $this->json($output);
    }

}
