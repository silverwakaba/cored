<?php

namespace App\Helpers\Core;

class QueryHelper{
    // Exclude column from selection
    public static function exclude($data){
        // Selected by default
        $default = [
            'id',
        ];

        // Selected only if timestamp is set to true
        if(isset($data['timestamps']) && ($data['timestamps'] == true)){
            $default[] = 'created_at';
            $default[] = 'updated_at';
        }

        // Init model
        $model = new $data['model'];

        // Get model fillable attributes
        $fillable = $model->getFillable();

        // Get model hidden attributes
        $hidden = $model->getHidden();

        // Selected column by default
        $selected = array_values(array_diff(
            array_merge($default, $fillable), $hidden,
        ));

        // Select column via custom properties
        if(isset($data['exclude']) && ($data['exclude'] != null)){
            $exclude = array_values(array_diff(
                $selected, $data['exclude'],
            ));

            // Return column
            return $exclude;
        }

        // Return column
        return $selected;
    }
}






