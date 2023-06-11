<?php
require_once 'dompdf/autoload.inc.php';
include 'php/core/init.php';

use Dompdf\Dompdf;

class Pdf extends Dompdf
{
    public function __construct()
    {
        parent::__construct();
    }
}

