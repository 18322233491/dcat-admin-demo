<?php

namespace App\Admin\Forms;

use App\Models\Tourist;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Contracts\LazyRenderable;

class EditTourist extends Form implements LazyRenderable
{
    use LazyWidget;

    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return Response
     */
    public function handle(array $input)
    {
        // 获取外部传递参数
        $id = $this->payload['id'] ?? null;

        // 表单参数
        $name = $input['name'] ?? null;
        $sex  = $input['sex'] ?? 1;
        $img  = $input['img'] ?? '';
        if (!$id) {
            return $this->error('参数错误');
        }

        $user = Tourist::query()->find($id);

        if (!$user) {
            return $this->error('用户不存在');
        }

        $user->update(['name' => $name, 'sex' => $sex, 'img' => $img]);

        return $this->success('修改成功', 'tourists');
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->text('name')->required();
        $this->radio('sex')->options(['1' => '男', '2' => '女'])->default('1');
        $this->image('img', '封面图');
    }

    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        $user = Tourist::query()->find($this->payload['id']);
        return [
            'name' => $user->name,
            'sex'  => $user->sex,
            'img'  => $user->img,
        ];
    }
}
