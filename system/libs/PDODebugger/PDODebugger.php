<?php

namespace libs\PDODebugger;

class PDODebugger
{
    
    public static function getQuery($sql, array $params = [], array $data = [])
    {
        $params = array_merge($data, $params);
        foreach ($params as $field => $value) {
            if(is_array($value)) {
                foreach ($value as $f => $v) {
                    $sql = str_replace(':p_' . $field . $f, $v, $sql);
                }
            } else {
                $sql = str_replace(':' . $field, $value, $sql);
            }
        }
        
        return $sql;
    }
    
}
