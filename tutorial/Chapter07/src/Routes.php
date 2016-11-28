<?php

return [
    ['GET', '/page/{slug}', ['Enigma\Controllers\PageController', 'retrieve']],
    ['GET', '/', ['Enigma\Controllers\HomeController', 'home']],
];
