<?php

class BirthdayModule extends Gdn_Module {

    public function assetTarget() {
        return 'Panel';
    }

    protected function getBirthdays() {
        $birthdays = json_decode(Gdn::get('BirthdayModule.Birthdays'));
        $token = date('y-m-d/H');

        if ($birthdays && $birthdays[0] == $token) {
            return $birthdays[1];
        }

        $date = new DateTime();
        if ($guestTimeZone = C('Garden.GuestTimeZone')) {
            try {
                $timeZone = new DateTimeZone($guestTimeZone);
                $offset = $timeZone->getOffset(new DateTime('now', new DateTimeZone('UTC')));
                $offset = floor($offset / 3600);
                $date->modify("$offset hours");
            } catch (Exception $e) {}
        }

        $birthdays = Gdn::sql()
            ->select('UserID')
            ->from('User')
            ->where("DATE_FORMAT(DateOfBirth, '%m-%d')", $date->format("'m-d'"), false, false)
            ->get()
            ->resultArray();
        $birthdays = array_column($birthdays, 'UserID');

        Gdn::set('BirthdayModule.Birthdays', json_encode([$token, $birthdays]));
        return $birthdays;
    }

    public function toString() {
        $users = Gdn::UserModel()->GetIDs($this->getBirthdays());
        if (!$users) {
            return;
        }
        $return = '<div class="Box BirthdayModule"><h4>'
            .plural(count($users), T("Today's Birthday"), T("Today's Birthdays"))
            .'</h4><p>';
        foreach ($users as $user) {
            $return .= userPhoto($user, 'Medium').' ';
        }
        $return .= '</p></div>';
        return $return;
    }

}
