<?php /* GROUPS $Id: groups.class.php,v 0.1 2004/02/03 12:07:45 michaelfinger Exp $ */
/**
 *	@package dotProject
 *	@subpackage modules
 *	@version $Revision: 1.7 $
*/

require_once( $AppUI->getSystemClass ('dp' ) );

/**
 *	Companies Class
 *	@todo Move the 'address' fields to a generic table
 */
class CGroup extends CDpObject {
/** @var int Primary Key */
	var $group_id = NULL;
/** @var string */
	var $group_name = NULL;

/** @var int */
	var $group_owner = NULL;
/** @var string */
	var $group_description = NULL;
/** @var string */
	var $group_company = NULL;

	function CGroup() {
		$this->CDpObject( 'groups', 'group_id' );
	}

	function updateGroupsContacts( $cslist ) {
	// delete all current entries
		$sql = "DELETE FROM groups_contacts WHERE group_id = $this->group_id";
		db_exec( $sql );

	// process dependencies
		if(isset($cslist)) {
		  foreach ($cslist as $contact_id) {
		    if (intval( $contact_id ) > 0) {
		      $sql = "INSERT into groups_contacts (group_id, contact_id) VALUES ($this->group_id, $contact_id)";
		      db_exec($sql);
		    }
		  }
		}
	}


// overload check
	function check() {
		if ($this->group_id === NULL) {
			return 'group id is NULL';
		}
		$this->group_id = intval( $this->group_id );

		return NULL; // object is ok
	}

// overload canDelete
	function canDelete( &$msg, $oid=null ) {
		$tables[] = array( 'label' => 'Group Contacts', 'name' => 'groups_contacts', 'idfield' => 'contact_id', 'joinfield' => 'group_id' );
	// call the parent class method to assign the oid
		return CDpObject::canDelete( $msg, $oid, $tables );
	}
}
?>