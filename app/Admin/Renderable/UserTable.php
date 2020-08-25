<?php

namespace App\Admin\Renderable;

use App\Admin\RowActions\DeleteTourist;
use App\Admin\RowActions\EditTourist;
use App\Models\Tourist;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\LazyRenderable;

class UserTable extends LazyRenderable
{
    public function grid(): Grid
    {
        return Grid::make(new Tourist(), function (Grid $grid) {
//            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('sex','性别')->using([1 => '男', 2 => '女'])->label([
                1 => 'danger',
                2 => 'success',
            ]);
            $grid->column('img','封面图')->image('',100,100);

            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->disableRefreshButton();
            $grid->disableViewButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            $grid->actions([new DeleteTourist(Tourist::class),new EditTourist(' 编辑')]);
            $grid->filter(function (Grid\Filter $filter) {
                // 更改为 panel 布局
                $filter->expand();
                $filter->panel();
                $filter
                    ->where('性别',function ($query){
                        if ($this->input != 0) {
                            $query->where('sex','=',$this->input);
                        } else {
                            $query->whereIn('sex',['1','2']);
                        }
                    })
                    ->select(['0'=>'全部','1' => '男','2'=>'女'])
                    ->width(2);
                $filter->setName('昵称')->like('name',$this->input)->width(3);
                $filter->customBetween('created_at')->date()->width(4);
            });


            $titles = ['id' => 'ID', 'name' => '姓名', 'sex' => '性别','created_at'=>'时间'];
            $grid->export()->titles($titles)->rows(function (array $rows) {
                foreach ($rows as $index => &$row) {
                    $row['sex'] = $row['sex'] === 1 ? '男' : '女';
                }

                return $rows;
            })->filename('管理员数据')->csv();

        });
    }
}