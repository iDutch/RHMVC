<?php

class TranslationModel extends AbstractModel
{

    public function getAll()
    {
        $translations = DBAdapter::getInstance()->query('
            SELECT t.id, t.keyword, l.`data`, l.language_id FROM translation t
            LEFT OUTER JOIN locales l ON (l.translation_id = t.id)
        ');

        $data = array();
        foreach ($translations as $translation) {
            if (isset($translation->language_id)) {
                $data[$translation->id]['keyword']                              = $translation->keyword;
                $data[$translation->id]['languages'][$translation->language_id] = $translation->data;
            } else {
                $data[$translation->id]['keyword']      = $translation->keyword;
                $data[$translation->id]['languages']    = array();
            }
        }

        return $data;
    }

    public function saveTranslations($postdata, $id = null)
    {
        //TODO: Insert in temporary table then swap tables and truncate temp table containing the old data
        DBAdapter::getInstance()->beginTransaction();
        DBAdapter::getInstance()->query('TRUNCATE TABLE locales');
        
        foreach ($postdata['translation'] as $id => $languages) {
            foreach ($languages as $language_id => $data) {
                DBAdapter::getInstance()->insert('locales', array('translation_id' => $id, 'language_id' => $language_id, 'data' => $data));
            }
        }

        DBAdapter::getInstance()->commit();
        Logger::getInstance()->log('update', 'translations', $id);

        //Generate translation file
        $languages = $this->getLanguages();
        foreach ($languages as $language) {
            $translations = DBAdapter::getInstance()->query('
                SELECT t.id, t.keyword, l.`data`, l.language_id FROM translation t
                LEFT OUTER JOIN locales l ON (l.translation_id = t.id)
                WHERE l.language_id = :language_id AND l.`data` <> \'\'
            ', array('language_id' => array('value' => $language->id, 'type' => PDO::PARAM_INT)));

            $file = fopen(__DIR__ . '/../languages/translations.' . $language->iso_code . '.php', 'w');
            fwrite($file, "<?php\n");
            fwrite($file, "return array(\n");
            foreach ($translations as $translation) {
                fwrite($file, "'" . $translation->keyword . "' => '" . str_replace("'","\\'", $translation->data) . "',\n");
            }
            fwrite($file, ");\n");
            fclose($file);
        }

        return true;
    }

}

