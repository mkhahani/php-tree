<?php
/**
 * phpTree
 * Builds XHTML tree structre from array of self-joined data
 *
 * @author      Mohsen Khahani <mkhahani@gmail.com>
 * @copyright   2011-2014 Mohsen Khahani
 * @license     MIT
 * @version     2.1
 * @created     November 1, 2011
 * @url         http://mohsenkhahani.ir/phpTree
 */

/**
 * Tree base class
 */
class Tree
{
    /**
     * Class version
     *
     * @var     string
     * @access  public
     */
    var $Version = '2.1';

    /**
     * Class constructor
     *
     * @access  public
     * @param   array   $data       Tree data [id, parent, text]
     * @param   mixed   $callback   Callback function to build a node
     *                              can be a function name or Array(class/object, function name)
     *                              For more info see PHP's call_user_func()
     * @return  void
     */
    function Tree($data, $callback = null)
    {
        $this->data = $data;
        $this->callback = $callback;
    }

    /**
     * Fetches children data of the givven node
     *
     * @access  private
     * @param   int  $id    Node ID
     * @return  array   Children data
     */
    function getChildren($id)
    {
        $res = array();
        foreach ($this->data as $node) {
            if ($node[1] == $id) {
                $res[] = $node;
            }
        }
        return $res;
    }

    /**
     * Builds sub tree from given nodes data
     *
     * @access  private
     * @param   array   $data   Nodes data
     * @return  string  XHTML sub tree
     */
    function buildNodes($data)
    {
        $tree = '';
        foreach ($data as $row) {
            $childNodes = '';
            $children = $this->getChildren($row[0]);
            if (count($children) > 0) {
                $childNodes = $this->buildNodes($children);
            }

            if ($this->callback) {
                $tree .= call_user_func($this->callback, $row, $childNodes);
            } else {
                $tree .= '<li><span></span><div>' . $row[2] . '</div>';
                if ($childNodes) {
                    $tree .= '<ul>' . $childNodes . '</ul>';
                }
                $tree .= '</li>';
            }
        }
        return $tree;
    }

    /**
     * Builds the tree
     *
     * @access  public
     * @return  string  XHTML tree
     */
    function get()
    {
        $rootNodes = $this->getChildren(0);
        return $this->buildNodes($rootNodes);
    }
}
