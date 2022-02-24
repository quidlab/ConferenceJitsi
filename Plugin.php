<?php

namespace Plugin\ConferenceJitsi;

class Plugin extends \FormaPlugin {

	public function install() {
        
        parent::addSetting('ConferenceJitsi_max_participant','string',255,'300');
        parent::addSetting('ConferenceJitsi_max_room','string',255,'999');
        parent::addSetting('ConferenceJitsi_applicationidentifier','string',255,'applicationidentifier');
        parent::addSetting('ConferenceJitsi_secretkey','string',255,'to match with key on server');
        parent::addSetting('ConferenceJitsi_ConferenceJitsi_server','string',255,'http://meet.myjitsi.com/');
        
    }

}
?>