<?php
namespace console\controllers;

use yii\console\Controller;
use common\models\LogModel;

/**
* Обслуживает вызовы cron
*/
class CronController extends Controller
{
    /**
    * Выполняет асинхронные запросы к URL для которых это регламентировано
    * и сохраняет результаты в журнал
    */
    public function actionIndex()
    {
        $mch = curl_multi_init();
        $chs = new WeakMap();

        foreach (LogModel::getAvailable()->all() as $data) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                \CURLOPT_URL => $data->href
                , \CURLOPT_HEADER => true
                , \CURLOPT_FOLLOWLOCATION => true
                , \CURLOPT_RETURNTRANSFER => true
                , \CURLOPT_ENCODING => 'gzip,deflate',
            ]);

            $chs[$ch] = $data->href;

            curl_multi_add_handle($ch);
        }

        $running = null;

        do {
            if (curl_multi_exec($mch, $running) !== \CURLM_OK) {
                break;
            }

            while (true) {
                $info = curl_multi_info_read($mch);

                if (!is_array($info)) {
                    break;
                }
                if ($info['msg'] !== \CURLMSG_DONE) {
                    continue;
                }

                $ch = $info['handle'];

                $body = $info['result'] === \CURLE_OK ? curl_multi_getcontent($ch) : curl_strerror($info['result']);
                $status = curl_getinfo($ch, \CURLINFO_HTTP_CODE);

                LogModel::addItem($chs[$ch], $status, $body);

                curl_multi_remove_handle($ch);
                curl_close($ch);
            }
        } while ($running);

        curl_multi_close($running);
    }
}