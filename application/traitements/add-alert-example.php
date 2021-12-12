<?php

\MVC\Object\Session::createAndTestSession();

\MVC\Object\Alert::addAlert('OK','This is an alert !','info');


header('location:'.\MVC\Classe\Url::link_rewrite(false, "index", []));