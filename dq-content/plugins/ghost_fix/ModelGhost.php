<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    /**
     * Model database for Ghostbuster Plugin
     *
     * @package OSClass
     * @subpackage Model
     * @1.0
     */
    class ModelGhost extends DAO
    {
        /**
         * It references to self object: ModelGhost.
         * It is used as a singleton
         *
         * @access private
         * @1.0
         * @var ModelGhost
         */
        private static $instance ;

        /**
         * It creates a new ModelGhost object class if it has been created
         * before, it return the previous object
         *
         * @access public
         * @1.0
         * @return ModelGhost
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Construct
         */
        function __construct()
        {
            parent::__construct();
        }

        public function ghostCheck($from, $where = 'fk_i_item_id') {
          	$result = $this->dao->query("SELECT " . $where . " FROM " . DB_TABLE_PREFIX . $from . " WHERE " . $where . " NOT IN (SELECT fk_i_item_id FROM " . DB_TABLE_PREFIX . "t_item_description)");

            if ($result != null) {
            	return $result->result();
            } else {
            	return null;
            }
        }

        /**
         * get resources for the item
         *
         * @return array
         */
        public function getResource($id)
        {
			$this->dao->select();
            $this->dao->from(DB_TABLE_PREFIX . 't_item_resource');
            $this->dao->where('fk_i_item_id', $id);

            $result = $this->dao->get();
			return $result->row();
		}

        public function ghostDelete($table, $id, $where = 'fk_i_item_id'){
        		if($this->dao->delete(DB_TABLE_PREFIX . $table, array($where => $id)) ) {
					return true;
				} else {
					return false;
				}
        }

    } ?>
