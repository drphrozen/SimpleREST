<?php
class RestInfo {
    public static $mPathElements = null;
    public static $mPath = null;
    public static $mData = null;

    /**
     * Returns an array of path elements, each element is a string with a length larger than 0.
     * @return array
     */
    static public function pathElements() {
        if (self::$mPathElements != null) {
            return self::getPathElements();
        }

        $tmp = explode('?', $_SERVER['REQUEST_URI'], 2);
        $pathstr = $tmp[0];
        $path = explode('/', $pathstr);
        $tmp = array();
        foreach ($path as $element) {
            if ($element != '') {
                $tmp[] = $element;
            }
        }
        self::$mPathElements = $tmp;
        return self::$mPathElements;
    }

    /**
     * Should return path with the following syntax only single slashes and no pre or post slash:
     * this/is/a/good/path
     * @return string
     */
    static public function path() {
        if (self::$mPath != null) {
            return self::$mPath;
        }
        self::$mPath = implode('/', self::getPathElements());
        return self::$mPath;
    }

    static public function data() {
        if (self::$mData == null) {
            self::$mData = file_get_contents('php://input');
        }
        return self::$mData;
    }

}

abstract class Node {
    abstract public function get();
    abstract public function put();
    abstract public function post();
    abstract public function delete();
}

class NodeFactory {
    /**
     * Determine the request method and type of node (collection or element)
     * @param ElementNode $elementNode
     * @param CollectionNode $collectionNode
     * @param callback $isCollection
     */
    static public function process($elementNode, $collectionNode, $isCollection) {
        $node = ($isCollection() ? $collectionNode : $elementNode);
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $node->get();
                break;
            case 'PUT':
                $node->put();
                break;
            case 'POST':
                $node->post();
                break;
            case 'DELETE':
                $node->delete();
                break;
        }
    }

}

abstract class ElementNode extends Node {
    /**
     * Retrieve a representation of the addressed member of the collection, expressed in an appropriate Internet media type. 
     */
    function get() {}

    /**
     * Replace the addressed member of the collection, or if it doesn't exist, create it. 
     */
    function put() {}

    /**
     * Treat the addressed member as a collection in its own right and create a new entry in it. 
     */
    function post() {}

    /**
     * Delete the addressed member of the collection. 
     */
    function delete() {}
}

abstract class CollectionNode extends Node {
    /**
     * List the URIs and perhaps other details of the collection's members. 
     */
    function get() {}

    /**
     * Replace the entire collection with another collection. 
     */
    function put() {}

    /**
     * Create a new entry in the collection. The new entry's URL is assigned automatically and is usually returned by the operation. 
     */
    function post() {}

    /**
     * Delete the entire collection. 
     */
    function delete() {}
}