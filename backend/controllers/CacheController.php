<?php
/**
 * 清理缓存控制器
 */

namespace backend\controllers;
use yii;

class CacheController extends MController
{
    /**
     * 清理缓存
     */
    public function actionClear()
    {
        $request = Yii::$app->request;
        $cache  = $request->post('cache','');
        $backupCache = $request->post('backupCache','');

        if (Yii::$app->request->post())
        {
            //删除文件缓存
            if($cache)
            {
                $flush = Yii::$app->cache->flush();
            }

            //删除备份缓存
            if($backupCache)
            {
                $path   = Yii::$app->params['dataBackupPath'];
                $lock   = realpath($path) . DIRECTORY_SEPARATOR.Yii::$app->params['dataBackLock'];
                array_map("unlink", glob($lock));
            }

            if($flush == true || !count(glob($lock)))
            {
                $this->message("数据缓存清理成功",$this->redirect(['clear']));
            }
            else
            {
                $this->message("数据缓存清理失败",$this->redirect(['clear']),'error');
            }
        }

        return $this->render('clear',[
        ]);

    }
}