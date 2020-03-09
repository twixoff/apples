<?php

namespace backend\src;

use yii\base\UserException;

class Apple implements IApple
{
    public $id;

    private $available_colors = ['red', 'green', 'gold', 'yellow'];

    public $color;

    public $date_created;

    public $date_fallen;

    public $status = 1;

    public $weight = 100;

    public function __construct()
    {
        if(!func_num_args())      // create new Apple
        {
            $random_color_indx = array_rand($this->available_colors);
            $this->color = $this->available_colors[$random_color_indx];
            $this->date_created = time();
        }
        else // create exist Apple
        {
            $args = func_get_arg(0);
            $this->color = $args['color'];
            $this->id = $args['id'];
            $this->status = $args['status'];
            $this->weight = $args['weight'];
            $this->date_created = $args['date_created'];
            $this->date_fallen = $args['date_fallen'];

            // check time && status
            if($args['status'] == 2 && (time() - $args['date_fallen']) >= 5*3600) {
                $this->status = 3;
            }
        }
    }

    public function fall()
    {
        $this->changeStatus(2);
        $this->date_fallen = time();
    }

    /*
     * 1 - new
     * 2 - fallen
     * 3 - junk
     * 4 - removed
     */
    public function changeStatus($status) {
        if($this->status > $status) {
            throw new UserException('Неверный статус');
        }
        $this->status = $status;
    }

    public function getStatusName()
    {
        switch ($this->status)
        {
            case 1: $status = 'растет на ветке'; break;
            case 2: $status = 'лежит на земле'; break;
            case 3: $status = 'испорчено'; break;
            case 4: $status = 'удалено'; break;
            default: $status = 'не определен';
        }

        return $status;
    }

    public function eat($percent)
    {
        if($this->status == 1) {
            throw new UserException('Нельзя откусить, яблоко еще не упало.');
        }
        if($this->status == 3) {
            throw new UserException('Нельзя откусить, яблоко испорчено.');
        }
        if($this->weight == 0) {
            throw new UserException('Нельзя откусить, яблоко съедено полностью.');
        }
        $this->weight = ($this->weight - $percent) < 0 ? 0 : $this->weight - $percent;
    }

    public function remove()
    {
        if($this->weight > 0 && $this->status != 3) {
            throw new UserException('Необходимо съесть яблоко перед его удалением.');
        }
        $this->status = 4;
    }

}