<?php
namespace Jeffdev\FMApi;

require_once dirname(__FILE__) . '/../Error/Validation.php';
require_once dirname(__FILE__) . '/../Result.php';

class FileMaker_Command_Implementation
{
    var $_fm;
    var $_layout;
    var $result_layout;
    var $_script;
    var $_scriptParams;
    var $_preReqScript;
    var $_preReqScriptParams;
    var $_preSortScript;
    var $_preSortScriptParams;
    var $_recordClass;
    var $_recordId;

    public function __construct($fm_obj, $layout_name) {
        $this->_fm =& $fm_obj;
        $this->_layout = $layout_name;
        $this->_recordClass = $fm_obj->getProperty('recordClass');
    }

    function setResultLayout($layout_name)
    {
        $this->_result_layout = $layout_name;
    }

    function setScript($script_name, $script_param = null)
    {
        $this->_script = $script_name;
        $this->_scriptParams = $script_param;
    }

    function setPreCommandScript($script_name, $script_param = null)
    {
        $this->_preReqScript = $script_name;
        $this->_preReqScriptParams = $script_param;
    }

    function setPreSortScript($script_name, $script_param = null)
    {
        $this->_preSortScript = $script_name;
        $this->_preSortScriptParams = $script_param;
    }

    function setRecordClass($recordclass)
    {
        $this->_recordClass = $recordclass;
    }

    function setRecordId($record_id)
    {
        $this->_recordId = $record_id;
    }

    function validate($field_name = null)
    {

        if (!is_a($this, 'FileMaker_Command_Add_Implementation') && !is_a($this, 'FileMaker_Command_Edit_Implementation'))
        {
            return true;
        }

        $layout = & $this->_fm->getLayout($this->_layout);
        if (FileMaker :: isError($layout))
        {
            return $layout;
        }

        $errval = new FileMaker_Error_Validation($this->_fm);
        if ($field_name === null)
        {

            foreach ($layout->getFields() as $field_name => $V06e3d36f)
            {
                if (!isset ($this->_fields[$field_name]) || !count($this->_fields[$field_name]))
                {

                    $result_fields = array (
                        0 => null
                        );
                }
                else
                {

                    $result_fields = $this->_fields[$field_name];
                }

                $val_result = $errval;
                foreach ($result_fields as $result_field)
                {

                    $val_result = $val_result->validate($result_field, $val_result);
                }
            }
        }
        else
        {

            $field = & $layout->getField($field_name);
            if (FileMaker :: isError($field))
            {

                return $field;
            }

            if (!isset ($this->_fields[$field_name]) || !count($this->_fields[$field_name]))
            {

                $result_fields = array (
                    0 => null
                );
            }
            else
            {

                $result_fields = $this->_fields[$field_name];
            }

            $val_result = $errval;
            foreach ($result_fields as $result_field)
            {

                $val_result = $val_result->validate($result_field, $val_result);
            }
        }

        return $val_result->numErrors() ? $val_result : true;
    }

    function & _getResult($resultObj)
    {
        $resultSet = new FileMaker_Parser_FMResultSet($this->_fm);
        $parsed_result = $resultSet->parse($resultObj);
        if (FileMaker :: isError($parsed_result))
        {
            return $parsed_result;
        }

        $result = new FileMaker_Result($this->_fm);
        $parsedparedresult = $parsed_result->setResult($result, $this->V0b9a204c);

        if (FileMaker :: isError($parsedparedresult)) 
        {
            return $parsedparedresult;
        }
        
        return $parsedparedresult;
    }

    function _getCommandParams() 
    {
        
        $out_parameters = array
        (
            '-db' => $this->_fm->getProperty('database'),
            '-lay' => $this->_layout
        );

        $params = array (
            '_script' => '-script',
            '_preReqScript' => '-script.prefind',
            '_preSortScript' => '-script.presort'
        );

        foreach ($params as $key => $value)
        {

            if ($this->$key)
            {

                $out_parameters[$value] = $this->$key;
                $key .= 'Params';
                if ($this-> $key)
                {

                    $V21ffce5b[$value . '.param'] = $this->$key;
                }
            }
        }

        if ($this->result_layout)
        {

            $out_parameters['-lay.response'] = $this->result_layout;
        }

        return $out_parameters;
    }
}