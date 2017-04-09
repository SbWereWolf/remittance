<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 06.04.2017
 * Time: 19:33
 */

namespace Remittance\Web;


use Remittance\Core\Common;
use Remittance\DataAccess\Entity\TransferRecord;
use Remittance\DataAccess\Search\TransferSearch;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;


class OperatorPage
{

    const ACTION_ACCOMPLISH = 'accomplish';
    const ACTION_ANNUL = 'annul';
    const ID = 'id';

    private $container;
    private $router;

    public function __construct(Container $container, Router $router)
    {
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $arguments
     * @return Response
     */
    public function root(Request $request, Response $response, array $arguments)
    {

        $searcher = new TransferSearch();
        $transfers = $searcher->search();

        $isSet = isset($transfers);
        $isArray = false;
        $isContain = false;
        if ($isSet) {
            $isArray = is_array($transfers);
            $isContain = count($transfers) > 0;
        }

        $isValid = $isArray && $isContain;
        $actionLinks = array();
        if ($isValid) {

            foreach ($transfers as $transfer) {
                $isObject = $transfer instanceof TransferRecord;
                if ($isObject) {

                    $accomplishLink = $this->router->pathFor(
                        self::ACTION_ACCOMPLISH,
                        [self::ID => $transfer->id]);
                    $annulLink = $this->router->pathFor(
                        self::ACTION_ANNUL,
                        [self::ID => $transfer->id]);

                    $actionLinks[$transfer->id][self::ACTION_ACCOMPLISH] = $accomplishLink;
                    $actionLinks[$transfer->id][self::ACTION_ANNUL] = $annulLink;
                }
            }

        }

        $offset = 0;
        $limit = 0;
        $response = $this->container->view->render($response, "operator/operator.php", [
            'transfers' => $transfers,
            'offset' => $offset,
            'limit' => $limit,
            'actionLinks' => $actionLinks,
        ]);

        return $response;

    }

    public function accomplish(Request $request, Response $response, array $arguments)
    {

        $id = Common::setIfExists(self::ID, $arguments, Common::EMPTY_VALUE);
        $response = $response->withJson(
            array('message' => "success accomplish $id")
        );

        return $response;
    }

    public function annul(Request $request, Response $response, array $arguments)
    {

        $id = Common::setIfExists(self::ID, $arguments, Common::EMPTY_VALUE);
        $response = $response->withJson(
            array('message' => "success annul $id")
        );

        return $response;

    }

}
