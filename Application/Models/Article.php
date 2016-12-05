<?php

namespace Application\Models;

use ActiveRecord\Model;
use Application\Models\ArticleContent;
use Plasticbrain\FlashMessages\FlashMessages;

class Article extends Model
{

    static $belongs_to = [
            ['category']
    ];
    static $has_many = [
            ['article_contents', 'conditions' => ['language_id = ?', 1]]
    ];
    static $validates_presence_of = [
            ['publish_date'],
            ['category_id']
    ];

    public function saveThroughTransaction($params)
    {
        $conn = $this->connection();
        $conn->transaction();

        if (isset($this->id)) {
            $languages = ArticleContent::find('all', ['conditions' => ['article_id = ?', $this->id]]);
            foreach ($languages as $ArticleContent) {
                $ArticleContent->delete();
            }
            $ArticleContent = null;
        }

        $this->allow_comments = false;
        $this->is_online = false;

        foreach ($params as $key => $value) {
            if ($key == 'publish_date' || $key == 'archive_date' || $key == 'category_id' || $key == 'allow_comments' || $key == 'is_online') {
                $this->{$key} = !empty($value) ? $value : null;
            }
            if ($key == 'language') {
                foreach ($value as $language_id => $lang_data) {
                    $ArticleContent[$language_id] = new ArticleContent();
                    $ArticleContent[$language_id]->is_online = false;
                    foreach ($lang_data as $lang_key => $lang_value) {
                        if ($lang_key == 'title' || $lang_key == 'content' || $lang_key == 'is_online') {
                            $ArticleContent[$language_id]->{$lang_key} = $lang_value;
                        }
                    }
                    $ArticleContent[$language_id]->language_id = $language_id;
                }
            }
        }

        $A = $this->save();

        foreach ($ArticleContent as $language_id => $value) {
            $ArticleContent[$language_id]->article_id = $this->id;
            $AC[$language_id] = $ArticleContent[$language_id]->save();
        }

        if (!$A || in_array(false, $AC)) {
            $conn->rollback();
            $msg = new FlashMessages();
            foreach ($this->errors->full_messages() as $k => $message) {
                $msg->error($message);
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
        $languages = ArticleContent::find('all', ['conditions' => ['article_id = ?', $this->id]]);
        foreach ($languages as $language) {
            foreach ($language->to_array() as $key => $value) {
                $data['language'][$language->language_id][$key] = $value;
            }
        }
        return $data;
    }

}
