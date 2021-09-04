<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Desafio backend capyba",
 *      description="Documentação da api desenvolvida para desafio backend para capyba - Gabriel Lima"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Para conseguir esse token, logue com email e senha com uma conta com email verificado",
 *     name="Bearer token",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Para conseguir esse token, crie uma conta, ou envie novamente um codigo para email de uma conta já cadastrada, que esse token virá na resposta da requisição",
 *     name="Bearer token",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="codeAuth",
 * )
 */
class Controller extends BaseController
{
    //
}
