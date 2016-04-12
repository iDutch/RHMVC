<?php

class PageModel extends AbstractModel
{

    public function getPage($uri)
    {
        $uri = strlen($uri) < 1 ? '/' : $uri;

        //TODO: Get language_id from session
        $page = DBAdapter::getInstance()->query('
            SELECT p.id, pm.title, pm.description, pm.keywords, l.path FROM page p
            JOIN page_metadata pm ON (pm.page_id = p.id)
            JOIN layout l ON (l.id = p.layout_id)
            WHERE language_id = :language_id
            AND p.url = :url
            AND p.is_online = 1
            LIMIT 1
        ', array(
            'language_id'   => array('value' => 1, 'type' => PDO::PARAM_INT),
            'url'           => array('value' => $uri),
        ));
        if (count($page) === 1) {
            $page_sections = DBAdapter::getInstance()->query('
                SELECT ps.controller, ps.action, ps.action_arguments, s.varname
                FROM page_section ps
                JOIN section s ON (s.id = ps.section_id)
                WHERE ps.page_id = :page_id
            ', array('page_id' => array('value' => $page[0]->id, 'type' => PDO::PARAM_INT)));
            if (count($page_sections) > 0) {
                foreach ($page_sections as $section) {
                    $page[0]->sections[$section->varname][] = $section;
                }
            }
            return $page[0];
        } else {
            return false;
        }

    }

}