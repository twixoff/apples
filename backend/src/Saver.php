<?php

namespace backend\src;

use yii\base\UserException;
use yii\db\Exception;

class Saver
{

    public function findById($id)
    {
        $result = \Yii::$app->db->createCommand("SELECT * FROM apples WHERE id = :id")
            ->bindValues([':id' => $id])->queryOne();

        if(!$result) {
            throw new UserException("Яблоко не найдено ($id).");
        }

        return $result;
    }

    public function insertToDb(Apple $apple) {
        try {
            $db = \Yii::$app->db;
            $db->createCommand()->insert('apples', [
                'color' => $apple->color,
                'weight' => $apple->weight,
                'status' => $apple->status,
                'date_created' => $apple->date_created,
            ])->execute();
            $apple->id = $db->getLastInsertID();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateToDb(Apple $apple) {
        try {
            // TODO:: check if exist in db
            $db = \Yii::$app->db;
            $db->createCommand()->update('apples', [
                'color' => $apple->color,
                'weight' => $apple->weight,
                'status' => $apple->status,
                'date_created' => $apple->date_created,
                'date_fallen' => $apple->date_fallen,
            ], 'id = :id', [':id' => $apple->id])->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function removeFromDb(Apple $apple)
    {
        \Yii::$app->db->createCommand()->delete('apples', 'id = :id', [':id' => $apple->id])->execute();
    }
}