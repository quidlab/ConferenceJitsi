# ConferenceJitsi WIP
 FormaLMS Jitsi Plugin
Works with only jitsi server with jwt protection. BigBlueButton must not be uninstalled for it to work, however it can be disabled.
As of version 3.0.1 /appScs/lib/lib.conference.1.3.plugins.php file needs to be modified change 
$pg->get_plugin('ConferenceBBB');  to $pg->get_plugin($room_type); this appears at 2 places.
