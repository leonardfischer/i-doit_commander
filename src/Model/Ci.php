<?php

namespace idoit\Module\Lfischercommander\Model;

use idoit\Model\Dao\Base;
use isys_application;
use isys_component_dao_result;

class Ci extends Base
{
    public function getObjectTypes(): isys_component_dao_result
    {
        $statusNormal = $this->convert_sql_int(C__RECORD_STATUS__NORMAL);

        $sql = "SELECT 
            isys_obj_type__id AS id,
            isys_obj_type__title AS title,
            isys_obj_type__const AS const
            FROM isys_obj_type
            WHERE isys_obj_type__status = {$statusNormal};";

        return $this->retrieve($sql);
    }

    public function findByObjectName(string $objectName): int
    {
        $objectName = $this->convert_sql_text(str_replace('*', '%', $objectName));
        $statusNormal = $this->convert_sql_int(C__RECORD_STATUS__NORMAL);

        $sql = "SELECT isys_obj__id AS id
            FROM isys_obj
            WHERE isys_obj__title LIKE {$objectName} 
            AND isys_obj__status = {$statusNormal};";

        return (int)$this->retrieve($sql)->get_row_value('id');
    }
}
