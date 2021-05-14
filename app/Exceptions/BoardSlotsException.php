<?php
namespace App\Exceptions;

use Exception;

class BoardSlotsException extends Exception
{
    public function render()
    {
        return response('Slot items cannot fit into rows and columns', 422);
    }
}
