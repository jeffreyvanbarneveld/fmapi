<?php
namespace Jeffdev\FMApi;

require_once dirname(__FILE__) . '/../CommandImpl.php';
class FileMaker_Command_FindRequest_Implementation
{
    var $_findCriteria = array();

    var $_omit;

    function __construct()
    {
        $this->_omit = false;
    }

    function addFindCriterion($field_name, $value)
    {
        $this->_findCriteria[$field_name] = $value;
    }

    function setOmit($omit)
    {
        $this->_omit = $omit;
    }

    function clearFindCriteria()
    {
        $this->_findCriteria = array();
    }

}
