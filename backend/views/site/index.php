<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\bootstrap\Html;
$this->title = "My Apple's Application";
?>
<div class="site-index">

    <?= Html::beginForm('', 'post', ['class' => 'form-inline', 'style' => 'margin-bottom: 20px;']) ?>
        <div class="form-group">
            Добавить яблоки
        </div>
        <div class="form-group">
            <?= Html::input('number', 'count', $count ?? 1, [
                'class' => 'form-control text-center', 'style' => 'width: 60px;',
                'min' => 1
            ]) ?> шт.
        </div>
        <div class="form-group">
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?= Html::endForm() ?>

    <?php if($apples) : ?>
        <div class="row">
            <?php foreach ($apples as $apple) : ?>
                <div class="col-sm-6 col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">Яблоко цвета «<?= $apple->color ?>» - <?= $apple->id ?></div>
                        </div>
                        <div class="panel-body">
                            <p>Появилось на ветке <?= Yii::$app->formatter->asRelativeTime($apple->date_created) ?></p>
                            <p>Статус: <?= $apple->getStatusName() ?></p>
                            <p>Съедено: <?= 100 - $apple->weight ?>%</p>
                        </div>
                        <div class="panel-body">
                            <?php if($apple->status == 1) : ?>
                                <a href="<?= Url::to(['fall', 'id' => $apple->id]) ?>" class="btn btn-sm btn-info">упасть</a>
                            <?php else : ?>
                                <button type="button" class="btn btn-sm btn-info" disabled="disabled">упасть</button>
                            <?php endif; ?>
                            <?php if($apple->weight > 0 ) : ?>
                                <button type="button" data-toggle="modal" data-target="#modal-<?= $apple->id ?>" class="btn btn-sm btn-info">откусить</button>
                            <?php else : ?>
                                <button type="button" class="btn btn-sm btn-info" disabled="disabled">откусить</button>
                            <?php endif; ?>
                            <a href="<?= Url::to(['remove', 'id' => $apple->id]) ?>" class="btn btn-sm btn-info">удалить</a>
                        </div>
                    </div>
                    <div class="modal fade" id="modal-<?= $apple->id ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Сколько откусить?</h4>
                                </div>
                                <div class="modal-body text-center">
                                    <a href="<?= Url::to(['eat', 'id' => $apple->id, 'percent' => 25]) ?>" class="btn btn-sm btn-info">25%</a>
                                    <a href="<?= Url::to(['eat', 'id' => $apple->id, 'percent' => 50]) ?>" class="btn btn-sm btn-info">50%</a>
                                    <a href="<?= Url::to(['eat', 'id' => $apple->id, 'percent' => 100]) ?>" class="btn btn-sm btn-info">100%</a>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
