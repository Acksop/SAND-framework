<?php

\SAND\Object\Session::createAndTestSession();

\SAND\Classe\Dumper::dump($_GET);
\SAND\Classe\Dumper::dump($url_params);
\SAND\Classe\Dumper::dump($GLOBALS);
