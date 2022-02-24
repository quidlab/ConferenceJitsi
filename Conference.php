<?php

namespace Plugin\ConferenceJitsi;

use Docebo;
use Get;
use stdClass;

require_once('jwt.php');
use \Firebase\JWT\JWT;

class Conference extends \PluginConference {

    public static $can_mod = false;

    function name(){
        return Plugin::getName();
    }

    function __construct() { }

    static function _getRoomTable() {

        return $GLOBALS['prefix_scs'].'_ConferenceJitsi';
    }

    static function _query($query) {

        $re = sql_query($query);
        return $re;
    }




    function canOpenRoom($start_time) {
        return true;
    }


    function insertRoom($idConference, $name, $startDate, $endDate, $maxParticipants) {

        $acl_manager =& Docebo::user()->getAclManager();
        $display_name = Docebo::user()->getUserName();
        $u_info = $acl_manager->getUser(getLogUserId(), false);
        $user_email=$u_info[ACL_INFO_EMAIL];
        $extra_conf = array();
		// will be used later
        /* 
        $extra_conf['showtoolbars'] = '#config.toolbarButtons=[%22microphone%22,%22camera%22,%22chat%22,%22desktop%22,%22hangup%22,%22tileview%22]'; */

        $res->result = true;
        require_once(_base_.'/lib/lib.json.php');
        $json = new \Services_JSON();

        //save in database the roomid for user login
        $insert_room = "
		INSERT INTO ".self::_getRoomTable()."
		( idConference,emailuser,displayname,ismoderator,extra_conf ) VALUES (
			'".$idConference."',
			'".$user_email."',
			'".$display_name."',
			'".$ismoderator."',
			'".$json->encode($extra_conf)."'
		)";

        if(!sql_query($insert_room)) return false;
        return sql_insert_id();

        return false;
    }

    static function roomInfo($room_id) {

        $room_open = "
		SELECT  *
		FROM ".self::_getRoomTable()."
		WHERE id = '".$room_id."'";
        $re_room = self::_query($room_open);

        return self::nextRow($re_room);
    }

    static function roomActive($idCourse, $at_date = false) {

        $room_open = "
		SELECT id,idCourse,idSt,name, starttime,endtime, confkey, emailuser, displayname, meetinghours,maxparticipants,audiovideosettings,maxmikes
		FROM ".self::_getRoomTable()."
		WHERE idCourse = '".$idCourse."'";

        if ($at_date !== false) {
            $room_open .= " AND endtime >= '".$at_date."'";
        }

        $room_open .= " ORDER BY starttime";

        $re_room = self::_query($room_open);

        return $re_room;
    }



    static function nextRow($re_room) {

        return sql_fetch_array($re_room);
    }

    function deleteRoom($room_id) {

        $res = self::api_delete_schedule($room_id);

        $room_del = "
		DELETE FROM ".self::_getRoomTable()."
		WHERE idConference = '".$room_id."'";
        $re_room = self::_query($room_del);

        return $re_room;
    }

    function getUrl($idConference,$room_type) {
        $lang =& \DoceboLanguage::createInstance('conference', 'lms');

        $conf=new \Conference_Manager();

        $conference = $conf->roomInfo($idConference);

        $acl_manager =& Docebo::user()->getAclManager();
        $username = Docebo::user()->getUserName();
        $u_info = $acl_manager->getUser(getLogUserId(), false);
        $user_email=$u_info[ACL_INFO_EMAIL];
		$avatar=$u_info[ACL_INFO_AVATAR];
		$avatar_url='https://'.$_SERVER['SERVER_NAME'].'/files/appCore/photo/'.$avatar;
		//echo ($avatar_url);
        $query2="SELECT * FROM ".self::_getRoomTable()." WHERE idConference = '".$idConference."'";
        $re_room = self::_query($query2);
        $room=self::nextRow($re_room);


        if ($room["ismoderator"]==0) {
            $moderator=false;
        } else {
             $moderator=true;
        }
				
		$Jitsi_Server = Get::sett('ConferenceJitsi_ConferenceJitsi_server', "");
		$key=Get::sett('ConferenceJitsi_secretkey', "");
		$aud=Get::sett('ConferenceJitsi_applicationidentifier', "");
		$exp=time()+1200;
		$RoomName = self::getRoomName($idConference);
$payload = array(
	"context" => array ( "user" => 
						array( 
						"avatar" => $avatar_url,
						"name"=> $username,
						"email"=> $user_email
						)),
    "aud"=> $aud,
	"iss"=> $aud,
	"sub"=> $Jitsi_Server,
	"room"=> $RoomName,
	"moderator"=> true,
	"exp"=> $exp
	
	);

$jwt = JWT::encode($payload, $key);

$_url=$Jitsi_Server.$RoomName.'?jwt='.$jwt;

$extra='#config.toolbarButtons=[%22microphone%22,%22camera%22,%22chat%22,%22desktop%22,%22hangup%22,%22tileview%22]';
$url= '<a href="'. $_url .$extra.'" target="_blank">'.$lang->def('_ENTER').'</a>';


        echo($url);
		return $url;
    }

    static function getRoomName($idConference) {
        $query = "SELECT * FROM ".$GLOBALS['prefix_scs']."_room WHERE id = '".$idConference."'";
		//echo ($query);
        $res = self::_query($query);
        $info = self::nextRow($res);
		return ($info['name']);
        
    }

}