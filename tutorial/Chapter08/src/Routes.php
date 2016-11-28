<?php

return [
    ['GET', '/page/retrieve/{id}', ['Enigma\Controllers\PageController', 'retrieve']],
    ['GET', '/page/find/{slug}', ['Enigma\Controllers\PageController', 'findBySlug']],
    ['GET', '/page/delete/{id}', ['Enigma\Controllers\PageController', 'delete']],
    [['GET','POST'], '/page/add', ['Enigma\Controllers\PageController', 'create']],
    [['GET','POST'], '/page/edit/{id}', ['Enigma\Controllers\PageController', 'update']],
    ['GET', '/page/browse', ['Enigma\Controllers\PageController', 'list']],
    ['GET', '/', ['Enigma\Controllers\HomeController', 'home']],
];
