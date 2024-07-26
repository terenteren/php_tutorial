<?php

(new Core\Authenticator)->logout();

header('Location: /');
exit();
