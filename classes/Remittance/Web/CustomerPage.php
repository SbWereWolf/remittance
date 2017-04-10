<?php

namespace Remittance\Web;


use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;


class CustomerPage
{
    private $viewer;

    public function __construct(PhpRenderer $viewer)
    {
        $this->viewer = $viewer;
    }

    public function root(Request $request, Response $response, array $arguments)
    {

        $response = $this->viewer->render($response, "remittance/remittance.php");

        return $response;
    }

}
