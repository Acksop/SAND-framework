<?php
\MVC\Object\Alert::addAlert('NOT OK!','You should not go to the admin access.','warning');
\MVC\Object\Session::checkACL_admin();