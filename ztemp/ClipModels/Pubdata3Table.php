<?php
/**
 * Clip
 * Generated Model Class
 *
 * @link http://code.zikula.org/clip/
 */

/**
 * Doctrine_Table class used to implement own special entity methods.
 */
class ClipModels_Pubdata3Table extends Clip_Doctrine_Table
{
    /**
     * Returns the relations as an indexed array.
     *
     * @param boolean $onlyown Retrieves owning relations only (default: false).
     * @param strung  $field   Retrieve a KeyValue array as alias => $field (default: null).
     *
     * @return array List of available relations.
     */
    static public function clipRelations($onlyown = true, $field = null)
    {
        $relations = array();

        // own relations
        

        if (!$onlyown) {
            // foreign relations
            
        }

        // return here if no relations or no specific field requested
        if (!$relations || !$field) {
            return $relations;
        }

        $v = reset($relations);
        if (!isset($v[$field])) {
            throw new Exception("Invalid field [$field] requested for the property [$key] on ".get_class()."::getRelations");
        }

        $result = array();
        foreach ($relations as $k => $v) {
            $result[$k] = $v[$field];
        }

        return $result;
    }
}
