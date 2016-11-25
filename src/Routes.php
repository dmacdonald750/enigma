<?php

return [
    ['GET', '/article/retrieve/{id}', ['Enigma\Controllers\PageController', 'retrieve']],
    ['GET', '/article/find/{slug}', ['Enigma\Controllers\PageController', 'findBySlug']],
    ['GET', '/article/edit/{id}', ['Enigma\Controllers\PageController', 'update']],
    ['GET', '/article/delete/{id}', ['Enigma\Controllers\PageController', 'delete']],
    ['GET', '/article/new', ['Enigma\Controllers\PageController', 'create']],
    ['GET', '/article', ['Enigma\Controllers\PageController', 'browse']],
    ['GET', '/page/{slug}', ['Enigma\Controllers\MarkFileController', 'retrieve']],
    ['GET', '/', ['Enigma\Controllers\FileController', 'home']],
    ['GET', '/twpage', ['Enigma\Controllers\FileController', 'retrieve']],

];
