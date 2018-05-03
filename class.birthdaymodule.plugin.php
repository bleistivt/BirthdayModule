<?php

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
