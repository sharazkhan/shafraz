<?php
/**
 * Clip
 * Generated Model Class
 *
 * @link http://code.zikula.org/clip/
 */

/**
 * This is the model class that define the entity structure and behaviours.
 */
class ClipModels_Pubdata3 extends Clip_Doctrine_Pubdata
{
    /**
     * Set table definition.
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $this->setTableName('clip_pubdata3');

        $this->hasColumn('pid as core_pid', 'integer', 4, array(
              'notnull' => true
        ));

        $this->hasColumn('id as id', 'integer', 4, array(
              'autoincrement' => true,
              'primary' => true
        ));

        $this->hasColumn('field9 as HeadLine', 'string', 255);

        $this->hasColumn('field10 as Teaser', 'string', 65535);

        $this->hasColumn('field11 as Text', 'string', 65535);

        $this->hasColumn('urltitle as core_urltitle', 'string', 255, array(
              'notnull' => true
        ));

        $this->hasColumn('author as core_author', 'integer', 4, array(
              'notnull' => true
        ));

        $this->hasColumn('hits as core_hitcount', 'integer', 8, array(
              'default' => '0'
        ));

        $this->hasColumn('language as core_language', 'string', 10);

        $this->hasColumn('revision as core_revision', 'integer', 4, array(
              'notnull' => true,
              'default' => '1'
        ));

        $this->hasColumn('online as core_online', 'boolean', null, array(
              'default' => '0'
        ));

        $this->hasColumn('intrash as core_intrash', 'boolean', null, array(
              'default' => '0'
        ));

        $this->hasColumn('visible as core_visible', 'boolean', null, array(
              'default' => '1'
        ));

        $this->hasColumn('locked as core_locked', 'boolean', null, array(
              'default' => '0'
        ));

        $this->hasColumn('publishdate as core_publishdate', 'timestamp');

        $this->hasColumn('expiredate as core_expiredate', 'timestamp');

        $this->index('urltitle_index', array(
                'fields' => array('urltitle')
            )
        );
    }

    /**
     * Record setup.
     *
     * @return void
     */
    public function setUp()
    {
        $this->actAs('Zikula_Doctrine_Template_StandardFields');
        
    }

    /**
     * Returns the relations as an indexed array.
     *
     * @param boolean $onlyown Retrieves owning relations only (default: false).
     * @param strung  $field   Retrieve a KeyValue array as alias => $field (default: null).
     *
     * @return array List of available relations.
     */
    public function getRelations($onlyown = true, $field = null)
    {
        return call_user_func_array(array('ClipModels_Pubdata3Table', 'clipRelations'), array($onlyown, $field));
    }

    /**
     * Utility methods to assign Clip Values
     */
    public function assignDefaultValues($overwrite = false)
    {
        $this->assignClipValues($this);

        parent::assignDefaultValues($overwrite);
    }

    public function assignClipValues(&$obj)
    {
        if (is_object($obj)) {
            $obj->clip_state = false;
            $obj->mapValue('core_tid',        3);
            $obj->mapValue('core_titlefield', 'HeadLine');
            $obj->mapValue('core_title',      $obj->_get($obj->core_titlefield, false) ? $obj[$obj->core_titlefield] : '');
            $obj->mapValue('core_uniqueid',   $obj->_get('core_pid', false) ? $obj->core_tid.'-'.$obj->core_pid : null);
            $obj->mapValue('core_creator',    $obj->_get('core_author', false) ? ($obj->core_author == UserUtil::getVar('uid') ? true : false) : null);
        } else {
            $obj['core_tid']        = 3;
            $obj['core_titlefield'] = 'HeadLine';
            $obj['core_title']      = isset($obj[$obj['core_titlefield']]) ? $obj[$obj['core_titlefield']] : '';
            $obj['core_uniqueid']   = isset($obj['core_pid']) && $obj['core_pid'] ? $obj['core_tid'].'-'.$obj['core_pid'] : null;
            $obj['core_creator']    = isset($obj['core_author']) ? ($obj['core_author'] == UserUtil::getVar('uid') ? true : false) : null;
        }

        return $obj;
    }

    /**
     * Hydration hook.
     *
     * @return void
     */
    public function postHydrate($event)
    {
        $event->data = $this->assignClipValues($event->data);
    }
}
