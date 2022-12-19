<?php
\SAND\Object\Alert::addAlert('NOT OK!','You should not go to the admin access.','warning');
\SAND\Object\Session::checkACL_admin();