<?php if (!defined('APPLICATION')) exit();

$PluginInfo['BirthdayModule'] = array(
	'Name' => 'Birthday Module',
	'Description' => 'Adds a module showing today\'s birthdays to the Panel. Requires ProfileExtender with the "Birthday" field enabled.',
	'Version' => '0.1',
	'Author' => 'Bleistivt',
	'AuthorUrl' => 'http://bleistivt.net'
);

class BirthdayModulePlugin extends Gdn_Plugin {

	public function Base_Render_Before($Sender) {
		$Birthdays = $this->GetBirthdays();
		if (empty($Birthdays) || $Sender->MasterView == 'admin')
			return;
		$Sender->SetData('Birthdays', $Birthdays);
		$BirthdayModule = new BirthdayModule($Sender);
		$Sender->AddModule($BirthdayModule);
	}

	protected function GetBirthdays() {
		$Birthdays = json_decode(Gdn::Get('BirthdayModule.Birthdays'));
		if ($Birthdays && $Birthdays[0] == date('y-m-d'))
			return $Birthdays[1];
		$Px = Gdn::Database()->DatabasePrefix;
		$Birthdays = Gdn::SQL()
			->Query("SELECT UserID FROM {$Px}User WHERE DATE_FORMAT(DateOfBirth, '%m-%d') = '".date('m-d')."'")
			->Result(DATASET_TYPE_ARRAY);
		$Birthdays = ConsolidateArrayValuesByKey($Birthdays, 'UserID');
		$Birthdays = array_diff($Birthdays, array(Gdn::UserModel()->GetSystemUserID()));
		Gdn::Set('BirthdayModule.Birthdays', json_encode(array(date('y-m-d'), $Birthdays)));
		return $Birthdays;
	}

	public function OnDisable() {
		Gdn::Set('BirthdayModule.Birthdays', null);
	}

}
