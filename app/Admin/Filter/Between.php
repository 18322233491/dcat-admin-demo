<?php


namespace App\Admin\Filter;


use Dcat\Admin\Grid\Filter\AbstractFilter;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Filter\Presenter\DateTime;
use Illuminate\Support\Arr;

class Between extends AbstractFilter
{
    // 自定义你的过滤器显示模板
    protected $view = 'admin::filter.between';

    // 这个方法用于生成过滤器字段的唯一id
    // 通过这个唯一id则可以用js代码对其进行操作
    public function formatId($column)
    {
        $id   = str_replace('.', '_', $column);
        $name = $this->parent->getGrid()->getName();

        return ['start' => "{$name}{$id}_start", 'end' => "{$name}{$id}_end"];
    }

    // form表单name属性格式化
    protected function formatName($column)
    {
        $columns = explode('.', $column);

        if (count($columns) == 1) {
            $name = $columns[0];
        } else {
            $name = array_shift($columns);

            foreach ($columns as $column) {
                $name .= "[$column]";
            }
        }

        return ['start' => "{$name}[start]", 'end' => "{$name}[end]"];
    }

    // 创建条件
    // 这里构建的条件支持`Laravel query builder`即可。
    public function condition($inputs)
    {
        if (!Arr::has($inputs, $this->column)) {
            return;
        }

        $this->value = Arr::get($inputs, $this->column);
        $value = array_filter($this->value, function ($val) {
            return $val !== '';
        });

        if (empty($value)) {
            return;
        }
        if (!isset($value['start']) && isset($value['end'])) {
            // 这里返回的数组相当于
            // $query->where($this->column, '<=', $value['end']);
            return $this->buildCondition($this->column, '<=', $value['end']);
        }

        if (!isset($value['end']) && isset($value['start'])) {
            // 这里返回的数组相当于
            // $query->where($this->column, '>=', $value['end']);
            return $this->buildCondition($this->column, '>=', $value['start']);
        }

        $this->query = 'whereBetween';

        // 这里返回的数组相当于
        // $query->whereBetween($this->column, $value['end']);
        return $this->buildCondition($this->column, $this->value);
    }

    // 自定义过滤器表单显示方式
    public function datetime($options = [])
    {
        $this->view = 'admin::filter.betweenDatetime';

        DateTime::collectAssets();

        $this->setupDatetime($options);

        return $this;
    }

    protected function setupDatetime($options = [])
    {
        $options['format'] = Arr::get($options, 'format', 'YYYY-MM-DD HH:mm:ss');
        $options['locale'] = Arr::get($options, 'locale', config('app.locale'));

        $startOptions = json_encode($options);
        $endOptions = json_encode($options + ['useCurrent' => false]);

        // 通过上面格式化后的id对表单进行你想要的操作
        $script = <<<JS
            $('#{$this->id['start']}').datetimepicker($startOptions);
            $('#{$this->id['end']}').datetimepicker($endOptions);
            $("#{$this->id['start']}").on("dp.change", function (e) {
                $('#{$this->id['end']}').data("DateTimePicker").minDate(e.date);
            });
            $("#{$this->id['end']}").on("dp.change", function (e) {
                $('#{$this->id['start']}').data("DateTimePicker").maxDate(e.date);
            });
JS;

        Admin::script($script);
    }
}