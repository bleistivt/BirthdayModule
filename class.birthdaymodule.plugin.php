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
        if ($Sender->MasterView == 'admin') {
            return;
        }
        $BirthdayModule = new BirthdayModule();
        $Sender->AddModule($BirthdayModule);
    }

    public function OnDisable() {
        Gdn::Set('BirthdayModule.Birthdays', null);
    }

}
