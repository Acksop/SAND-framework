<?php

\MVC\Object\Session::createAndTestSession();

\MVC\Classe\Dumper::dump($_GET);
\MVC\Classe\Dumper::dump($url_params);
\MVC\Classe\Dumper::dump($GLOBALS);
