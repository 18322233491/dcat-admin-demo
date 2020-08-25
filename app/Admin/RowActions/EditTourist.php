<?php

namespace App\Admin\RowActions;

use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Widgets\Modal;
use App\Admin\Forms\EditTourist as EditTouristForm;

class EditTourist extends RowAction
{
    public function render()
    {
        // 实例化表单类并传递自定义参数
        $form = EditTouristForm::make()->payload(['id' => $this->getKey()]);

        return Modal::make()
            ->lg()
            ->title($this->title)
            ->body($form)
            ->button($this->title);
    }
}