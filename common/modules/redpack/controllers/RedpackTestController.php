<?php

namespace common\modules\redpack\controllers;

use common\modules\redpack\models\RedpackTest;
use common\modules\redpack\models\RedpackTestSearch;
use Imagine\Gd\Font;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Yii;
use yii\filters\VerbFilter;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * RedpackTestController implements the CRUD actions for RedpackTest model.
 */
class RedpackTestController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all RedpackTest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RedpackTestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $models = $dataProvider->getModels();
        $html = $this->renderPartial('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        return $this->getHtml($html);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function getHtml($html)
    {
        $saveTag = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">';
        $str = "<html>{$html}</html>";
        $html_gbk = iconv("UTF-8", "GB2312//IGNORE", $html);
        //$html_gbk = $html;
        $str_gbk = "{$saveTag}{$html_gbk}</html>";
        $fp = fopen(Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'abc.doc', "wb");
        fwrite($fp, $str_gbk);
        fclose($fp);

        return $str;
    }

    public function actionIndex2()
    {
        Image::frame('img/anonymous.jpg', 5, '666', 0)
            ->rotate(-8)
            ->save('img/anonymous1.jpg', ['jpeg_quality' => 50]);

        $imagine = Image::getImagine();
        $palette = new \Imagine\Image\Palette\RGB();
        $image = $imagine->create(new Box(600, 800), $palette->color('#fff'));
        $image->draw()->ellipse(new Point(200, 150), new Box(300, 225), $image->palette()->color('000'));
        $image->draw()->line(new Point(200, 150), new Point(200, 225), $image->palette()->color('000'));
        $w = 200;
        $h = 80;
        $image->draw()->polygon([
            new Point(100, 100),
            new Point(100, 100 + $h),
            new Point(100 + $w, 100 + $h),
            new Point(100 + $w, 100),
        ], $image->palette()->color('000'));
        $image->draw()->text('ABCD中', new Font('img/arial.ttf', 12, $image->palette()->color('000')), new Point(10, 10));
        $image->save('img/ellipse.png');

        return 'ok';
    }

    // http://127.0.0.1/wxp/backend/web/index.php?r=redpack%2Fredpack-test/index3
    public function actionIndex3()
    {
        $im = imagecreatetruecolor(600, 800);
        //imageantialias($im, true);
        $black = imagecolorallocate($im, 0, 0, 0);
        $white = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $white);
        $text = '世界你好';
        $size = 10;
        $this->drawBox($im, 300, 200, $text, $size, $black);
        $this->drawBox($im, 300, 300, $text . ',又一个wordpress站点', $size, $black);
        imageline($im, 0, 0, 200, 100, $black);

        imagejpeg($im, Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'simple.jpg', 100);
        imagedestroy($im);
        return 'ok';
    }

    public function drawBox($im, $x, $y, $text, $size, $color)
    {
        $font = Yii::$app->runtimePath . DIRECTORY_SEPARATOR . "msyh.ttf";
        $box = imagettfbbox($size, 0, $font, $text);
        $width = abs($box[4] - $box[0]);
        $height = abs($box[5] - $box[1]);
        imagefttext($im, $size, 0, $x - $width / 2, $y + $height / 2, $color, $font, $text);
        imagerectangle($im, $x - $width / 2 - 10, $y - $height / 2 - 10, $x + $width / 2 + 10, $y + $height / 2 + 10, $color);
    }

    // http://127.0.0.1/wxp/backend/web/index.php?r=redpack%2Fredpack-test/table
    public function actionTable()
    {
        $searchModel = new RedpackTestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy([
            'type1' => SORT_ASC,
            'type2' => SORT_ASC,
            'factor' => SORT_DESC,
        ]);
        $models = $dataProvider->getModels();

        $text = '';
        $text .= $this->renderPartial('css');

        $text .= '<table class="test" align="center">';

        $line = '<thead><tr>';
        $model = new RedpackTest();
        $line .= '<th>' . $model->getAttributeLabel('type1') . '</th>';
        $line .= '<th>' . $model->getAttributeLabel('type2') . '</th>';
        $line .= '<th width="180">' . $model->getAttributeLabel('factor') . '</th>';
        $line .= '<th width="180">' . $model->getAttributeLabel('real') . '</th>';
        $line .= '<th width="180">' . $model->getAttributeLabel('sum') . '</th>';
        $line .= '</tr></thead>';
        $text = $text . $line;

        $text .= '<tbody>';
        foreach ($models as $model) {
            $line = '';
            //foreach ($model->attributes as $attribute) {
            //}
            $line .= $this->getTagType1($model);
            $line .= $this->getTagType2($model);
            //$line .= "<td>{$model->type2}</td>";
            $line .= "<td>{$model->factor}</td>";
            $line .= "<td>{$model->real}</td>";
            $line .= "<td>{$model->sum}</td>";
            $text = $text . "<tr>$line</tr>";
        }
        $text .= '</tbody>';
        $text .= '</table>';

        return $this->getHtml($text);
    }

    public function getTagType1($model)
    {
        static $tags = [];
        if (!empty($tags[$model->type1])) {
            return '';
        }
        $tags[$model->type1] = true;
        $count = RedpackTest::find()->where(['type1' => $model->type1])->count();
        return "<td rowspan=\"$count\">{$model->type1}</td>";
    }

    public function getTagType2($model)
    {
        static $tags = [];
        if (!empty($tags[$model->type1][$model->type2])) {
            return '';
        }
        $tags[$model->type1][$model->type2] = true;
        $count = RedpackTest::find()->where(['type1' => $model->type1, 'type2' => $model->type2])->count();
        return "<td rowspan=\"$count\">{$model->type2}</td>";
    }

    /**
     * Displays a single RedpackTest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the RedpackTest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RedpackTest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RedpackTest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new RedpackTest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RedpackTest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RedpackTest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RedpackTest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
