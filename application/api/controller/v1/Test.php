<?php
/**
 * Created by PhpStorm.
 * User: lingmeng
 * Date: 2019/6/4
 * Time: 16:57
 */

namespace app\api\controller\v1;


class Test
{
    public function index(){
        $token='{"refresh_token":"25.c0df7dd44838492f5aa521dcfa5f6408.315360000.1873767209.282335-16054793","expires_in":2592000,"session_key":"9mzdCPKlu2jR0NOWtIBPIPg2qNEARqiwfMBWUjlghGVWlx5X5pefVzFpmJrc1v3yMFCPb6iSBa3wkje7voYUOwSBGweaPw==","access_token":"24.36320219abddf8d73fa6b2ab6ced27ab.2592000.1560999209.282335-16054793","scope":"vis-faceverify_FACE_Police public brain_all_scope vis-classify_\u5b9e\u65f6\u68c0\u7d22-\u76f8\u4f3c brain_realtime_same_hq brain_realtime_similar brain_realtime_product wise_adapt lebo_resource_base lightservice_public hetu_basic lightcms_map_poi kaidian_kaidian ApsMisTest_Test\u6743\u9650 vis-classify_flower lpq_\u5f00\u653e cop_helloScope ApsMis_fangdi_permission smartapp_snsapi_base iop_autocar oauth_tp_app smartapp_smart_game_openapi oauth_sessionkey smartapp_swanid_verify smartapp_opensource_openapi smartapp_opensource_recapi","session_secret":"5804be7356064e9851a0088b7c8aaf9c"}';
        return json_decode($token,true);
    }

}