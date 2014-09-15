<?php if (!defined('APPLICATION')) exit();
class BirthdayModule extends Gdn_Module {

	protected $Birthdays;

	public function __construct($Sender) {
		parent::__construct($Sender);
		$this->Birthdays = $Sender->Data('Birthdays', array());
	}

	public function AssetTarget() {
		return 'Panel';
	}

	public function ToString() {
		$Birthdays = $this->Birthdays;
		Gdn::UserModel()->GetIDs($Birthdays);
		$Return = '<div class="Box BirthdayModule"><h4>'.Plural(count($Birthdays), T("Today's Birthday"), T("Today's Birthdays")).'</h4><p>';
		foreach ($Birthdays as $Birthday)
			$Return .= UserPhoto(Gdn::UserModel()->GetID($Birthday), 'Medium').' ';
		$Return .= '</p></div>';
		return $Return;
	}

}
