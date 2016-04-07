<?php

class MenuModel
{

    public function getFullMenu()
    {
        $menu = DBAdapter::getInstance()->query('
            SELECT p.id, p.url, p.parent_id, pm.link, pm.title
            FROM page p
            JOIN page_metadata pm ON (pm.page_id = p.id)
            WHERE pm.language_id = 1
            AND p.in_menu = 1
            ORDER BY p.position ASC
        ');

        return $this->buildMenuArray($menu);
    }

    private function buildMenuArray($menu, $parent_id = null)
    {
        // return an array of items with parent = $parentId
        $result = array();
        foreach ($menu as $item) {
            if ($item->parent_id == $parent_id) {
                $newItem = $item;
                $newItem->children = $this->buildMenuArray($menu, $newItem->id);
                $result[] = $newItem;
            }
        }

        if (count($result) > 0) {
            return $result;
        }
        return null;
    }

}
