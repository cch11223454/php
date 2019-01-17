<?php


/*
'JPUSH_CONFIG'=>array(
        'app_key'=>"62cb20c4c32d5b839adf491b",
        'master_secret'=>"4849445dd922a41cdd8eaed2",
),
*/

//极光推送
function j_push($rid,$android_msg,$ios_msg){

    if(is_array($rid)){
        $rid=implode(',', $rid);
    }
    vendor('JPushP.Client');
    vendor('JPushP.PushPayload');
    vendor('JPushP.Http');
    vendor('JPushP.Config');
    vendor('JPushP.Exceptions.JPushException');
    vendor('JPushP.Exceptions.APIRequestException');
    $config=C("JPUSH_CONFIG");

    $client = new \JPush\Client ($config['app_key'],$config['master_secret']);


    //$report = $client->report();

    try {
                $result = $client->push()
                    ->setPlatform('all')
                    ->addRegistrationId($rid)
                    //->addAlias('alias')
        //                ->addAllAudience()
                    // ->setNotificationAlert('Hello')

                    ->androidNotification($android_msg['content'], $android_msg['message'])
                   ->iosNotification($ios_msg['content'],$ios_msg['message'])
                    
                    ->send();
        

            } catch (\JPush\Exceptions\APIConnectionException $e) {
                
                print $e;
            } catch (\JPush\Exceptions\APIRequestException $e) {
                
                print $e;
            }

            return $result;
}

?>