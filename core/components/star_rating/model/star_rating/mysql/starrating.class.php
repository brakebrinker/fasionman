<?php
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/starrating.class.php');
class starRating_mysql extends starRating {}