<?php
namespace Jeffdev\FMApi;

require_once dirname(__FILE__) . '/../CommandImpl.php';

class FileMaker_Command_PerformScript_Implementation extends FileMaker_Command_Implementation
{

    function __construct($fm_obj, $layout, $script_name, $script_param = null)
    {
        FileMaker_Command_Implementation::__construct($fm_obj, $layout);
        $this->_script = $script_name;
        $this->_scriptParams = $script_param;
    }

    function execute()
    {
        $params = $this->_getCommandParams();
        $params['-findany'] = true;
        $result = $this->_fm->_execute($params);

        if (FileMaker::isError($result))
        {
            return $result;
        }

        return $this->_getResult($result);
    }
}
