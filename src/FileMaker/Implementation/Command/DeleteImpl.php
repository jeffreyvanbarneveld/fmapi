<?php
namespace Jeffdev\FMApi;

require_once dirname(__FILE__) . '/../CommandImpl.php';

class FileMaker_Command_Delete_Implementation extends FileMaker_Command_Implementation
{
   function __construct($fm_obj, $layout, $recID)
   {
     FileMaker_Command_Implementation::__construct($fm_obj, $layout);
     $this->_recordId = $recID;
   }

   function &execute()
   {
       if (empty($this->_recordId))
       {
           $Vcb5e100e = new FileMaker_Error($this->_fm, 'Delete commands require a record id.');
           return $Vcb5e100e;
       }
       
       $params = $this->_getCommandParams();
       $params['-delete'] = true;
       $params['-recid'] = $this->_recordId;
       $result = $this->_fm->_execute($params);

       if (FileMaker::isError($result))
       {
           return $result;
       }

       return $this->_getResult($result);
   }
}
