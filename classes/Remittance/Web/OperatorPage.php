<?php
/**
 * Created by PhpStorm.
 * User: ktokt
 * Date: 06.04.2017
 * Time: 19:33
 */

namespace Remittance\Web;


use Remittance\DataAccess\Entity\TransferRecord;
use Remittance\DataAccess\Search\TransferSearch;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\PhpRenderer;


class OperatorPage
{

    const ACTION_ACCOMPLISH = 'accomplish';
    const ACTION_ANNUL = 'annul';
    const ID = 'id';

    private $router;
    private $viewer;

    public function __construct(Router $router, PhpRenderer $viewer)
    {
        $this->router = $router;
        $this->viewer = $viewer;
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
        $response = $this->viewer->render($response, "operator/operator.php", [
            'transfers' => $transfers,
            'offset' => $offset,
            'limit' => $limit,
            'actionLinks' => $actionLinks,
        ]);

        return $response;

    }

}
