<?php
require_once 'SimpleREST.php';

class Element extends ElementNode {
    function delete() { echo "delete element"; }
    function get()    { echo "get element"; }
    function post()   { echo "post element"; }
    function put()    { echo "put element"; }
}

class Collection extends CollectionNode {
    function delete() { echo "delete collection"; }
    function get()    { echo "get collection"; }
    function post()   { echo "post collection"; }
    function put()    { echo "put collection"; }
}

NodeFactory::process(new Element(), new Collection(), function() {
    // All top level elements are collections
    return sizeof(RestInfo::pathElements()) == 1;
});
