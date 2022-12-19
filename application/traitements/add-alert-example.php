<?php

\SAND\Object\Session::createAndTestSession();

\SAND\Object\Alert::addAlert('OK','This is an alert !','info');


header('location:'. \SAND\Classe\Url::link_rewrite(false, "index", []));