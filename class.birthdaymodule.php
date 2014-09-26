<?php if (!defined('APPLICATION')) exit();

class BirthdayModule extends Gdn_Module {

    public function AssetTarget() {
        return 'Panel';
    }

    protected function GetBirthdays() {
        $Birthdays = json_decode(Gdn::Get('BirthdayModule.Birthdays'));
        if ($Birthdays && $Birthdays[0] == date('y-m-d/H')) {
            return $Birthdays[1];
        }
        $GuestTimeZone = C('Garden.GuestTimeZone');
        $Date = new DateTime();
        if ($GuestTimeZone) {
            try {
                $TimeZone = new DateTimeZone($GuestTimeZone);
                $HourOffset = $TimeZone->getOffset(new DateTime('now', new DateTimeZone('UTC')));
                $HourOffset = - floor($HourOffset / 3600);
                $Date->modify("$HourOffset hours");
            } catch (Exception $e) {}
        }
        $Px = Gdn::Database()->DatabasePrefix;
        $Birthdays = Gdn::SQL()
            ->Select('UserID')
            ->From('User')
            ->Where("DATE_FORMAT(DateOfBirth, '%m-%d')", $Date->format("'m-d'"), false, false)
            ->Get()
            ->Result(DATASET_TYPE_ARRAY);
        $Birthdays = ConsolidateArrayValuesByKey($Birthdays, 'UserID');
        Gdn::Set('BirthdayModule.Birthdays', json_encode(array(date('y-m-d/H'), $Birthdays)));
        return $Birthdays;
    }

    public function ToString() {
        $Birthdays = $this->GetBirthdays();
        if (empty($Birthdays)) {
            return;
        }
        Gdn::UserModel()->GetIDs($Birthdays);
        $Return = '<div class="Box BirthdayModule"><h4>'
            .Plural(count($Birthdays), T("Today's Birthday"), T("Today's Birthdays"))
            .'</h4><p>';
        foreach ($Birthdays as $Birthday) {
            $Return .= UserPhoto(Gdn::UserModel()->GetID($Birthday), 'Medium').' ';
        }
        $Return .= '</p></div>';
        return $Return;
    }

}
