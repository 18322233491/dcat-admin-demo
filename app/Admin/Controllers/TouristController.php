<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\UserTable;
use App\Admin\Repositories\Tourist;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Widgets\LazyTable;
use Dcat\Admin\Layout\Content;

class TouristController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    public function index(Content $content)
    {
        $table = UserTable::make()->simple();

        return $content->body(LazyTable::make($table));
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Tourist(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('sex');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Tourist(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
