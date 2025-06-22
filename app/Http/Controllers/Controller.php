<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OAT;
use App\Http\Response;


#[OAT\Info(
    version:"1",
    title: "Book sale",
    description: "Простой сервис по продаже книг",
)]
abstract class Controller
{
    use Response;
}
