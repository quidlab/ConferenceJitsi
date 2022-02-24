<?php

namespace Plugin\ConferenceJitsi;

class Plugin extends \FormaPlugin {

	public function install() {
        // test salt : 8cd8ef52e8e101574e400365b55e11a6
        //parent::addSetting('ConferenceJitsi_max_mikes','string',255,'2');
        parent::addSetting('ConferenceJitsi_max_participant','string',255,'300');
        parent::addSetting('ConferenceJitsi_max_room','string',255,'999');
        //parent::addSetting('ConferenceJitsi_password_moderator','string',255,'password.moderator');
        parent::addSetting('ConferenceJitsi_applicationidentifier','string',255,'applicationidentifier');
        parent::addSetting('ConferenceJitsi_secretkey','string',255,'to match with key on server');
        parent::addSetting('ConferenceJitsi_ConferenceJitsi_server','string',255,'http://vcsrv11.foqus.vc/');
        //parent::addSetting('ConferenceJitsi_port','string',255,'80');
        //parent::addSetting('ConferenceJitsi_user','string',255,'');
    }

}
?>