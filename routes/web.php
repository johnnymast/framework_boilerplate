<?php

use App\Http\Controller\IndexController;

app()->get('/', [IndexController::class, "index"]);
