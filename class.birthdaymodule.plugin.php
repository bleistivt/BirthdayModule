<?php

$PluginInfo['BirthdayModule'] = [
    'Name' => 'Birthday Module',
    'Description' => 'Adds a module showing today\'s birthdays to the Panel. Requires ProfileExtender with the "Birthday" field enabled.',
    'Version' => '0.2',
    'Author' => 'Bleistivt',
    'AuthorUrl' => 'http://bleistivt.net',
    'License' => 'GNU GPL2'
];

class BirthdayModulePlugin extends Gdn_Plugin {

    public function base_render_before($sender) {
        if ($sender->MasterView != 'admin') {
            $sender->addModule('BirthdayModule');
        }
    }

    public function onDisable() {
        Gdn::set('BirthdayModule.Birthdays', null);
    }

}
