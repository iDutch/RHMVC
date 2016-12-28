<?php

namespace Application\Models;

use System\Core\RHMVC\AbstractModel;

class Category extends AbstractModel
{

    static $table_name = 'categories';
    static $has_many = [
            ['articles'],
            ['category_contents', 'conditions' => ['language_id = ?', 1]]
    ];

    public function saveThroughTransaction($params)
    {
        $conn = $this->connection();
        $conn->transaction();

        if (isset($this->id)) {
            $languages = CategoryContent::find('all', ['conditions' => ['category_id = ?', $this->id]]);
            foreach ($languages as $CategoryContent) {
                $CategoryContent->delete();
            }
            $CategoryContent = null;
        }

        $this->is_online = false;
        $this->is_enabled = false;

        foreach ($params as $key => $value) {
            if ($key == 'is_enabled' || $key == 'is_online') {
                $this->{$key} = !empty($value) ? $value : null;
            }
            if ($key == 'language') {
                foreach ($value as $language_id => $lang_data) {
                    $CategoryContent[$language_id] = new CategoryContent();
                    foreach ($lang_data as $lang_key => $lang_value) {
                        if ($lang_key == 'name') {
                            $CategoryContent[$language_id]->{$lang_key} = $lang_value;
                        }
                    }
                    $CategoryContent[$language_id]->language_id = $language_id;
                }
            }
        }

        $C = $this->save();

        foreach ($CategoryContent as $language_id => $value) {
            $CategoryContent[$language_id]->category_id = $this->id;
            $CC[$language_id] = $CategoryContent[$language_id]->save();
        }

        if (!$C || in_array(false, $CC)) {
            $conn->rollback();
            if (count($this->errors->get_raw_errors())) {
                foreach ($this->errors->get_raw_errors() as $field => $message) {
                    $this->flashmessages->error($message);
                }
            }
            foreach ($CategoryContent as $language_id => $CC) {
                if (count($CC->errors->get_raw_errors())) {
                    foreach ($CC->errors->get_raw_errors() as $field => $message) {
                        $this->messages->error($field.$language_id, $message);
                    }
                }
            }
            return false;
        } else {
            $conn->commit();
            return true;
        }
    }

    public function getFormData()
    {
        $data = [];
        foreach ($this->to_array() as $key => $value) {
            $data[$key] = $value;
        }
        $languages = CategoryContent::find('all', ['conditions' => ['category_id = ?', $this->id]]);
        foreach ($languages as $language) {
            foreach ($language->to_array() as $key => $value) {
                $data['language'][$language->language_id][$key] = $value;
            }
        }
        return $data;
    }

}
